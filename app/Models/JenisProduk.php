<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JenisProduk extends Model
{
    protected $primaryKey = 'id_jenis_produk';
    protected $table = 'jenis_produk';

    protected $fillable = [
        'produk_id',
        'nama',
        'warna',
        'ukuran',
        'harga',
        'path_gambar',
        'jumlah_produk',
    ];

    public function produk() : BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'id_produk');
    }
}
