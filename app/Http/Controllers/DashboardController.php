<?php

namespace App\Http\Controllers;

use App\Services\FilterService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index ()
    {
        $user = auth()->user();

        // base query for types
        $baseIncome = $user->transactions()->where('type', 'income');
        $baseExpenses = $user->transactions()->where('type', 'expenses');
        $baseSavings = $user->transactions()->where('type', 'savings');
        
        //calculate net income
        $incomeIncome = (clone $baseIncome)->sum('amount');
        $expensesIncome = (clone $baseExpenses)->whereNotNull('source_income')->sum('amount');
        $savingsIncome = (clone $baseSavings)->whereNotNull('category_id')->sum('amount');

        $netIncome = $incomeIncome - $expensesIncome - $savingsIncome;

        // total expenses
        $totalExpenses = $user->transactions()->where('type', 'expenses')->sum('amount');

        // calculate net savings
        $expensesSavings = (clone $baseExpenses)->whereNotNull('source_savings')->sum('amount');
        $savingsSavings = (clone $baseSavings)->sum('amount');
        $netSavings = $savingsSavings - $expensesSavings;

        //For income categories
        $incomeCategoriesQuery = $user->categories()
            ->where('type', 'income')
            ->withSum(['transactions as income_total' => function ($query) {
                $query->where('type', 'income');
            }], 'amount');

        // Top 3 income
        $top5Income = (clone $incomeCategoriesQuery)
            ->having('income_total', '!=', 0)
            ->orderByDesc('income_total')
            ->limit(3)
            ->get();

        // For expenses categories
        $expenseCategoriesQuery = $user->categories()
            ->where('type', 'expenses')
            ->withSum(['transactions as expenses_total' => function ($query) {
                $query->where('type', 'expenses');
            }], 'amount');

        // Top 3 expenses
        $top5Expenses = (clone $expenseCategoriesQuery)
            ->having('expenses_total', '!=', 0)
            ->orderByDesc('expenses_total')
            ->limit(3)
            ->get();

        $savingsAccountsQuery = $user->savingsAccount()
            ->withSum(['transactions as savings_total' => function ($query) {
                $query->where('type', 'savings');
            }], 'amount');

        $top5Savings = (clone $savingsAccountsQuery)
                        ->having('savings_total', '!=', 0)
                        ->orderByDesc('savings_total')
                        ->limit(5)
                        ->get();

        $recentTransactions = $user->transactions()->with(['category', 'savingsAccount'])
            ->orderBy('date', 'desc')
            ->take(8)
            ->get();

        $oldestDate = $user->transactions()
            ->orderBy('date', 'asc')
            ->value('date');
        $oldestYear = $oldestDate ? Carbon::parse($oldestDate)->year : now()->year;

        return view('dashboard.dashboard', compact('netIncome', 'totalExpenses', 'netSavings', 'oldestYear', 'top5Income', 'top5Expenses', 'top5Savings', 'recentTransactions'));
    }

    public function allTransactions(FilterService $filterService)
    {
        $baseQuery = auth()->user()->transactions();

        [$allTransactions] = $filterService->filter(
            $baseQuery,
            ['category', 'savingsAccount'],
            'notGroup'
        );

        $oldestDate = auth()->user()->transactions()
            ->orderBy('date', 'asc')
            ->value('date');
        $oldestYear = $oldestDate ? Carbon::parse($oldestDate)->year : now()->year;
        $categories = [];
        return view('dashboard.table', compact('allTransactions', 'oldestYear','categories'),
                $this->globalData());
    }


    public function getLineChartData()
    {
        $year = request('year_filter') ?? now()->year;
        $user = auth()->user();

        $transactions = $user->transactions()
            ->selectRaw('type, MONTH(date) as month, SUM(amount) as total')
            ->whereYear('date', $year)
            ->whereIn('type', ['savings', 'income', 'expenses'])
            ->groupBy('type', 'month')
            ->get();

        $grouped = [];
        foreach ($transactions as $t) {
            $grouped[$t->type][$t->month] = $t->total;
        }

        $months = [];
        $savingsData = [];
        $incomeData = [];
        $expensesData = [];

        for ($m = 1; $m <= 12; $m++) {
            $months[] = Carbon::create()->month($m)->format('M');

            $savingsData[]  = $grouped['savings'][$m] ?? 0;
            $incomeData[]   = $grouped['income'][$m] ?? 0;
            $expensesData[] = $grouped['expenses'][$m] ?? 0;
        }

        return response()->json([
            'labels' => $months,
            'datasets' => [
                [
                    'label' => 'Income',
                    'data' => $incomeData,
                    'backgroundColor' => '#4794f8ff',
                ],
                [
                    'label' => 'Expenses',
                    'data' => $expensesData,
                    'backgroundColor' => '#ff4747ff',
                ],
                [
                    'label' => 'Savings',
                    'data' => $savingsData,
                    'backgroundColor' => '#07bd80ff',
                ],
            ]
        ]);
    }


    public function updateCurrency(Request $request)
    {
        $request->validate([
            'currency' => 'required|string',
        ]);

        $currency = json_decode($request->input('currency'), true);

        if (!is_array($currency) || !isset($currency['code'], $currency['symbol'])) {
            return redirect()->back()->withErrors(['currency' => 'Invalid currency selected.']);
        }

        $user = auth()->user();
        $user->currency_code = $currency['code'];
        $user->currency_symbol = $currency['symbol'];
        $user->save();

        return redirect()->back()->with('status', 'Currency updated successfully!');
    }
}
