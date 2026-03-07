<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ──── Super Admin ────────────────────────────────────────────────────
        User::create([
            'name'      => 'Super Admin',
            'email'     => 'superadmin@ecanteen.id',
            'password'  => Hash::make('password'),
            'role'      => 'super_admin',
            'is_active' => true,
        ]);

        // ──── Sellers (Admin) ────────────────────────────────────────────────
        $seller1 = User::create([
            'name'           => 'Bu Sari',
            'email'          => 'sari@ecanteen.id',
            'password'       => Hash::make('password'),
            'role'           => 'admin',
            'nama_toko'      => 'Kantin Bu Sari',
            'phone'          => '081234567890',
            'nama_bank'      => 'BCA',
            'nomor_rekening' => '1234567890',
            'is_active'      => true,
        ]);

        $seller2 = User::create([
            'name'           => 'Pak Budi',
            'email'          => 'budi@ecanteen.id',
            'password'       => Hash::make('password'),
            'role'           => 'admin',
            'nama_toko'      => 'Warung Pak Budi',
            'phone'          => '081234567891',
            'nama_bank'      => 'BRI',
            'nomor_rekening' => '0987654321',
            'is_active'      => true,
        ]);

        // ──── Users (Siswa/Guru) ─────────────────────────────────────────────
        User::create([
            'name'       => 'Ahmad Fauzi',
            'email'      => 'ahmad@ecanteen.id',
            'password'   => Hash::make('password'),
            'role'       => 'user',
            'identifier' => '24001',
            'kelas'      => 'RPL',
            'saldo'      => 75000,
            'is_active'  => true,
        ]);

        User::create([
            'name'       => 'Siti Nurhaliza',
            'email'      => 'siti@ecanteen.id',
            'password'   => Hash::make('password'),
            'role'       => 'user',
            'identifier' => '24002',
            'kelas'      => 'DKV',
            'saldo'      => 120000,
            'is_active'  => true,
        ]);

        User::create([
            'name'       => 'Bpk. Agus Santoso',
            'email'      => 'agus@ecanteen.id',
            'password'   => Hash::make('password'),
            'role'       => 'user',
            'identifier' => '10001',
            'kelas'      => 'AK',
            'saldo'      => 200000,
            'is_active'  => true,
        ]);

        // ──── Menus Bu Sari ──────────────────────────────────────────────────
        $menusSari = [
            ['nama' => 'Nasi Goreng Spesial', 'harga' => 15000, 'kategori' => 'makanan', 'deskripsi' => 'Nasi goreng dengan telur dan ayam, lalapan segar'],
            ['nama' => 'Nasi Uduk Komplit',   'harga' => 13000, 'kategori' => 'makanan', 'deskripsi' => 'Nasi uduk dengan lauk pauk lengkap'],
            ['nama' => 'Mie Goreng Ayam',     'harga' => 12000, 'kategori' => 'makanan', 'deskripsi' => 'Mie goreng dengan suwiran ayam dan sayuran'],
            ['nama' => 'Es Teh Manis',        'harga' => 5000,  'kategori' => 'minuman', 'deskripsi' => 'Teh manis dingin segar'],
            ['nama' => 'Es Jeruk',            'harga' => 7000,  'kategori' => 'minuman', 'deskripsi' => 'Jeruk peras segar dengan es'],
            ['nama' => 'Gorengan (4pcs)',     'harga' => 5000,  'kategori' => 'snack',   'deskripsi' => 'Bakwan, tahu, tempe, dan pisang goreng'],
        ];

        foreach ($menusSari as $menu) {
            Menu::create([...$menu, 'seller_id' => $seller1->id, 'status' => 'tersedia', 'stok' => 0]);
        }

        // ──── Menus Pak Budi ─────────────────────────────────────────────────
        $menusBudi = [
            ['nama' => 'Ayam Bakar',          'harga' => 18000, 'kategori' => 'makanan', 'deskripsi' => 'Ayam bakar bumbu kecap dengan nasi'],
            ['nama' => 'Soto Ayam',           'harga' => 12000, 'kategori' => 'makanan', 'deskripsi' => 'Soto ayam bening dengan nasi/lontong'],
            ['nama' => 'Indomie Goreng',      'harga' => 8000,  'kategori' => 'makanan', 'deskripsi' => 'Indomie goreng telur'],
            ['nama' => 'Jus Alpukat',         'harga' => 10000, 'kategori' => 'minuman', 'deskripsi' => 'Jus alpukat segar dengan susu'],
            ['nama' => 'Air Mineral',         'harga' => 3000,  'kategori' => 'minuman', 'deskripsi' => 'Aqua 600ml'],
            ['nama' => 'Keripik Singkong',    'harga' => 3000,  'kategori' => 'snack',   'deskripsi' => 'Kerupuk singkong renyah, pedas/original'],
        ];

        foreach ($menusBudi as $menu) {
            Menu::create([...$menu, 'seller_id' => $seller2->id, 'status' => 'tersedia', 'stok' => 0]);
        }

        $this->command->info('✅ Seeder berhasil!');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Super Admin', 'superadmin@ecanteen.id', 'password'],
                ['Admin/Seller', 'sari@ecanteen.id', 'password'],
                ['Admin/Seller', 'budi@ecanteen.id', 'password'],
                ['User (Siswa)', 'ahmad@ecanteen.id', 'password'],
                ['User (Siswa)', 'siti@ecanteen.id', 'password'],
                ['User (Guru)', 'agus@ecanteen.id', 'password'],
            ]
        );
    }
}
