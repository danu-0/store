<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran'; // Nama tabel di database
    protected $fillable = [
        'user_id',
        'total_dibayar',
        'tanggal_pembayaran',
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

}
