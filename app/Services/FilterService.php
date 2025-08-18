<?php

namespace App\Services;

use Carbon\Carbon;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class FilterService
{
    public function filter($baseQuery, array $with = [], $page = null)
    {
        $searchValue  = trim(request('filter.search', request('search', '')));
        $dateFilter   = trim(request('date_filter', ''));
        $monthFilter  = request('month_filter');
        $yearFilter   = request('year_filter');
        $startDate    = request('start');
        $endDate      = request('end');

        // Sorting headers
        $sort  = request('sort', 'date');
        $order = request('order', 'desc');

        $allowedSorts = ['date', 'amount', 'type', 'notes', 'name'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'date';
        }

        // ✅ Validations
        if (Str::length($searchValue) > 100) {
            throw ValidationException::withMessages([
                'search' => ['The search query must not be longer than 100 characters.']
            ]);
        }

        if ($monthFilter && (!is_numeric($monthFilter) || $monthFilter < 1 || $monthFilter > 12)) {
            throw ValidationException::withMessages([
                'month_filter' => ['The selected month is invalid.']
            ]);
        }

        if ($yearFilter && (!is_numeric($yearFilter) || $yearFilter < 1950 || $yearFilter > date('Y') + 1)) {
            throw ValidationException::withMessages([
                'year_filter' => ['The selected year is invalid.']
            ]);
        }

        if ($startDate && !strtotime($startDate)) {
            throw ValidationException::withMessages([
                'start' => ['The start date is invalid or in an incorrect format.']
            ]);
        }

        if ($endDate && !strtotime($endDate)) {
            throw ValidationException::withMessages([
                'end' => ['The end date is invalid or in an incorrect format.']
            ]);
        }

        
        $query = QueryBuilder::for($baseQuery)
            ->when(!empty($with), fn($q) => $q->with($with))
            ->allowedFilters([
                AllowedFilter::callback('search', function ($query, $value) {
                    // Join categories if searching by category name
                    $query->leftJoin('categories', 'transactions.category_id', '=', 'categories.id')
                          ->select('transactions.*')
                          ->where(function ($q) use ($value) {
                              $q->where('transactions.notes', 'like', "%{$value}%")
                                ->orWhere('transactions.type', 'like', "%{$value}%")
                                ->orWhere('transactions.amount', 'like', "%{$value}%")
                                ->orWhere('categories.name', 'like', "%{$value}%");
                          });
                }),
            ]);

        // Apply sorting depending on mode
        if ($page === 'group') {
            $query->orderBy('transactions.date', 'desc');
        } else {
            if ($sort === 'name') {
                // join categories for sorting
                $query->leftJoin('categories', 'transactions.category_id', '=', 'categories.id')
                      ->select('transactions.*')
                      ->orderBy('categories.name', $order);
            } else {
                $query->orderBy("transactions.$sort", $order);
            }
        }

        // Date filters
        if ($dateFilter) {
            $ranges = [
                'today'        => [Carbon::today(), Carbon::today()],
                'last_7_days'  => [Carbon::now()->subDays(6)->startOfDay(), Carbon::now()->endOfDay()],
                'last_30_days' => [Carbon::now()->subDays(30)->startOfDay(), Carbon::now()->endOfDay()],
            ];

            if (isset($ranges[$dateFilter])) {
                [$start, $end] = $ranges[$dateFilter];
                if ($start->equalTo($end)) {
                    $query->whereDate('transactions.date', $start);
                } else {
                    $query->whereBetween('transactions.date', [$start, $end]);
                }
            }
        }

        if ($monthFilter && $yearFilter) {
            $query->whereMonth('transactions.date', $monthFilter)
                  ->whereYear('transactions.date', $yearFilter);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('transactions.date', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        if ($page === 'group') {
            $paginated = $query->paginate(5)->withQueryString();

            $groupedTransactions = $paginated->getCollection()
                ->groupBy(fn($transaction) => $transaction->date->format('Y-m-d'));

            $sumByTypePerDate = [];
            foreach ($groupedTransactions as $date => $transactions) {
                $sumByTypePerDate[$date] = [
                    'income'   => $transactions->where('type', 'income')->sum('amount'),
                    'expenses' => $transactions->where('type', 'expenses')->sum('amount'),
                    'savings'  => $transactions->where('type', 'savings')->sum('amount'),
                ];
            }

            $paginated->setCollection($groupedTransactions);

            return [$paginated, $sumByTypePerDate];
        } else {
            $paginated = $query->paginate(10)->withQueryString();
            return [$paginated];
        }
    }
}
