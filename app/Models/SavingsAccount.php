<?php

namespace App\Models;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;

class SavingsAccount extends Model
{

    protected $fillable = [
        'name',
        'user_id',
        'account_number',
        'type',
        'icon',
        'color'
    ];


    public function user() {
        return $this->belongsTo(User::class);
    }

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }
}
