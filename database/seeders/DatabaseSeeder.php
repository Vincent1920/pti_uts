<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kategori;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Disarankan pakai Hash facade

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed data untuk User (Owner dan User biasa)
        $users = [
            [
                'name' => 'admin',
                'username' => 'admin',
                'role' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin1234'), // Menggunakan Hash::make lebih standar
            ],
            [
                'name' => 'user',
                'username' => 'user',
                'role' => 'user',
                'email' => 'user@gmail.com',
                'password' => Hash::make('12345678'),
            ],
        ];

        // LOOPING YANG HILANG (Ini perbaikan utamanya):
        foreach ($users as $user) {
            // Menggunakan firstOrCreate agar tidak error jika dijalankan 2x (mencegah duplikat email)
            User::firstOrCreate(['email' => $user['email']], $user);
        }


        // 2. Seed data untuk Kategori
        $categories = [
            ['nama_kategori' => 'roti'],
            ['nama_kategori' => 'dessert'],
            ['nama_kategori' => 'chocolate'],
            ['nama_kategori' => 'beverage'],
        ];

        foreach ($categories as $category) {
            // Menggunakan firstOrCreate untuk mencegah duplikat kategori
            Kategori::firstOrCreate(['nama_kategori' => $category['nama_kategori']], $category);
        }
    }
}