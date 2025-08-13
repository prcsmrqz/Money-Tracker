<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class transactionTabButton extends Component
{
    public $categories;
    public $savingsAccounts;
    public $expensesCategories;
    public function __construct($categories, $savingsAccounts, $expensesCategories)
    {
        $this->categories = $categories;
        $this->savingsAccounts = $savingsAccounts;
        $this->expensesCategories = $expensesCategories;
    }

    public function render(): View|Closure|string
    {
        return view('components.transaction-tab-button');
    }
}
