<?php

namespace App\Services;

use Carbon\Carbon;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class FilterService
{
    public function filter($baseQuery, array $with = [])
    {

        $searchValue  = trim(request('filter.search', request('search', '')));
        $dateFilter   = trim(request('date_filter', ''));
        $monthFilter  = request('month_filter');
        $yearFilter   = request('year_filter');
        $startDate    = request('start');
        $endDate      = request('end');

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
                    $query->where(function ($q) use ($value) {
                        $q->where('notes', 'like', "%{$value}%")
                            ->orWhere('type', 'like', "%{$value}%")
                            ->orWhere('amount', 'like', "%{$value}%");
                    });
                }),
            ])
            ->orderBy('date', 'desc');

        if ($dateFilter) {
            $ranges = [
                'today'        => [Carbon::today(), Carbon::today()],
                'last_7_days'  => [Carbon::now()->subDays(6)->startOfDay(), Carbon::now()->endOfDay()],
                'last_30_days' => [Carbon::now()->subDays(30)->startOfDay(), Carbon::now()->endOfDay()],
            ];

            if (isset($ranges[$dateFilter])) {
                [$start, $end] = $ranges[$dateFilter];
                if ($start->equalTo($end)) {
                    $query->whereDate('date', $start);
                } else {
                    $query->whereBetween('date', [$start, $end]);
                }
            }
        }

        if ($monthFilter && $yearFilter) {
            $query->whereMonth('date', $monthFilter)
                  ->whereYear('date', $yearFilter);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('date', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

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
    }
}
