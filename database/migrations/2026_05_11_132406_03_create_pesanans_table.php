<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        {
    Schema::create('pesanans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pelanggan_id')->constrained('users')->onDelete('cascade');
        $table->timestamp('tanggal_pesan');
        $table->integer('jumlah_pax')->default(1);
        $table->integer('total_harga');
        $table->enum('status', ['selesai', 'dibatalkan'])->default('selesai');
        $table->string('metode_bayar')->default('cash');
        $table->text('catatan')->nullable();
        $table->timestamps();
    });
    }
}

    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};