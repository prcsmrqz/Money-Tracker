<?php

namespace App\View\Components\category;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SearchFilter extends Component
{
    public $oldestYear;
    public function __construct($oldestYear)
    {
        $this->oldestYear = $oldestYear;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.category.search-filter');
    }
}
