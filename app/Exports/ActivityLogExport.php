<?php

namespace App\Exports;

use App\Services\ActivityService;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Spatie\Activitylog\Models\Activity;

class ActivityLogExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $activities = Activity::whereBetween('created_at', [Carbon::yesterday(), Carbon::yesterday()->endOfDay()])
            ->get();
        $output = [];
        foreach ($activities ?? [] as $activity) {
            if (isset($activity->causer->type) && in_array($activity->causer->type, ['employee', 'company'])) {

                    $subject = (new ActivityService)->getActivitySubject($activity);
                    $properties = (new ActivityService)->getActivityPropertiesToArray($activity);

                    $output[] = [
                        $activity->created_at->format('Y-m-d H:i:s'),
                        $activity->description,
                        $activity->causer->company->name  ?? $activity->causer->employee->name  ?? '',
                        $subject,
                        $properties
                    ];
            }
        }
        return collect($output);

    }
}
