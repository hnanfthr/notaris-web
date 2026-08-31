<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 1. Akun Pak Imam (IAM: Read-Only / Viewer)
        User::updateOrCreate(
            ['email' => 'imam@notaris.id'],
            [
                'name' => 'Imam Safari',
                // Ambil password dari .env, jika tidak ada (lokal) pakai 'pakimam123'
                'password' => Hash::make(env('SEED_IMAM_PASSWORD', 'pakimam123')),
                'role' => 'viewer', // IAM Read-Only permission
            ]
        );

        // 2. Akun Super Admin (IAM: Read, Write, Edit, Delete)
        User::updateOrCreate(
            ['email' => 'superadmin@notaris.id'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make(env('SEED_ADMIN_PASSWORD', 'admin123')),
                'role' => 'superadmin', // IAM Full Access permission
            ]
        );

        // 3. Akun Staff Arsip (IAM: Read, Write, Edit, Delete)
        User::updateOrCreate(
            ['email' => 'staff@notaris.id'],
            [
                'name' => 'Staff Arsip',
                'password' => Hash::make(env('SEED_STAFF_PASSWORD', 'staff123')),
                'role' => 'superadmin', // IAM Full Access permission
            ]
        );
    }
}