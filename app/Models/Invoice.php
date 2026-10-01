<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $primaryKey = 'id_invoice';
    protected $table = 'invoice';

    protected $fillable = [
        'kode_invoice',
        'tanggal',
        'customer_id',
        'nama',
        'no_hp',
        'alamat',
        'total_bayar',
        'ongkir',
        'kurir',
        'layanan_pengiriman',
        'status_pembayaran',
        'status_pengiriman',
        'resi',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function customer() : BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id', 'id_user');
    }

    public function itemTransaksi()
    {
        return $this->hasMany(ItemTransaksi::class, 'invoice_id', 'id_invoice');
    }
}
