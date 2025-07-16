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
        $currencyList = json_decode(file_get_contents(storage_path('app/full_currency_list.json')), true);

        View::composer('*', function ($view) use ($currencyList) {
            $view->with('currencyList', $currencyList);
        });
    }
}
