<?php

namespace App\View\Components\category;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SearchFilter extends Component
{
    public $oldestYear;
    public $search;
    public function __construct($oldestYear, $search)
    {
        $this->oldestYear = $oldestYear;
        $this->search = $search;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.category.search-filter');
    }
}
