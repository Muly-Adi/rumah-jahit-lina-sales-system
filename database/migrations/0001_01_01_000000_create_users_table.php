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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 25);
            $table->string('username', 100)->nullable();
            $table->string('email', 50)->nullable()->unique();
            $table->string('no_hp', 15)->nullable();
            $table->string('password');
            $table->text('alamat')->nullable();
            $table->text('foto')->nullable();
            $table->enum('role', ['admin', 'karyawan', 'customer'])->default('customer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
