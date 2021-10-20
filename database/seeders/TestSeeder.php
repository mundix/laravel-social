<?php

namespace Database\Seeders;

use App\Exports\ActivityLogExport;
use App\Helpers\Currency;
use App\Mail\ActivityLogEmail;
use App\Models\Cause;
use App\Models\Company;
use App\Models\Employee;
use App\Services\ActivityService;
use App\Services\CauseService;
use App\Services\CompanyService;
use App\Services\InvolvementService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Activitylog\Models\Activity;
use App\Models\User;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       return get_class($this);
    }
}
