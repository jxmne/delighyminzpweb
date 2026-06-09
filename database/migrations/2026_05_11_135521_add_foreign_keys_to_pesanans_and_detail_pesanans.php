<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah foreign key ke tabel pesanans
        Schema::table('pesanans', function (Blueprint $table) {
            $table->foreign('pelanggan_id')->references('id')->on('pelanggan')->onDelete('cascade');
        });

        // Tambah foreign key ke tabel detail_pesanans
        Schema::table('detail_pesanans', function (Blueprint $table) {
            $table->foreign('pesanan_id')->references('id')->on('pesanans')->onDelete('cascade');
            $table->foreign('menu_id')->references('id')->on('menux')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // Hapus foreign key dari tabel pesanans
        Schema::table('pesanans', function (Blueprint $table) {
            $table->dropForeign(['pelanggan_id']);
        });

        // Hapus foreign key dari tabel detail_pesanans
        Schema::table('detail_pesanans', function (Blueprint $table) {
            $table->dropForeign(['pesanan_id']);
            $table->dropForeign(['menu_id']);
        });
    }
};