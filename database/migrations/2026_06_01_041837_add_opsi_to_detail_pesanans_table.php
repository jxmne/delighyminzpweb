<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('detail_pesanans', function (Blueprint $table) {
            $table->enum('opsi', ['Take Away', 'Dine In'])->default('Take Away')->after('subtotal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_pesanans', function (Blueprint $table) {
            $table->dropColumn('opsi');
        });
         Schema::dropIfExists('detail_pesanans');
    }
};
