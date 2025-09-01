<?php

namespace App\View\Components\category;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;

class SearchFilter extends Component
{
    public $oldestYear;
    public $search;
    public $mode;
    public function __construct($oldestYear = 2025, $search, $mode)
    {
        $this->oldestYear = $oldestYear;
        $this->search = $search;
        $this->mode = $mode;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        error_log("SearchFilter oldestYear: " . $this->oldestYear);
        return view('components.category.search-filter');
    }
}
