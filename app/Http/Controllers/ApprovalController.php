<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\License;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ApprovalController extends Controller
{
    public function index()
    {
        $pendingLicenses = License::where('approval_status', 'pending')
            ->where('payment_status', 'settlement')
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();
            
        $approvedLicenses = License::where('approval_status', 'approved')
            ->with('user')
            ->orderBy('approved_at', 'desc')
            ->limit(10)
            ->get();
            
        $rejectedLicenses = License::where('approval_status', 'rejected')
            ->with('user')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();
            
        return view('super_admin.approvals.index', compact('pendingLicenses', 'approvedLicenses', 'rejectedLicenses'));
    }
    
    public function approve($licenseId)
    {
        try {
            $license = License::with('user')->findOrFail($licenseId);
            
            if ($license->approval_status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Lisensi sudah diproses sebelumnya.'
                ], 400);
            }
            
            $license->update([
                'approval_status' => 'approved',
                'is_active' => true,
                'approved_at' => now(),
                'approved_by' => auth()->id(),
            ]);
            
            // Kirim email aktivasi ke Admin Sekolah
            $this->sendActivationEmail($license->user, $license);
            
            Log::info('License approved: ' . $license->kode_lisensi . ' by ' . auth()->user()->email);
            
            return response()->json([
                'success' => true,
                'message' => 'Akun berhasil diaktivasi. Email aktivasi telah dikirim ke admin sekolah.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Approval Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengaktivasi akun: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function reject($licenseId, Request $request)
    {
        $request->validate([
            'reason' => ['required', 'string', 'max:500']
        ]);
        
        try {
            $license = License::with('user')->findOrFail($licenseId);
            
            if ($license->approval_status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Lisensi sudah diproses sebelumnya.'
                ], 400);
            }
            
            $license->update([
                'approval_status' => 'rejected',
                'is_active' => false,
            ]);
            
            // Update user status menjadi rejected
            $license->user->update([
                'status' => 'rejected'
            ]);
            
            // Kirim email penolakan ke Admin Sekolah
            $this->sendRejectionEmail($license->user, $license, $request->reason);
            
            Log::info('License rejected: ' . $license->kode_lisensi . ' by ' . auth()->user()->email);
            
            return response()->json([
                'success' => true,
                'message' => 'Pengajuan ditolak. Email notifikasi telah dikirim.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Rejection Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak pengajuan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    private function sendActivationEmail($user, $license)
    {
        try {
            $activationLink = route('activate.account', $user->activation_token);
            
            Mail::send('emails.account_activation', [
                'user' => $user,
                'license' => $license,
                'activationLink' => $activationLink,
                'email' => $user->email,
                'password' => 'Password yang Anda daftarkan saat registrasi', // Tidak kirim password asli demi keamanan
            ], function ($message) use ($user) {
                $message->to($user->email, $user->name)
                        ->subject('Akun Anda Telah Disetujui - Aktivasi Akun');
            });
            
            Log::info('Activation email sent to: ' . $user->email);
        } catch (\Exception $e) {
            Log::error('Failed to send activation email: ' . $e->getMessage());
        }
    }
    
    private function sendRejectionEmail($user, $license, $reason)
    {
        try {
            Mail::send('emails.account_rejected', [
                'user' => $user,
                'license' => $license,
                'reason' => $reason,
            ], function ($message) use ($user) {
                $message->to($user->email, $user->name)
                        ->subject('Pengajuan Aktivasi Akun Ditolak');
            });
            
            Log::info('Rejection email sent to: ' . $user->email);
        } catch (\Exception $e) {
            Log::error('Failed to send rejection email: ' . $e->getMessage());
        }
    }
}