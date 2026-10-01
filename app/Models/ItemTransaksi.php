<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemTransaksi extends Model
{
    protected $primaryKey = 'id_item_transaksi';
    protected $table = 'item_transaksi';

    protected $fillable = [
        'invoice_id',
        'produk_id',
        'jenis_produk_id',
        'jumlah',
        'subtotal',
        'nilai'
    ];

    public function invoice() : BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id_invoice');
    }

    public function produk() : BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'id_produk');
    }

    public function jenisProduk() : BelongsTo
    {
        return $this->belongsTo(JenisProduk::class, 'jenis_produk_id', 'id_jenis_produk');
    }
}
