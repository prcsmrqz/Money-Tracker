<?php

namespace App\View\Components\iconTab;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Icons extends Component
{
    public $categories;
    public $type;
    public function __construct($categories, $type)
    {
        $this->categories = $categories;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.icon-tab.icons');
    }
}
