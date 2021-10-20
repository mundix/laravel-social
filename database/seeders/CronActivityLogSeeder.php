<?php

namespace Database\Seeders;

use App\Exports\ActivityLogExport;
use App\Mail\ActivityLogEmail;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class CronActivityLogSeeder extends Seeder
{
    protected $attempts = 0;
    protected $limitAttempts = 5;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = 'activities-' . date('Y-m-d') . '.xlsx';
        if (Excel::store(new ActivityLogExport, $file, 'temp')) {
            $filePath = 'public/' . $file;
            try {
                if (file_exists($filePath)) {

                    do {
                        if(!$this->sendMail($filePath)) {
                            $this->deleteFile($filePath);
                            \Log::info('Mail Activity Log send');
                            break;
                        }else{
                            \Log::error('Mail can\'t be sent, attempts #' . $this->limitAttempts);
                        }
                    }while($this->attempts++ < $this->limitAttempts);

                } else {
                    throw new \Exception('file not found in ' . $filePath);
                }
            } catch (\Exception $exception) {
                \Log::error($exception->getMessage());

            }
        }else{
            \Log::error('Error excel not created');
        }
        die or exit(0);
    }

    protected function sendMail($filePath)
    {
        $adminEmails = User::whereType('admin')->pluck('email');
        Mail::to($adminEmails)->send(new ActivityLogEmail($filePath));
        return Mail::failures();
    }

    protected function deleteFile($filePath)
    {
        \File::delete($filePath);
    }
}
