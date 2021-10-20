<?php
$date = \Carbon\Carbon::now()->firstOfMonth()->subMonths(6);

for ($i = 1; $i <= $amount; $i++) {
    $columns[] = [
        'name' => $date->format('M'),
        'value' => User::where('created_at', '>=',  $date->firstOfMonth()->startOfDay())
      				->where('created_at', '<=',  $date->endOfMonth()->endOfDay())
      				->get()
      				->count()
    ];
    $date = $date->addMonth()->startOfMonth();
}