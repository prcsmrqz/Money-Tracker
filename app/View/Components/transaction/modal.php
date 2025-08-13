<?php

namespace App\View\Components\transaction;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Modal extends Component
{
    public $transaction;
    public $savingsAccounts;
    public $categories;
    public function __construct($transaction = null, $savingsAccounts = null, $categories = null)
{
    $this->transaction = $transaction;
    $this->savingsAccounts = $savingsAccounts;
    $this->categories = $categories;
}


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.transaction.modal');
    }
}
