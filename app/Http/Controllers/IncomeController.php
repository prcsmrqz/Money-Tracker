<?php

namespace App\Http\Controllers;

use App\Traits\ActiveTab;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;

class IncomeController extends Controller
{
    use ActiveTab;

    public function index()
    {
        $user = auth()->user();

        $categoriesQuery = $user->categories()
            ->where('type', 'income')
            ->withSum(['transactions as income_total' => function ($query) {
                $query->where('type', 'income');
            }], 'amount')
            ->withSum(['expenseTransactionsFromIncome as expenses_total' => function ($query) {
                $query->where('type', 'expenses');
                $query->whereColumn('source_income', 'categories.id'); 
            }], 'amount')
            ->withSum(['transactions as savings_total' => function ($query) {
                $query->where('type', 'savings');
                $query->whereColumn('category_id', 'categories.id'); 
            }], 'amount');

        $top5Income = (clone $categoriesQuery)
                        ->having('income_total', '!=', 0) 
                        ->orderByDesc('income_total') 
                        ->limit(5)             
                        ->get();
        
        $categories =  $categoriesQuery->orderBy('name', 'ASC')->paginate(15);

        foreach ($categories as $category) {
            $category->total = ($category->income_total ?? 0) - ($category->expenses_total ?? 0) - ($category->savings_total ?? 0);
        }

        $recentTransactions = $user->transactions()->where('type', 'income')->orderBy('date', 'desc')->take(5)->with('category')->get();
        $monthlyIncome = $user->transactions()->where('type', 'income')->whereMonth('date', now()->month)->whereYear('date', now()->year)->sum('amount');
        $totalIncome = $user->transactions()->where('type', 'income')->sum('amount');

        $oldestDate = $user->transactions()
            ->where('type', 'income')
            ->orderBy('date', 'asc')
            ->value('date');
        $oldestYear = $oldestDate ? Carbon::parse($oldestDate)->year : now()->year;

        $activeTab = $this->getActiveTab();

        return view('income.index', compact('categories', 'totalIncome', 'activeTab', 'oldestYear', 'top5Income', 'recentTransactions', 'monthlyIncome'));
    }

    public function incomeChart(Request $request)
    {
        $user = auth()->user();
        $categories = $user->categories()->where('type', 'income')->orderBy('name')->get();

        foreach ($categories as $category) {
            $transactions = $user->transactions()
                ->where('type', 'income')
                ->where('category_id', $category->id);

            if ($request->date_filter === 'today') {
                $transactions->whereDate('date', Carbon::today());
            } elseif ($request->date_filter === 'last_7_days') {
                $transactions->whereBetween('date', [
                        Carbon::now()->subDays(6)->startOfDay(),
                        Carbon::now()->endOfDay(),
                    ]);
            } elseif ($request->date_filter === 'last_30_days') {
                $transactions->whereBetween('date', [
                        Carbon::now()->subDays(30)->startOfDay(),
                        Carbon::now()->endOfDay(),
                    ]);
            }

            if ($request->month_filter && $request->year_filter) {
                $transactions->whereMonth('date', $request->month_filter)
                            ->whereYear('date', $request->year_filter);
            }

            if ($request->start && $request->end) {
                $transactions->whereBetween('date', [
                    Carbon::parse($request->start)->startOfDay(),
                    Carbon::parse($request->end)->endOfDay()
                ]);
            }

            $category->totalIncome = $transactions->sum('amount');
        }

        return response()->json($categories);
    }


}