<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    protected function globalData(): array
    {
        $user = auth()->user();

        return [
            'allSavingsAccounts' => $user?->savingsAccount()->orderBy('name')->get() ?? collect(),
            'allCategories'      => $user?->categories()->get() ?? collect(),
        ];
    }
}
