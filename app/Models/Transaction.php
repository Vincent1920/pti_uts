<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    
    protected $fillable = [
    'user_id', 
    'invoice_code', 
    'subtotal', 
    'discount_amount', 
    'grand_total', 
    'snap_token', // <--- WAJIB ADA
    'status',
    'name', 
    'email', 
    'phone', 
    'address', 
    'city', 
    'postal_code', 
    'payment_method'
];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Detail Item
    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }
}