<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';

    protected $fillable = [
        'kategori_id',
        'nama_product',
        'harga',
        'stock',
    ];

    //relasi
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
}
