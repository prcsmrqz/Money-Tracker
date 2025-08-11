<?php

namespace App\View\Components\transaction;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TransactionExpenses extends Component
{
    public $expensesCategories;
    public $savingsAccounts;
    public $incomeCategories;
    public function __construct($expensesCategories, $savingsAccounts, $incomeCategories)
    {
        $this->expensesCategories = $expensesCategories;
        $this->savingsAccounts = $savingsAccounts;
        $this->incomeCategories = $incomeCategories;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.transaction.transaction-expenses');
    }
}
