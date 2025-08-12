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
        'source_savings',
        'source_income',
        'notes',
        'type',
        'date',
    ];
    protected $casts = [
        'date' => 'datetime',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function savingsAccounts()
    {
        return $this->belongsTo(SavingsAccount::class);
    }

    public function sourceIncomeCategory()
    {
        return $this->belongsTo(Category::class, 'source_income');
    }

    public function sourceSavingsAccount()
    {
        return $this->belongsTo(SavingsAccount::class, 'source_savings');
    }


    public function sourceAccount()
    {
        return $this->belongsTo(SavingsAccount::class, 'source_type');
    }
}
