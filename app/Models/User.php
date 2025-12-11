<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\VerifyEmailUser;
class User extends Authenticatable implements MustVerifyEmail // Tambahkan 'implements MustVerifyEmail'
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'name', 'email', 'password', 'is_admin','username','email_verified_at',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    public function sendEmailVerificationNotification()
    {
       
        $this->notify(new VerifyEmailUser($this));
    }

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function barangs()
    {
        return $this->hasMany(Barang::class);
    }
    public function cartItems()
{
    return $this->hasMany(CartItem::class);
}


}
