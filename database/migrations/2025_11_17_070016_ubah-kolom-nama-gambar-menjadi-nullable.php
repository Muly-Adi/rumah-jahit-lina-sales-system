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
            $table->string('nama', 50)->nullable()->change();
            $table->integer('harga')->nullable()->change();
            $table->string('path_gambar')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_produk', function (Blueprint $table) {
            $table->string('nama', 100)->nullable(false)->change();
            $table->integer('harga')->nullable(false)->change();
            $table->string('path_gambar')->nullable(false)->change();
        });
    }
};
