<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SavingsAccount;
use Illuminate\Support\Facades\Storage;
use Dotenv\Exception\ValidationException;
use App\Http\Requests\StoreSavingsRequest;
use App\Http\Requests\UpdateSavingsRequest;

use Carbon\Carbon;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class SavingsController extends Controller
{
    public function index() 
    {
        $savingsAccounts = auth()->user()->savingsAccounts()->orderBy('name', 'ASC')->paginate(15);
        foreach( $savingsAccounts as $savingsAccount) {
            $savings = auth()->user()->transactions()
                            ->where('savings_account_id', $savingsAccount->id)
                            ->where('type', 'savings')
                            ->sum('amount');
                
            $expenses = auth()->user()->transactions()
                            ->where('source_savings', $savingsAccount->id)
                            ->where('type', 'expenses')
                            ->sum('amount');

            $savingsAccount->totalSavings =  $savings - $expenses;
        }

        $totalIncome = auth()->user()->transactions()->where('type', 'savings')->whereMonth('date', now()->month)->whereYear('date', now()->year)->sum('amount');

        $savings = auth()->user()->transactions()->where('type', 'savings')->sum('amount');
        $expenses = auth()->user()->transactions()->where('type', 'expenses')->whereNotNull('source_savings')->sum('amount');
        $totalSavings = $savings - $expenses;

        $top3Savings = $savingsAccounts->sortByDesc('totalSavings')->take(3);

        //chart
        $oldestDate = auth()->user()->transactions()
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

        return view('savings.index', compact('savingsAccounts', 'totalIncome', 'activeTab', 'oldestYear', 'totalSavings', 'top3Savings'));
    }

    public function show (SavingsAccount $saving)
    {
        $baseQuery = auth()->user()->transactions()
                        ->where(function ($query) use ($saving) {
                            $query->where('savings_account_id', $saving->id)
                                ->orWhere('source_savings', $saving->id);
                        });

        // Get oldest date's year
        $oldestDate = (clone $baseQuery)->orderBy('date', 'asc')->value('date');
        $oldestYear = $oldestDate ? Carbon::parse($oldestDate)->year : now()->year;

        // Build the main query (filtered with Spatie QueryBuilder)
        $query = QueryBuilder::for($baseQuery)
            ->with('savingsAccounts')
            ->allowedFilters([
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->where(function ($q) use ($value) {
                        $q->where('notes', 'like', "%{$value}%")
                            ->orWhere('type', 'like', "%{$value}%")
                            ->orWhere('amount', 'like', "%{$value}%");
                    });
                }),
            ])
            ->orderBy('date', 'desc');


            if (request('date_filter')) {
                $dateFilter = request('date_filter');
                if($dateFilter == 'today'){
                    $query->whereDate('date', Carbon::today());
                } else if ($dateFilter == 'last_7_days'){
                    $query->whereBetween('date', [
                        Carbon::now()->subDays(6)->startOfDay(),
                        Carbon::now()->endOfDay(),
                    ]);
                } else if ($dateFilter == 'last_30_days') {
                    $query->whereBetween('date', [
                        Carbon::now()->subDays(30)->startOfDay(),
                        Carbon::now()->endOfDay(),
                    ]);
                } 
                
            }

            if (request('month_filter') && request('year_filter')) {
                $monthFilter = request('month_filter');
                $yearFilter = request('year_filter');
                    $query->whereMonth('date', $monthFilter )->whereYear('date', $yearFilter);
            }

            if (request('start') && request('end')) {
                $query->whereBetween('date', [
                    Carbon::parse(request('start'))->startOfDay(),
                    Carbon::parse(request('end'))->endOfDay()
                ]);
            }

            $paginated = $query->paginate(5)->withQueryString();

            $groupedTransactions = $paginated->getCollection()
                ->groupBy(fn($transaction) => $transaction->date->format('Y-m-d'));

            $sumByTypePerDate = [];
            foreach ($groupedTransactions as $date => $transactions) {
                $sumByTypePerDate[$date] = [
                    'income' => $transactions->where('type', 'income')->sum('amount'),
                    'expenses' => $transactions->where('type', 'expenses')->sum('amount'),
                    'savings' => $transactions->where('type', 'savings')->sum('amount'),
                ];
            }

            $paginated->setCollection($groupedTransactions);


            return view('savings.show', [
                'transactions' => $paginated,
                'sumByTypePerDate' => $sumByTypePerDate,
                'savingsAccount' => $saving,
                'oldestYear' => $oldestYear,
            ]);
    }

    public function store(StoreSavingsRequest $request) 
    {
        try {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        $savingsData = [];
        
        $savingsData['name'] = $data['name'];
        $savingsData['color'] = $data['color'];
        $savingsData['type'] = $data['type'];
        $savingsData['account_number'] = $data['account_number'];
        $savingsData['icon'] = $request->file('icon') ? $request->file('icon')->store('icons', 'public') : null;
        $savingsData['user_id'] = $data['user_id'];

        $savings = auth()->user()->savingsAccounts()->create($savingsData);

        $transactionData = [];
        $transactionData['date'] = $data['date'];
        $transactionData['amount'] = $data['amount'];
        $transactionData['type'] = $data['transaction_type'];
        $transactionData['savings_account_id'] = $savings->id;
        $transactionData['user_id'] = $data['user_id'];

        auth()->user()->transactions()->create($transactionData);

        return redirect()->back()->with('success', 'Savings account created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()
            ->withErrors($e->validator, 'create')
            ->withInput();

        }
    }

    public function update(UpdateSavingsRequest $request, $id)
    {
        $savings = auth()->user()->savingsAccounts()->findOrFail($id);

        try {
            $data = $request->validated();
            if ($request->hasFile('icon')) {
                $data['icon'] = $request->file('icon')->store('icons', 'public');
            } else {
                unset($data['icon']);
            }
            $savings->update($data);
            return redirect()->back()->with('success', 'Savings account updated successfully.');

        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->validator, 'update_' . $id)
                ->withInput();
        }

    }


    public function destroy ($id)
    {
        $savings = auth()->user()->savingsAccounts()->findOrFail($id);
        if ($savings) {
            if ($savings->icon && Storage::disk('public')->exists($savings->icon)) {
                Storage::disk('public')->delete($savings->icon);
            }
            
            $savings->delete();
            return redirect()->back()->with('success', 'Savings Account deleted successfully.');
        }
        return redirect()->back()->with('error', 'Savings Account not found.');
    }

    public function savingsChart(Request $request)
    {
        $user = auth()->user();
        $savings = $user->savingsAccounts()->orderBy('name')->get();

        foreach ($savings as $saving) {
            $transactions = $user->transactions()
                ->where('type', 'savings')
                ->where('savings_account_id', $saving->id);

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

            $saving->totalSavings = $transactions->sum('amount');
        }

        return response()->json($savings);
    }
}
