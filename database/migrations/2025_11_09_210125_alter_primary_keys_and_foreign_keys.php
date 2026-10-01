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
        Schema::disableForeignKeyConstraints();

        // Rename primary keys
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('id', 'id_user');
        });
        Schema::table('kategori', function (Blueprint $table) {
            $table->renameColumn('id', 'id_kategori');
        });
        Schema::table('produk', function (Blueprint $table) {
            $table->renameColumn('id', 'id_produk');
        });
        Schema::table('jenis_produk', function (Blueprint $table) {
            $table->renameColumn('id', 'id_jenis_produk');
        });
        Schema::table('gambar_produk', function (Blueprint $table) {
            $table->renameColumn('id', 'id_gambar_produk');
        });
        Schema::table('riwayat_stok_produk', function (Blueprint $table) {
            $table->renameColumn('id', 'id_riwayat_stok_produk');
        });
        Schema::table('invoice', function (Blueprint $table) {
            $table->renameColumn('id', 'id_invoice');
        });
        Schema::table('item_transaksi', function (Blueprint $table) {
            $table->renameColumn('id', 'id_item_transaksi');
        });

        // Update foreign keys
        Schema::table('produk', function (Blueprint $table) {
            $table->dropForeign(['kategori_id']);
            $table->foreign('kategori_id')->references('id_kategori')->on('kategori')->onDelete('cascade');
        });

        Schema::table('jenis_produk', function (Blueprint $table) {
            $table->dropForeign(['produk_id']);
            $table->foreign('produk_id')->references('id_produk')->on('produk')->onDelete('cascade');
        });

        Schema::table('gambar_produk', function (Blueprint $table) {
            $table->dropForeign(['produk_id']);
            $table->foreign('produk_id')->references('id_produk')->on('produk')->onDelete('cascade');
        });

        Schema::table('riwayat_stok_produk', function (Blueprint $table) {
            if (Schema::hasColumn('riwayat_stok_produk', 'produk_id')) {
                $table->dropForeign(['produk_id']);
                $table->foreign('produk_id')->references('id_produk')->on('produk')->onDelete('cascade');
            }
            if (Schema::hasColumn('riwayat_stok_produk', 'jenis_produk_id')) {
                $table->dropForeign(['jenis_produk_id']);
                $table->foreign('jenis_produk_id')->references('id_jenis_produk')->on('jenis_produk')->onDelete('cascade');
            }
        });

        Schema::table('invoice', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->foreign('customer_id')->references('id_user')->on('users')->onDelete('cascade');
        });

        Schema::table('item_transaksi', function (Blueprint $table) {
            $table->dropForeign(['invoice_id']);
            if (Schema::hasColumn('item_transaksi', 'produk_id')) {
                $table->dropForeign(['produk_id']);
                $table->foreign('produk_id')->references('id_produk')->on('produk')->onDelete('cascade');
            }
            if (Schema::hasColumn('item_transaksi', 'jenis_produk_id')) {
                $table->dropForeign(['jenis_produk_id']);
                $table->foreign('jenis_produk_id')->references('id_jenis_produk')->on('jenis_produk')->onDelete('cascade');
            }
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        // Drop new foreign keys and re-create old ones
        Schema::table('produk', function (Blueprint $table) {
            $table->dropForeign(['kategori_id']);
            $table->foreign('kategori_id')->references('id')->on('kategori')->onDelete('cascade');
        });
        Schema::table('jenis_produk', function (Blueprint $table) {
            $table->dropForeign(['produk_id']);
            $table->foreign('produk_id')->references('id')->on('produk')->onDelete('cascade');
        });
        Schema::table('gambar_produk', function (Blueprint $table) {
            $table->dropForeign(['produk_id']);
            $table->foreign('produk_id')->references('id')->on('produk')->onDelete('cascade');
        });
        Schema::table('riwayat_stok_produk', function (Blueprint $table) {
            if (Schema::hasColumn('riwayat_stok_produk', 'produk_id')) {
                $table->dropForeign(['produk_id']);
                $table->foreign('produk_id')->references('id')->on('produk')->onDelete('cascade');
            }
            if (Schema::hasColumn('riwayat_stok_produk', 'jenis_produk_id')) {
                $table->dropForeign(['jenis_produk_id']);
                $table->foreign('jenis_produk_id')->references('id')->on('jenis_produk')->onDelete('cascade');
            }
        });
        Schema::table('invoice', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::table('item_transaksi', function (Blueprint $table) {
            $table->dropForeign(['invoice_id']);
            if (Schema::hasColumn('item_transaksi', 'produk_id')) {
                $table->dropForeign(['produk_id']);
                $table->foreign('produk_id')->references('id')->on('produk')->onDelete('cascade');
            }
            if (Schema::hasColumn('item_transaksi', 'jenis_produk_id')) {
                $table->dropForeign(['jenis_produk_id']);
                $table->foreign('jenis_produk_id')->references('id')->on('jenis_produk')->onDelete('cascade');
            }
        });

        // Rename columns back to 'id'
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('id_user', 'id');
        });
        Schema::table('kategori', function (Blueprint $table) {
            $table->renameColumn('id_kategori', 'id');
        });
        Schema::table('produk', function (Blueprint $table) {
            $table->renameColumn('id_produk', 'id');
        });
        Schema::table('jenis_produk', function (Blueprint $table) {
            $table->renameColumn('id_jenis_produk', 'id');
        });
        Schema::table('gambar_produk', function (Blueprint $table) {
            $table->renameColumn('id_gambar_produk', 'id');
        });
        Schema::table('riwayat_stok_produk', function (Blueprint $table) {
            $table->renameColumn('id_riwayat_stok_produk', 'id');
        });
        Schema::table('invoice', function (Blueprint $table) {
            $table->renameColumn('id_invoice', 'id');
        });
        Schema::table('item_transaksi', function (Blueprint $table) {
            $table->renameColumn('id_item_transaksi', 'id');
        });

        Schema::enableForeignKeyConstraints();
    }
};