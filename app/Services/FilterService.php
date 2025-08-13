<?php

namespace App\Services;

use Carbon\Carbon;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class FilterService
{
    public function filter($baseQuery, array $with = [])
    {
        $query = QueryBuilder::for($baseQuery)
            ->when(!empty($with), fn($q) => $q->with($with))
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

        // Date filter
        if (request('date_filter')) {
            $dateFilter = request('date_filter');
            if ($dateFilter === 'today') {
                $query->whereDate('date', Carbon::today());
            } elseif ($dateFilter === 'last_7_days') {
                $query->whereBetween('date', [
                    Carbon::now()->subDays(6)->startOfDay(),
                    Carbon::now()->endOfDay(),
                ]);
            } elseif ($dateFilter === 'last_30_days') {
                $query->whereBetween('date', [
                    Carbon::now()->subDays(30)->startOfDay(),
                    Carbon::now()->endOfDay(),
                ]);
            }
        }

        // Month + Year filter
        if (request('month_filter') && request('year_filter')) {
            $query->whereMonth('date', request('month_filter'))
                  ->whereYear('date', request('year_filter'));
        }

        // Start/End date filter
        if (request('start') && request('end')) {
            $query->whereBetween('date', [
                Carbon::parse(request('start'))->startOfDay(),
                Carbon::parse(request('end'))->endOfDay()
            ]);
        }

        // Pagination
        $paginated = $query->paginate(5)->withQueryString();

        // Group by date
        $groupedTransactions = $paginated->getCollection()
            ->groupBy(fn($transaction) => $transaction->date->format('Y-m-d'));

        // Sum by type
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
    }
}
