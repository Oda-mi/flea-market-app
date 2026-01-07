<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image',
        'postal_code',
        'address',
        'building',
    ];


    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function favoriteItems()
    {
        return $this->belongsToMany(Item::class, 'favorites');
    }

    public function buyingTransactions()
    {
        return $this->hasMany(Transaction::class, 'buyer_id');
    }

    public function sellingTransactions()
    {
        return $this->hasMany(Transaction::class, 'seller_id');
    }

    public function transactionMessages()
    {
        return $this->hasMany(TransactionMessage::class);
    }

    // 自分が「した」評価
    public function evaluationsGiven()
    {
        return $this->hasMany(Evaluation::class, 'evaluator_id');
    }

    // 自分が「された」評価
    public function evaluationsReceived()
    {
        return $this->hasMany(Evaluation::class, 'evaluatee_id');
    }




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
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
