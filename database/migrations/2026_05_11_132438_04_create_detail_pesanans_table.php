<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pesanans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pesanan_id')->constrained('pesanans')->onDelete('cascade');
        $table->foreignId('menu_id')->constrained('menus')->onDelete('cascade');
        $table->integer('kuantitas');
        $table->integer('harga_satuan');
        $table->integer('subtotal');
        $table->enum('opsi', ['Take Away', 'Dine In'])->default('Take Away');
        $table->timestamps();
    });

    }

    public function down(): void
    {
        Schema::table('detail_pesanans', function (Blueprint $table) {
            $table->dropColumn('opsi');
        });
    }
};