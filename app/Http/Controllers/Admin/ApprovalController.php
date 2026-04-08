<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\License;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ApprovalController extends Controller
{
    /**
     * Menampilkan halaman approvals
     */
    public function index()
    {
        $pendingLicenses = License::with(['user', 'payments'])
            ->where('approval_status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();

        $approvedLicenses = License::with(['user', 'approver'])
            ->where('approval_status', 'approved')
            ->orderBy('approved_at', 'desc')
            ->limit(50)
            ->get();

        $rejectedLicenses = License::with(['user', 'approver'])
            ->where('approval_status', 'rejected')
            ->orderBy('updated_at', 'desc')
            ->limit(50)
            ->get();

        return view('super-admin.approvals', compact(
            'pendingLicenses', 
            'approvedLicenses', 
            'rejectedLicenses'
        ));
    }

    /**
     * Menampilkan detail notifikasi dan user
     */
    public function show($licenseId)
    {
        $license = License::with(['user', 'payments'])->findOrFail($licenseId);
        
        // Ambil data user
        $user = $license->user;
        
        // Hitung total pembayaran
        $totalPayment = $license->payments->sum('amount');
        
        // Mark notification as read jika ada
        $notification = Notification::where('data', 'like', '%"license_id":'.$licenseId.'%')
            ->where('user_id', Auth::id())
            ->where('is_read', false)
            ->first();
            
        if ($notification) {
            $notification->markAsRead();
        }
        
        return view('super-admin.approval-detail', compact('license', 'user', 'totalPayment'));
    }

    /**
     * Approve user and license
     */
    public function approve($licenseId)
    {
        try {
            DB::beginTransaction();

            $license = License::with('user')->findOrFail($licenseId);

            if ($license->approval_status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Lisensi sudah diproses sebelumnya.'
                ], 400);
            }

            // Generate activation token
            $activationToken = Str::random(60);
            
            // Update license
            $license->update([
                'approval_status' => 'approved',
                'is_active' => true,
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            // Update user status dan activation token
            if ($license->user) {
                $license->user->update([
                    'status' => 'pending', // Status menunggu aktivasi via email
                    'activation_token' => $activationToken,
                    'activation_token_expires_at' => now()->addDays(7),
                ]);
            }

            // Kirim email aktivasi
            try {
                $this->sendActivationEmail($license->user, $license, $activationToken);
            } catch (\Exception $mailError) {
                Log::error('Email gagal dikirim: ' . $mailError->getMessage());
            }

            // Notifikasi untuk User (Admin Sekolah)
            Notification::create([
                'user_id' => $license->user_id,
                'type' => 'license_approved',
                'title' => '✅ Akun Disetujui!',
                'message' => "Selamat! Pendaftaran Anda telah disetujui. Silakan cek email untuk mengaktifkan akun.",
                'data' => json_encode(['license_id' => $license->id]),
                'is_read' => false,
            ]);

            // Notifikasi untuk Super Admin
            Notification::create([
                'user_id' => Auth::id(),
                'type' => 'approval_completed',
                'title' => '✅ Approval Berhasil',
                'message' => "Anda telah menyetujui akun {$license->user->name} ({$license->user->email}). Email aktivasi telah dikirim.",
                'data' => json_encode(['license_id' => $license->id]),
                'is_read' => false,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Akun berhasil disetujui! Email aktivasi telah dikirim ke user.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Approval Error: ' .  $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Kirim email aktivasi
     */
    private function sendActivationEmail($user, $license, $token)
    {
        // Gunakan class Mailable yang sudah Anda buat
        try {
            \Illuminate\Support\Facades\Mail::to($user->email)
                ->send(new \App\Mail\AccountActivationMail($user, $license));
                
            Log::info('Email aktivasi dikirim ke: ' . $user->email);
        } catch (\Exception $e) {
            Log::error('Gagal kirim via Mailable: ' . $e->getMessage());
        }
    }

    /**
     * Reject user and license
     */
    public function reject(Request $request, $licenseId)
    {
        $request->validate([
            'reason' => ['required', 'string', 'min:5', 'max:500'],
        ]);

        try {
            DB::beginTransaction();

            $license = License::with('user')->findOrFail($licenseId);

            if ($license->approval_status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Lisensi sudah diproses sebelumnya.'
                ], 400);
            }

            // Update license
            $license->update([
                'approval_status' => 'rejected',
                'is_active' => false,
                'rejection_reason' => $request->reason,
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            // Update user status
            if ($license->user) {
                $license->user->update(['status' => 'rejected']);
            }

            // Notifikasi untuk User
            Notification::create([
                'user_id' => $license->user_id,
                'type' => 'license_rejected',
                'title' => '❌ Pendaftaran Ditolak',
                'message' => "Maaf, pendaftaran Anda ditolak. Alasan: {$request->reason}",
                'data' => json_encode(['license_id' => $license->id, 'reason' => $request->reason]),
                'is_read' => false,
            ]);

            // Notifikasi untuk Super Admin
            Notification::create([
                'user_id' => Auth::id(),
                'type' => 'approval_rejected',
                'title' => '❌ Approval Ditolak',
                'message' => "Anda telah menolak pendaftaran {$license->user->name}.",
                'data' => json_encode(['license_id' => $license->id]),
                'is_read' => false,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil ditolak.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Rejection Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak: ' . $e->getMessage()
            ], 500);
        }
    }
}