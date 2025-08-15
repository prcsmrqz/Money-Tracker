<?php

namespace App\Traits;

trait ActiveTab
{
public function getActiveTab(): string
    {
        
        $mode        = request('mode');
        $dateFilter  = request('date_filter');
        $monthFilter = request('month_filter');
        $yearFilter  = request('year_filter');
        $startFilter = request('start');
        $endFilter   = request('end');

        $hasFilter = $dateFilter || ($monthFilter && $yearFilter) || ($startFilter && $endFilter);

        // Always return mode if it's set, otherwise 'icon'
        if ($hasFilter) {
            return $mode ?: 'icon';
        }

        return $mode ?: 'icon';
    }
}
