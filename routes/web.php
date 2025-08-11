<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SavingsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    //currency update
    Route::patch('/currency', [DashboardController::class, 'updateCurrency'])->name('currency.update');

    //resource controllers
    Route::resource('income', IncomeController::class);
    Route::resource('category', CategoryController::class);
    Route::resource('transaction', TransactionController::class);
    Route::resource('savings', SavingsController::class);
    Route::resource('expenses', ExpensesController::class);

    //chart
    Route::get('/income-chart', [IncomeController::class, 'incomeChart']);
    Route::get('/savings-chart', [SavingsController::class, 'savingsChart']);
    Route::get('/expenses-chart', [ExpensesController::class, 'expensesChart']);


});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
