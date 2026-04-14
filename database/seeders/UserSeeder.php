<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Instansi;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $instansi1 = Instansi::where('KodeInstansi', 'SKL-001')->first();
        $instansi2 = Instansi::where('KodeInstansi', 'SKL-002')->first();
        $instansi3 = Instansi::where('KodeInstansi', 'SKL-003')->first();

        $users = [
            // ── Super Admin (tidak terikat instansi) ──────────────────────
            [
                'InstansiID' => null,
                'name'       => 'Super Administrator',
                'email'      => 'superadmin@gmail',
                'password'   => Hash::make('password'),
                'role'       => 'super_admin',
                'phone'      => '081200000001',
                'status'     => 'active',
            ],

            // ── Admin Sekolah ─────────────────────────────────────────────
            [
                'InstansiID' => $instansi1?->InstansiID,
                'name'       => 'Admin SMK INFORMATIKA UTAMA',
                'email'      => 'ayualthafunnisa74@gmail.com',
                'password'   => Hash::make('password'),
                'role'       => 'admin_sekolah',
                'phone'      => '081200000002',
                'status'     => 'active',
            ],
            [
                'InstansiID' => $instansi2?->InstansiID,
                'name'       => 'Admin SMKN 2 Medan',
                'email'      => 'hilwa.hidayati20@gmail.com',
                'password'   => Hash::make('password'),
                'role'       => 'admin_sekolah',
                'phone'      => '081200000003',
                'status'     => 'active',
            ],
            [
                'InstansiID' => $instansi3?->InstansiID,
                'name'       => 'Admin SMPN 3 Binjai',
                'email'      => 'azmiavila04@gmail.com',
                'password'   => Hash::make('password'),
                'role'       => 'admin_sekolah',
                'phone'      => '081200000004',
                'status'     => 'active',
            ],

            // ── Petugas ───────────────────────────────────────────────────
            [
                'InstansiID' => $instansi1?->InstansiID,
                'name'       => 'Petugas Inventaris SMAN 1',
                'email'      => 'petugas@sman1medan.sch.id',
                'password'   => Hash::make('password'),
                'role'       => 'petugas',
                'phone'      => '081200000005',
                'status'     => 'active',
            ],
            [
                'InstansiID' => $instansi2?->InstansiID,
                'name'       => 'Petugas Inventaris SMKN 2',
                'email'      => 'petugas@smkn2medan.sch.id',
                'password'   => Hash::make('password'),
                'role'       => 'petugas',
                'phone'      => '081200000006',
                'status'     => 'active',
            ],

            // ── Teknisi ───────────────────────────────────────────────────
            [
                'InstansiID' => $instansi1?->InstansiID,
                'name'       => 'Teknisi Wahyu Pradana',
                'email'      => 'teknisi@sman1medan.sch.id',
                'password'   => Hash::make('password'),
                'role'       => 'teknisi',
                'phone'      => '081200000007',
                'status'     => 'active',
            ],
            [
                'InstansiID' => $instansi2?->InstansiID,
                'name'       => 'Teknisi Eko Setiawan',
                'email'      => 'teknisi@smkn2medan.sch.id',
                'password'   => Hash::make('password'),
                'role'       => 'teknisi',
                'phone'      => '081200000008',
                'status'     => 'active',
            ],
        ];

        foreach ($users as $data) {
            User::create($data);
        }

        $this->command->info('✅ UserSeeder selesai: ' . count($users) . ' user dibuat.');
        $this->command->table(
            ['Email', 'Role', 'Password'],
            collect($users)->map(fn($u) => [$u['email'], $u['role'], 'password'])->toArray()
        );
    }
}