<?php

namespace App\View\Components\income;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Table extends Component
{
    public $transactionsTable;
    public $categories;
    public $allSavingsAccounts;
    public $allCategories;
    public $oldestYear;

    public function __construct($transactionsTable = null, $categories = null, $allSavingsAccounts = null, $allCategories = null, $oldestYear = null)
    {
        $this->transactionsTable = $transactionsTable;
        $this->categories = $categories;
        $this->allSavingsAccounts = $allSavingsAccounts;
        $this->allCategories = $allCategories;
        $this->oldestYear = $oldestYear;
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.income.table');
    }
}
