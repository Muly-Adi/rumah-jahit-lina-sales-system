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
        Schema::create('invoice', function (Blueprint $table) {
            $table->id();
            $table->string('kode_invoice')->unique();
            $table->date('tanggal');
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->string('nama', 25);
            $table->string('no_hp', 15);
            $table->text('alamat');
            $table->integer('total_bayar');
            $table->enum('status_pembayaran', ['pending', 'terima', 'tolak'])->default('pending');
            $table->boolean('status_pengiriman')->default(false);
            $table->string('resi')->nullable();
            $table->text('bukti_pembayaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice');
    }
};
