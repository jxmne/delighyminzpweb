<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            ['nama_menu' => 'Nasi Kuning', 'kategori' => 'Menu Harian', 'harga' => 12000, 'stok_harian' => 100],
            ['nama_menu' => 'Nasi Ayam Goreng', 'kategori' => 'Menu Harian', 'harga' => 19000, 'stok_harian' => 100],
            ['nama_menu' => 'Nasi Merah Tumis Brokoli', 'kategori' => 'Menu Diet', 'harga' => 19000, 'stok_harian' => 100],
            ['nama_menu' => 'Nasi Jagung', 'kategori' => 'Menu Diet', 'harga' => 15000, 'stok_harian' => 100],
            ['nama_menu' => 'Nasi Uduk Ayam', 'kategori' => 'Menu Harian', 'harga' => 19000, 'stok_harian' => 100],
            ['nama_menu' => 'Nasi Campur', 'kategori' => 'Menu Harian', 'harga' => 13000, 'stok_harian' => 100],
        ];  

        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}