<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class ExpensesController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        //icons
        $categories = $user->categories()->where('type', 'expenses')->orderBy('name', 'ASC')->paginate(15);

        foreach ($categories as $category) {
            $category->totalIncome = $user->transactions()
                ->where('type', 'expenses')
                ->where('category_id', $category->id)
                ->sum('amount');
        }
        $totalIncome = $user->transactions()->where('type', 'expenses')->whereMonth('date', now()->month)->whereYear('date', now()->year)->sum('amount');

        //chart
        $oldestDate = $user->transactions()
            ->where('type', 'expenses')
            ->orderBy('date', 'asc')
            ->value('date');
        $oldestYear = $oldestDate ? Carbon::parse($oldestDate)->year : now()->year;

        $activeTab = '';
        $dateFilter = request('date_filter');

        $monthFilter = request('month_filter');
        $yearFilter = request('year_filter');

        $startFilter = request('start');
        $endFilter = request('end');

        if ($dateFilter || ($monthFilter && $yearFilter) || ($startFilter && $endFilter)) {
                $activeTab = 'chart';
        }

        

        return view('expenses.index', compact('categories', 'totalIncome', 'activeTab', 'oldestYear'));
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
