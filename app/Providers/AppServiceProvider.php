<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
        View::composer('*', function ($view) {
            $currencyList = json_decode(file_get_contents(storage_path('app/full_currency_list.json')), true);
            
            $remainingIncome = 0;

            if (auth()->check()) {
                $user = auth()->user();

                $expenses = $user->transactions()
                    ->where('type', 'expenses')
                    ->whereNotNull('source_income')
                    ->sum('amount');

                $savings = $user->transactions()
                    ->where('type', 'savings')
                    ->sum('amount');

                $income = $user->transactions()
                    ->where('type', 'income')
                    ->sum('amount');

                $remainingIncome = $income - $expenses - $savings;
            }

            $view->with([
                'currencyList' => $currencyList,
                'remainingIncome' => $remainingIncome,
            ]);
        });
    }
}
