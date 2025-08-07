<?php

namespace App\View\Components\savings;

use App\Models\SavingsAccount;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UpdateModal extends Component
{
    public $savingsAccount;
    public $action;
    public $title;
    public $type;
    public function __construct(SavingsAccount $savingsAccount, $title, $action, $type)
    {
        $this->savingsAccount = $savingsAccount;
        $this->title = $title;
        $this->action = $action;
        $this->type = $type;
    }
    public function render(): View|Closure|string
    {
        return view('components.savings.update-modal');
    }
}
