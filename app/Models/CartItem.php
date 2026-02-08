<?php

namespace App\Models;

use App\Models\Barang;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartItem extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'barang_id', 'quantity'];
    protected $table = 'cart_items';

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function getTotalPriceAttribute()
    {
        return $this->quantity * $this->barang->harga;
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
