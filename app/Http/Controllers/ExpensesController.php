<?php

namespace App\Http\Controllers;

use App\Traits\ActiveTab;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ExpensesController extends Controller
{
    use ActiveTab;
    public function index()
    {
        $user = auth()->user();
        //icons
        $categoriesQuery = $user->categories()
            ->where('type', 'expenses')
            ->withSum(['transactions as total' => function ($query) {
                $query->where('type', 'expenses');
            }], 'amount');

        $top5Expenses = (clone $categoriesQuery)
                        ->having('total', '!=', 0) 
                        ->orderByDesc('total') 
                        ->limit(5)             
                        ->get();

        $categories = $categoriesQuery->orderBy('name', 'ASC')->paginate(15);

        $recentTransactions = $user->transactions()->where('type', 'expenses')->orderBy('date', 'desc')->take(5)->with('category')->get();
        $monthlySpent = $user->transactions()->where('type', 'expenses')->whereMonth('date', now()->month)->whereYear('date', now()->year)->sum('amount');
        $totalSpent = $user->transactions()->where('type', 'expenses')->sum('amount');

        $oldestDate = $user->transactions()
            ->where('type', 'expenses')
            ->orderBy('date', 'asc')
            ->value('date');
        $oldestYear = $oldestDate ? Carbon::parse($oldestDate)->year : now()->year;

        $activeTab = $this->getActiveTab();
        
        return view('expenses.index', compact('categories', 'monthlySpent', 'totalSpent', 'activeTab', 'oldestYear', 'top5Expenses', 'recentTransactions'));
    }

    public function expensesChart(Request $request)
    {
        $user = auth()->user();
        $categories = $user->categories()->where('type', 'expenses')->orderBy('name')->get();

        foreach ($categories as $category) {
            $transactions = $user->transactions()
                ->where('type', 'expenses')
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

            $category->totalExpenses = $transactions->sum('amount');
        }

        return response()->json($categories);
    }
}
