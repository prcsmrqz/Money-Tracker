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
    public function __construct( $search, $mode, $oldestYear = 2025)
    {
        $this->oldestYear = $oldestYear;
        $this->search = $search;
        $this->mode = $mode;
        Log::info('SearchFilter oldestYear: ' . json_encode($oldestYear));
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.category.search-filter');
    }
}
