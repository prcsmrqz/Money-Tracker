<?php

namespace App\Traits;

trait ActiveTab
{
    public function getActiveTab(): string
    {
        $dateFilter  = request('date_filter');
        $monthFilter = request('month_filter');
        $yearFilter  = request('year_filter');
        $startFilter = request('start');
        $endFilter   = request('end');

        return ($dateFilter || ($monthFilter && $yearFilter) || ($startFilter && $endFilter))
            ? 'chart'
            : '';
    }
}
