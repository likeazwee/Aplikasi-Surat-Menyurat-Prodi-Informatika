<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat Akun Admin
        User::create([
            'name' => 'Admin Prodi Informatika',
            'email' => 'adminprodi@gmail.com',
            'password' => Hash::make('adminprodi123'),
            'role' => 'admin', // Pastikan kolom 'role' sesuai dengan database Anda
        ]);

        // Membuat Akun Kaprodi
        User::create([
            'name' => 'Kaprodi Informatika',
            'email' => 'kaprodi@gmail.com',
            'password' => Hash::make('kaprodi123'),
            'role' => 'kaprodi',
        ]);
    }
}
