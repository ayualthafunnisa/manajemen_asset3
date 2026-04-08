<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\License;
use App\Models\Payment;
use App\Models\Notification; // Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;
use Midtrans\Notification as MidtransNotification;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /* ════════════════════════════════════════════════════════════
     * REGISTER — GET SNAP TOKEN (AJAX)
     * ════════════════════════════════════════════════════════════ */
 
    public function getPaymentToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'min:8', 'confirmed'],
            'amount'   => ['nullable', 'integer'],
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
        $amount = $request->amount ?? 500000;
        $planType = $request->plan_type ?? 'yearly';
        
        if ($planType === 'monthly') {
            $amount = 49000;
        }

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
                'password'  => $data['password'],
                'plan_type' => $planType,
                'amount'    => $amount,
            ]]);
            
            $orderId = 'LIC-' . strtoupper(Str::random(8)) . '-' . time();
            session(['temp_order_id' => $orderId]);

            // Konfigurasi Midtrans
            MidtransConfig::$serverKey    = config('services.midtrans.server_key');
            MidtransConfig::$isProduction = config('services.midtrans.is_production', false);
            MidtransConfig::$isSanitized  = true;
            MidtransConfig::$is3ds        = true;

            $itemName = $planType === 'monthly' 
                ? 'Lisensi Asset Management 1 Bulan' 
                : 'Lisensi Asset Management 1 Tahun';

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
            session()->forget(['temp_registration', 'temp_order_id']);
            
            return response()->json([
                'message' => 'Gagal memproses pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }
 
    /* ════════════════════════════════════════════════════════════
     * REGISTER — FINAL SUBMIT (setelah Midtrans callback)
     * ════════════════════════════════════════════════════════════ */
 
    public function register(Request $request)
    {
        Log::info('Final Registration Data:', $request->all());

        $request->validate([
            'order_id'           => ['required', 'string'],
            'transaction_status' => ['required', 'string'],
            'name'               => ['required', 'string', 'max:255'],
            'email'              => ['required', 'email'],
            'phone'              => ['nullable', 'string', 'max:20'],
            'password'           => ['required', 'min:8'],
            'amount'             => ['nullable', 'integer'],
            'plan_type'          => ['nullable', 'string', 'in:monthly,yearly'],
        ]);

        $existingUser = User::where('email', $request->email)->first();
        if ($existingUser) {
            return response()->json([
                'success' => false,
                'message' => 'Email sudah terdaftar. Silakan login.'
            ], 422);
        }

        $isPaid = in_array($request->transaction_status, ['settlement', 'capture', 'success']);
        
        if (!$isPaid) {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran belum selesai. Silakan selesaikan pembayaran terlebih dahulu.'
            ], 422);
        }

        try {
            DB::beginTransaction();
            
            // Generate activation token
            $activationToken = Str::random(60);
            
            // Buat user dengan status pending (menunggu approval super admin)
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'phone'    => $request->phone,
                'password' => Hash::make($request->password),
                'role'     => 'admin_sekolah',
                'status'   => 'pending', // Pending approval
                'activation_token' => $activationToken,
                'activation_token_expires_at' => now()->addDays(7),
            ]);
            
            $planType = $request->plan_type ?? 'yearly';
            $amount = $request->amount ?? ($planType === 'monthly' ? 49000 : 500000);
            $durationMonths = $planType === 'monthly' ? 1 : 12;
            
            $expiredDate = $planType === 'monthly' 
                ? now()->addMonth()->toDateString() 
                : now()->addYear()->toDateString();
            
            // Buat license dengan status pending approval
            $license = License::create([
                'user_id'         => $user->id,
                'license_key'     => 'KEY-' . strtoupper(Str::random(16)),
                'kode_lisensi'    => $request->order_id,
                'license_type'    => $planType === 'monthly' ? 'basic' : 'premium',
                'duration_months' => $durationMonths,
                'start_date'      => now()->toDateString(),
                'expired_date'    => $expiredDate,
                'is_active'       => false,
                'payment_status'  => 'settlement',
                'approval_status' => 'pending',
            ]);
            
            // Buat record payment
            $payment = Payment::create([
                'user_id'     => $user->id,
                'license_id'  => $license->id,
                'order_id'    => $request->order_id,
                'amount'      => $amount,
                'status'      => 'settlement',
                'paid_at'     => now(),
            ]);

            // Buat notifikasi untuk Super Admin
            $superAdmins = User::where('role', 'super_admin')->get();
            foreach ($superAdmins as $superAdmin) {
                Notification::create([
                    'user_id' => $superAdmin->id,
                    'title' => '📝 Pendaftaran Baru Menunggu Approval',
                    'message' => "User {$user->name} ({$user->email}) telah melakukan registrasi dan pembayaran. Silakan approve akun.",
                    'type' => 'registration_pending',
                    'data' => json_encode([
                        'user_id' => $user->id,
                        'license_id' => $license->id,
                        'user_name' => $user->name,
                        'user_email' => $user->email
                    ]),
                    'is_read' => false
                ]);
            }
            
            DB::commit();
            
            // JANGAN LOGIN USER - biarkan dia di halaman sukses
            session()->forget(['temp_registration', 'temp_order_id']);
            
            // Simpan email user untuk ditampilkan di halaman sukses
            session(['pending_approval_email' => $user->email]);
            
            return response()->json([
                'success' => true,
                'message' => '✅ Pembayaran berhasil! Akun Anda akan segera diproses oleh Super Admin. Silakan cek email Anda untuk informasi aktivasi.',
                'redirect' => route('registration.pending')
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
 
    /**
     * Tampilkan halaman pending approval setelah registrasi
     */
    public function pendingApproval()
    {
        $email = session('pending_approval_email');
        return view('auth.pending-approval', compact('email'));
    }

    /* ════════════════════════════════════════════════════════════
     * MIDTRANS WEBHOOK
     * ════════════════════════════════════════════════════════════ */
 
    public function midtransWebhook(Request $request)
    {
        MidtransConfig::$serverKey    = config('services.midtrans.server_key');
        MidtransConfig::$isProduction = config('services.midtrans.is_production', false);
 
        try {
            $notif = new MidtransNotification();
            $orderId = $notif->order_id;
            $status = $notif->transaction_status;
            $fraud = $notif->fraud_status ?? null;
            $paymentType = $notif->payment_type ?? null;
            
            Log::info('Midtrans Webhook:', [
                'order_id' => $orderId,
                'status' => $status,
                'fraud' => $fraud,
                'payment_type' => $paymentType
            ]);
            
            // Cari payment berdasarkan order_id
            $payment = Payment::where('order_id', $orderId)->first();
            
            if (!$payment) {
                Log::warning('Payment not found for order_id: ' . $orderId);
                return response()->json(['message' => 'not found'], 404);
            }
 
            $payStatus = match(true) {
                $status === 'capture' && $fraud !== 'challenge' => 'settlement',
                $status === 'settlement' => 'settlement',
                in_array($status, ['cancel','deny','expire']) => $status,
                default => 'pending',
            };
 
            // Update payment
            $payment->update([
                'status' => $payStatus,
                'payment_type' => $paymentType,
                'midtrans_response' => json_encode($notif),
                'paid_at' => in_array($payStatus, ['settlement', 'capture']) ? now() : null,
            ]);
            
            // Update terkait license jika payment berhasil
            if ($payStatus === 'settlement' && $payment->license) {
                $payment->license->update([
                    'payment_status' => $payStatus,
                ]);
            }
 
            return response()->json(['message' => 'ok']);
            
        } catch (\Exception $e) {
            Log::error('Webhook Error: ' . $e->getMessage());
            return response()->json(['message' => 'error'], 500);
        }
    }

    public function activateAccount($token)
    {
        $user = User::where('activation_token', $token)
                    ->where('activation_token_expires_at', '>', now())
                    ->first();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Token aktivasi tidak valid atau sudah kadaluarsa.');
        }

        $user->update([
            'status' => 'active',
            'activation_token' => null,
            'activation_token_expires_at' => null,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('login')
            ->with('success', 'Akun berhasil diaktifkan. Silakan login.');
    }
}