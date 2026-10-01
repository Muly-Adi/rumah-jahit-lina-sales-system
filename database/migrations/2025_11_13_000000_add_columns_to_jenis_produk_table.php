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
        Schema::table('jenis_produk', function (Blueprint $table) {
            $table->string('warna', 20)->nullable()->after('nama');
            $table->string('ukuran', 20)->nullable()->after('warna');
            $table->integer('harga')->after('ukuran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_produk', function (Blueprint $table) {
            $table->dropColumn(['warna', 'ukuran', 'harga']);
        });
    }
};
