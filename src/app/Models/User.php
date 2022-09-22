<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;




class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'document',
        'email',
        'password',
        'type',
        'balance'
    ];

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }
}
