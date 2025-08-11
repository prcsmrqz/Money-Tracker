<?php

namespace App\View\Components\transaction;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Modal extends Component
{
    public $transaction;
    public $savingsAccounts;
    public function __construct($transaction = null, $savingsAccounts = null)
{
    $this->transaction = $transaction;
    $this->savingsAccounts = $savingsAccounts;
}


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.transaction.modal');
    }
}
