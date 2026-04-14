<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\License;
use App\Models\Payment;
use App\Models\User;

class LicenseSeeder extends Seeder
{
    /*
     | Harga sesuai landing page & RegisterController:
     |   monthly  => Rp 49.000  / 1 bulan   => license_type: 'basic'
     |   yearly   => Rp 470.000 / 12 bulan  => license_type: 'premium'
     */

    private array $harga = [
        'monthly' => ['amount' => 49000,  'duration' => 1,  'type' => 'basic',   'label' => 'Lisensi 1 Bulan'],
        'yearly'  => ['amount' => 470000, 'duration' => 12, 'type' => 'premium', 'label' => 'Lisensi 1 Tahun'],
    ];

    public function run(): void
    {
        $superAdmin = User::where('role', 'super_admin')->first();

        // ── Setiap admin_sekolah: lisensi TAHUNAN, sudah approved & aktif ──
        $adminUsers = User::where('role', 'admin_sekolah')->get();

        foreach ($adminUsers as $index => $admin) {
            $plan      = 'yearly';
            $cfg       = $this->harga[$plan];
            $startDate = now()->subMonths(2);           // sudah berjalan 2 bulan
            $expDate   = $startDate->copy()->addMonths($cfg['duration']);
            $orderId   = 'LIC-' . strtoupper(Str::random(8)) . '-' . str_pad($index + 1, 2, '0', STR_PAD_LEFT);

            // 1. License
            $license = License::create([
                'user_id'          => $admin->id,
                'license_key'      => 'KEY-' . strtoupper(Str::random(16)),
                'kode_lisensi'     => $orderId,
                'license_type'     => $cfg['type'],         // 'premium'
                'duration_months'  => $cfg['duration'],     // 12
                'start_date'       => $startDate->toDateString(),
                'expired_date'     => $expDate->toDateString(),
                'is_active'        => true,
                'payment_status'   => 'settlement',
                'approval_status'  => 'approved',
                'rejection_reason' => null,
                'approved_by'      => $superAdmin?->id,
                'approved_at'      => $startDate->copy()->addDay(),
            ]);

            // 2. Payment
            Payment::create([
                'user_id'           => $admin->id,
                'license_id'        => $license->id,
                'order_id'          => $orderId,
                'transaction_id'    => 'TXN-' . strtoupper(Str::random(12)),
                'snap_token'        => Str::random(40),
                'amount'            => $cfg['amount'],       // 470000
                'payment_type'      => 'bank_transfer',
                'status'            => 'settlement',
                'midtrans_response' => [
                    'transaction_status' => 'settlement',
                    'payment_type'       => 'bank_transfer',
                    'bank'               => 'bca',
                    'gross_amount'       => (string) $cfg['amount'],
                    'order_id'           => $orderId,
                ],
                'paid_at' => $startDate->copy()->addHours(2),
            ]);

            $this->command->line(
                "  ✔  [{$cfg['label']}] {$admin->email} — Rp" .
                number_format($cfg['amount'], 0, ',', '.') .
                " — exp: {$expDate->toDateString()}"
            );
        }

        // ── Contoh lisensi BULANAN pending (untuk demo fitur approval) ──────
        $petugasUser = User::where('role', 'petugas')->first();
        if ($petugasUser) {
            $plan    = 'monthly';
            $cfg     = $this->harga[$plan];
            $orderId = 'LIC-DEMO-MONTHLY-01';

            $license = License::create([
                'user_id'          => $petugasUser->id,
                'license_key'      => 'KEY-' . strtoupper(Str::random(16)),
                'kode_lisensi'     => $orderId,
                'license_type'     => $cfg['type'],          // 'basic'
                'duration_months'  => $cfg['duration'],      // 1
                'start_date'       => now()->toDateString(),
                'expired_date'     => now()->addMonth()->toDateString(),
                'is_active'        => false,
                'payment_status'   => 'settlement',
                'approval_status'  => 'pending',             // belum di-approve super admin
                'rejection_reason' => null,
                'approved_by'      => null,
                'approved_at'      => null,
            ]);

            Payment::create([
                'user_id'           => $petugasUser->id,
                'license_id'        => $license->id,
                'order_id'          => $orderId,
                'transaction_id'    => 'TXN-' . strtoupper(Str::random(12)),
                'snap_token'        => Str::random(40),
                'amount'            => $cfg['amount'],       // 49000
                'payment_type'      => 'gopay',
                'status'            => 'settlement',
                'midtrans_response' => [
                    'transaction_status' => 'settlement',
                    'payment_type'       => 'gopay',
                    'gross_amount'       => (string) $cfg['amount'],
                    'order_id'           => $orderId,
                ],
                'paid_at' => now(),
            ]);

            $this->command->line(
                "  ⏳ [Monthly/Pending] {$petugasUser->email} — Rp" .
                number_format($cfg['amount'], 0, ',', '.') .
                " — menunggu approval"
            );
        }

        $this->command->info('✅ LicenseSeeder selesai (Payment sudah termasuk di sini).');
    }
}