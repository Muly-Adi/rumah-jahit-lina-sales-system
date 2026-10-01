<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatStokProduk extends Model
{
    protected $primaryKey = 'id_riwayat_stok_produk';
    protected $table = 'riwayat_stok_produk';

    protected $fillable = [
        'produk_id',
        'jenis_produk_id',
        'tanggal',
        'stok_awal',
        'stok_masuk',
        'stok_keluar',
        'stok_akhir',
    ];

    public function produk() : BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'id_produk');
    }

    public function jenisProduk() : BelongsTo
    {
        return $this->belongsTo(JenisProduk::class, 'jenis_produk_id', 'id_jenis_produk');
    }
}
