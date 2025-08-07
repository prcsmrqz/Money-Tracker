<?php

namespace App\View\Components\savings;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SavingsModal extends Component
{
    public $action;
    public $title;
    public $type;
    public function __construct( $title, $action, $type)
    {
        $this->title = $title;
        $this->action = $action;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.savings.savings-modal');
    }
}
