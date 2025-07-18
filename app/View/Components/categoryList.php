<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class categoryList extends Component
{
    public $categories;
    public $action;
    public $type;
    public function __construct($categories, $action, $type)
    {
        $this->categories = $categories;
        $this->action = $action;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.category-list');
    }
}
