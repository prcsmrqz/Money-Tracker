<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class categoryModal extends Component
{

        public $title;
        public $storeAction;
        public $updateAction;
        public $categories;
        public $type;
        public $show;
    public function __construct($title, $storeAction, $updateAction, $categories, $type, $show=false)
    {
        $this->title = $title;
        $this->storeAction = $storeAction;
        $this->updateAction = $updateAction;
        $this->categories = $categories;
        $this->type = $type;
        $this->show = $show;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.category-modal');
    }
}
