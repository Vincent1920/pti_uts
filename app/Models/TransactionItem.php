<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    use HasFactory;
    
   protected $table = 'transaction_items'; 

    protected $fillable = [
        'transaction_id', 'barang_id','diskon', 'product_name', 'quantity', 'price', 'subtotal'
    ];
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
   

public function transaction()
{
    return $this->belongsTo(Transaction::class, 'transaction_id');
}
}