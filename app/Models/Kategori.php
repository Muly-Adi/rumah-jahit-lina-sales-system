<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    protected $primaryKey = 'id_kategori';
    protected $table = 'kategori';

    protected $fillable = [
        'nama',
    ];

    public function produk() : HasMany
    {
        return $this->hasMany(Produk::class, 'kategori_id', 'id_kategori');
    }
}
