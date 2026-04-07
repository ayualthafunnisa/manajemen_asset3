<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Instansi;
use App\Models\License;
use Laravolt\Indonesia\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;
use Midtrans\Notification;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }
 
    /* ════════════════════════════════════════════════════════════
     * REGISTER — GET SNAP TOKEN (AJAX)
     * Dipanggil JS sebelum popup Midtrans dibuka.
     * ════════════════════════════════════════════════════════════ */
 
    public function getPaymentToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'min:8', 'confirmed'],
            'amount'   => ['nullable', 'integer'], // Bisa dari frontend
            'plan_type'=> ['nullable', 'string', 'in:monthly,yearly'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        
        // Tentukan amount berdasarkan pilihan user
        $amount = $request->amount ?? 500000; // Default yearly
        $planType = $request->plan_type ?? 'yearly';
        
        // Jika monthly, amount = 49000
        if ($planType === 'monthly') {
            $amount = 49000;
        }

        // Cek apakah Midtrans sudah terkonfigurasi
        if (!config('services.midtrans.server_key')) {
            return response()->json([
                'message' => 'Konfigurasi pembayaran belum lengkap. Hubungi administrator.'
            ], 500);
        }

        try {
            // Simpan data registrasi sementara di session
            session(['temp_registration' => [
                'name'      => $data['name'],
                'email'     => $data['email'],
                'phone'     => $data['phone'] ?? null,
                'password'  => $data['password'], // Simpan password asli sementara
                'plan_type' => $planType,
                'amount'    => $amount,
            ]]);
            
            $orderId = 'LIC-' . strtoupper(Str::random(8)) . '-' . time();
            
            // Simpan order_id di session
            session(['temp_order_id' => $orderId]);

            // 4. Konfigurasi Midtrans
            MidtransConfig::$serverKey    = config('services.midtrans.server_key');
            MidtransConfig::$isProduction = config('services.midtrans.is_production', false);
            MidtransConfig::$isSanitized  = true;
            MidtransConfig::$is3ds        = true;

            // Nama item berdasarkan pilihan
            $itemName = $planType === 'monthly' 
                ? 'Lisensi Asset Management 1 Bulan' 
                : 'Lisensi Asset Management 1 Tahun';

            // 5. Susun Params
            $params = [
                'transaction_details' => [
                    'order_id'     => $orderId,
                    'gross_amount' => (int) $amount,
                ],
                'customer_details' => [
                    'first_name' => $data['name'],
                    'email'      => $data['email'],
                    'phone'      => $data['phone'] ?? '08123456789',
                ],
                'item_details' => [
                    [
                        'id'       => $planType === 'monthly' ? 'LISENSI-1BULAN' : 'LISENSI-1TAHUN',
                        'price'    => (int) $amount,
                        'quantity' => 1,
                        'name'     => $itemName,
                    ]
                ],
            ];

            $snapToken = Snap::getSnapToken($params);

            return response()->json([
                'snap_token' => $snapToken,
                'order_id'   => $orderId,
            ]);

        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            
            // Hapus session jika gagal
            session()->forget(['temp_registration', 'temp_order_id']);
            
            return response()->json([
                'message' => 'Gagal memproses pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }
 
    /* ════════════════════════════════════════════════════════════
     * REGISTER — FINAL SUBMIT (setelah Midtrans callback)
     * Membuat user dan license setelah payment berhasil
     * ════════════════════════════════════════════════════════════ */
 
    public function register(Request $request)
    {
        // Log untuk debug
        Log::info('Final Registration Data:', $request->all());

        $request->validate([
            'order_id'           => ['required', 'string'],
            'transaction_status' => ['required', 'string'],
            'name'               => ['required', 'string', 'max:255'],
            'email'              => ['required', 'email'],
            'phone'              => ['nullable', 'string', 'max:20'],
            'password'           => ['required', 'min:8'],
            'amount'             => ['nullable', 'integer'],
            'plan_type'          => ['nullable', 'string'],
        ]);

        // Cek apakah email sudah terdaftar (double check)
        $existingUser = User::where('email', $request->email)->first();
        if ($existingUser) {
            return response()->json([
                'success' => false,
                'message' => 'Email sudah terdaftar. Silakan login.'
            ], 422);
        }

        // Cek apakah pembayaran berhasil
        $isPaid = in_array($request->transaction_status, ['settlement', 'capture', 'success']);
        
        if (!$isPaid) {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran belum selesai. Silakan selesaikan pembayaran terlebih dahulu.'
            ], 422);
        }

        try {
            DB::beginTransaction();
            
            // Buat user
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'phone'    => $request->phone,
                'password' => Hash::make($request->password),
                'role'     => 'admin_sekolah',
                'status'   => 'active',
            ]);
            
            // Tentukan masa aktif license
            $planType = $request->plan_type ?? 'yearly';
            $amount = $request->amount ?? ($planType === 'monthly' ? 49000 : 500000);
            
            $expiredDate = $planType === 'monthly' 
                ? now()->addMonth()->toDateString() 
                : now()->addYear()->toDateString();
            
            // Buat license
            $license = License::create([
                'user_id'        => $user->id,
                'license_key'    => 'KEY-' . strtoupper(Str::random(16)),
                'kode_lisensi'   => $request->order_id,
                'start_date'     => now()->toDateString(),
                'expired_date'   => $expiredDate,
                'is_active'      => true,
                'payment_status' => 'settlement',
                'amount'         => $amount,
            ]);
            
            DB::commit();
            
            // Login user
            Auth::login($user);
            $request->session()->regenerate();
            
            // Hapus data temporary session
            session()->forget(['temp_registration', 'temp_order_id']);
            
            return response()->json([
                'success' => true,
                'message' => 'Selamat! Registrasi berhasil.',
                'redirect' => route('dashboard.admin')
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registration Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }
 
    /* ════════════════════════════════════════════════════════════
     * MIDTRANS WEBHOOK
     * ════════════════════════════════════════════════════════════ */
 
    public function midtransWebhook(Request $request)
    {
        MidtransConfig::$serverKey    = config('services.midtrans.server_key');
        MidtransConfig::$isProduction = config('services.midtrans.is_production', false);
 
        try {
            $notif = new Notification();
            $orderId = $notif->order_id;
            $status = $notif->transaction_status;
            $fraud = $notif->fraud_status ?? null;
            
            Log::info('Midtrans Webhook:', [
                'order_id' => $orderId,
                'status' => $status,
                'fraud' => $fraud
            ]);
            
            // Cari license berdasarkan kode_lisensi
            $license = License::where('kode_lisensi', $orderId)->first();
            
            if (!$license) {
                Log::warning('License not found for order_id: ' . $orderId);
                return response()->json(['message' => 'not found'], 404);
            }
 
            $payStatus = match(true) {
                $status === 'capture' && $fraud !== 'challenge' => 'settlement',
                $status === 'settlement' => 'settlement',
                in_array($status, ['cancel','deny','expire']) => $status,
                default => 'pending',
            };
 
            $isActive = $payStatus === 'settlement';
 
            $license->update([
                'payment_status' => $payStatus, 
                'is_active' => $isActive
            ]);
            
            if ($isActive && $license->user) {
                $license->user->update(['status' => 'active']);
            }
 
            return response()->json(['message' => 'ok']);
            
        } catch (\Exception $e) {
            Log::error('Webhook Error: ' . $e->getMessage());
            return response()->json(['message' => 'error'], 500);
        }
    }
}