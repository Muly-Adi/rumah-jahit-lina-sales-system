<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UlasanRating extends Model
{
    protected $primaryKey = 'id_ulasan_rating';
    protected $table = 'ulasan_rating';

    protected $fillable = [
        'user_id',
        'produk_id',
        'rating',
        'ulasan',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    public function produk() : BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'id_produk');
    }
}
