<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GambarProduk extends Model
{
    protected $primaryKey = 'id_gambar_produk';
    protected $table = 'gambar_produk';

    protected $fillable = [
        'produk_id',
        'path_gambar',
    ];

    public function produk() : BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'id_produk');
    }
}
