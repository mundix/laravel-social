<?php

namespace App\Http\Livewire\Admin;

use App\Interfaces\Filters;
use App\Models\Employee;
use App\Models\User;
use App\Services\EmployeeService;
use App\Traits\SupportUiNotification;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class AdminDashboardUsersComponent extends Component implements Filters
{
    use WithPagination;
    use SupportUiNotification;

    public $arrayFilter = self::OPTIONS;
    public  $optionFilter = 0;
    public $filter;
    public $filterOptions = self::OPTION_LABELS;
    public $filterSelected;
    public $key = 'userColumnChart';

    public function render()
    {
        return view('livewire.admin.admin-dashboard-users-component', [
            'userColumnChart' => $this->updateUsersChart($this->filter)
        ]);
    }

    public function mount()
    {
        $this->filter = self::OPTIONS[0];
        $this->filterSelected = $this->filterOptions[0];
    }

    public function updatedOptionFilter($value)
    {
        $this->filter = $this->arrayFilter[$value] ?? $this->arrayFilter[0];
        $this->filterSelected = $this->filterOptions[$value] ?? $this->filterOptions[0];
    }

    private function updateUsersChart($filter): ColumnChartModel
    {
        $chart = new ColumnChartModel();
        $chart->setTitle('New Users');
        foreach ($this->getChartColumns($filter['period'], $filter['value']) as $column) {
            $chart->addColumn($column['name'], $column['value'], '#6D905A')->setOpacity(1);
        }

        $chart->setAnimated(true);
        $chart->withoutLegend();
        $chart->withoutDataLabels();
        return $chart;
    }

    private function getChartColumns($period, $amount): array
    {
        /**
         * TODO: Refactor this code after the presentation (Big rush)
         */
        $columns = [];
        [$period, $amount] = $period == 'month' && $amount == 1 ? ['day', Carbon::now()->subMonth()->daysInMonth] : [$period, $amount];
        if ($period == 'day') {
            $isAMonth = $amount == Carbon::now()->subMonth()->daysInMonth;
            for ($i = $amount; $i >= 1; $i--) {
                $date = Carbon::now()->subRealDays($i);
                $startOfDay = Carbon::now()->subRealDays($i)->startOfDay();
                $endOfDay = Carbon::now()->subRealDays($i)->endOfDay();
                if ($isAMonth) {
                    $date = Carbon::now()->startOfMonth()->subRealDays($i);
                    $startOfDay = Carbon::now()->startOfMonth()->subRealDays($i)->startOfDay();
                    $endOfDay = Carbon::now()->startOfMonth()->subRealDays($i)->endOfDay();
                }
                $columns[] = [
                    'name' => $isAMonth ? $date->format('d') : $date->format('M d'),
                    'value' => User::whereBetween('created_at',[$startOfDay, $endOfDay])->count()
                ];
            }
        }

        if ($period == 'month') {
            for ($i = $amount; $i >= 1; $i--) {
                $date = Carbon::now()->subRealMonths($i);
                $firstOfMonth = Carbon::now()->subRealMonths($i)->firstOfMonth()->startOfDay();
                $endOfMonth = Carbon::now()->subRealMonths($i)->endOfMonth()->endOfDay();
                $columns[] = [
                    'name' => $date->format('M'),
                    'value' => User::whereBetween('created_at', [
                        $firstOfMonth,
                        $endOfMonth
                    ])->count()
                ];
            }
        }

        if ($period == 'year') {
            $amount = 12;
            for ($i = 0; $i < $amount; $i++) {
                $date = Carbon::now()->subYear()->startOfYear()->addMonths($i)->startOfDay();
                $firstOfMonth = Carbon::now()->subYear()->startOfYear()->addMonths($i)->startOfMonth()->startOfDay();
                $endOfMonth = Carbon::now()->subYear()->startOfYear()->addMonths($i)->endOfMonth()->endOfDay();
                $columns[] = [
                    'name' => $date->format('M'),
                    'value' => User::whereBetween('created_at', [
                        $firstOfMonth,
                        $endOfMonth
                    ])->count()
                ];
            }
        }
        return $columns;
    }
}
