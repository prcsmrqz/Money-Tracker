<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    protected $fillable = [
        'user_id',
        'category_id',
        'savings_account_id',
        'amount',
        'source_type',
        'notes',
        'type'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function savingsAccount()
    {
        return $this->belongsTo(SavingsAccount::class);
    }

    public function sourceAccount()
    {
        return $this->belongsTo(SavingsAccount::class, 'source_type');
    }
}
