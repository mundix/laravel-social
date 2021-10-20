<?php


namespace App\Interfaces;


interface Filters
{
    public CONST OPTION_LABELS = [
        'Last 7 Days',
        'Last Month',
        'Last 3 Months',
        'Last 6 Months',
        'Last Year'
    ];

    public CONST OPTIONS = [
        ['value' => 7, 'period' => 'day'],
        ['value' => 1, 'period' => 'month'],
        ['value' => 3, 'period' => 'month'],
        ['value' => 6, 'period' => 'month'],
        ['value' => 1, 'period' => 'year'],
    ];
}