<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;
    // use HasFactory;
    // protected $fillable = ['title', 'harga', 'deskripsi', 'img', 'kategori_id'];
    protected $fillable = ['title', 'img', 'deskripsi', 'harga', 'berat_barang','user_id','qr_code','kategori_id','jumlah_barang'];

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
 

// Barang.php
public function cartItems()
{
    return $this->hasMany(CartItem::class);
}
}
