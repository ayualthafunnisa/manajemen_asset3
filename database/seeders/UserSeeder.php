<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@gmail.com'], // biar tidak double
            [
                'name' => 'Ayu Althafunnisa',
                'InstansiID' => null,
                'password' => Hash::make('SUPERADMIN'),
                'role' => 'super_admin',
                'phone' => '081234567890',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
    }
}
