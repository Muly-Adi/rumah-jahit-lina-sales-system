<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produk extends Model
{
    protected $primaryKey = 'id_produk';
    protected $table = 'produk';

    protected $fillable = [
        'kategori_id',
        'nama',
        'harga_modal',
        'harga',
        'keterangan',
        'jumlah_produk',
        'min_beli',
    ];

    public function kategori() : BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'id_kategori');
    }

    public function riwayatStokProduk() : HasMany
    {
        return $this->hasMany(RiwayatStokProduk::class, 'produk_id', 'id_produk');
    }

    public function jenisProduk() : HasMany
    {
        return $this->hasMany(JenisProduk::class, 'produk_id', 'id_produk');
    }

    public function gambarProduk() : HasMany
    {
        return $this->hasMany(GambarProduk::class, 'produk_id', 'id_produk');
    }

    public function invoice() : HasMany
    {
        return $this->hasMany(Invoice::class, 'produk_id', 'id_produk');
    }

    public function itemTransaksi() : HasMany
    {
        return $this->hasMany(ItemTransaksi::class, 'produk_id', 'id_produk');
    }

    // Method untuk mendapatkan total jumlah terjual
    public function getTotalTerjualAttribute()
    {
        return $this->itemTransaksi()
            ->whereHas('invoice', function($query) {
                $query->where('status_pembayaran', 'terima');
            })
            ->sum('jumlah');
    }

    public function ulasanRating() : HasMany
    {
        return $this->hasMany(UlasanRating::class, 'produk_id', 'id_produk');
    }
}
