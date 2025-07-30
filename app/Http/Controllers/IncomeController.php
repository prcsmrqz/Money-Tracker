<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;

class IncomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        //icons
        $categories = $user->categories()->where('type', 'income')->orderBy('name', 'ASC')->get();

        foreach ($categories as $category) {
            $category->totalIncome = $user->transactions()
                ->where('type', 'income')
                ->where('category_id', $category->id)
                ->sum('amount');
        }
        $totalIncome = $user->transactions()->where('type', 'income')->whereMonth('date', now()->month)->whereYear('date', now()->year)->sum('amount');

        //chart
        $oldestDate = $user->transactions()
            ->where('type', 'income')
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


        return view('income.index', compact('categories', 'totalIncome', 'activeTab', 'oldestYear'));
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
                $transactions->whereDate('date', '>=', Carbon::now()->subDays(7));
            } elseif ($request->date_filter === 'last_30_days') {
                $transactions->whereDate('date', '>=', Carbon::now()->subDays(30));
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