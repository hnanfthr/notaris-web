<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 1. Akun Pak Imam (Super Admin)
        User::create([
            'name' => 'Imam Safari',
            'email' => 'imam@notaris.id',
            'password' => Hash::make('pakimam123'), // Password
            'role' => 'admin', // Kita tambah kolom role manual nanti atau abaikan jika tabel standar
        ]);

        // 2. Akun Staff Arsip
        User::create([
            'name' => 'Staff Arsip',
            'email' => 'staff@notaris.id',
            'password' => Hash::make('staff123'),
            'role' => 'staff',
        ]);
    }
}