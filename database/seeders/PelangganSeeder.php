<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pelanggan;

class PelangganSeeder extends Seeder
{
    public function run(): void
    {
        $pelanggans = [
            ['nama' => 'Ibu Sarah', 'email' => 'sarah@example.com', 'telepon' => '081234567891', 'alamat' => 'Jl. Mawar No. 5', 'status' => 'aktif'],
            ['nama' => 'Bu RT 05', 'email' => 'rt05@example.com', 'telepon' => '081234567892', 'alamat' => 'Perumahan Mutiara', 'status' => 'aktif'],
        ];

        foreach ($pelanggans as $pelanggan) {
            Pelanggan::create($pelanggan);
        }
    }
}