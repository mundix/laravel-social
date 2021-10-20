<?php

namespace App\Http\Livewire\Companies\Profile\Edit;

use App\Models\CompanyInvite;
use App\Models\Employee;
use App\Models\User;
use App\Models\UserToken;
use App\Services\GlobalService;
use App\Traits\SupportUiNotification;
use App\Traits\ValidatorErrorManagementTrait;
use Livewire\Component;
use Livewire\WithFileUploads;

class CompanyProfileEditAddMultipleEmployeesComponent extends Component
{
    use WithFileUploads;
    use SupportUiNotification;
    use ValidatorErrorManagementTrait;

    public $isUploaded = false;
    public $isInviteSend = false;
    public $company;
    public $file;
    public $invites = [];
    public $errors = [];
    public $errorsFound = false;

    protected $listeners = [
        'CompanyProfileEditAddMultipleEmployeesComponent' => '$refresh',
        'renderCompanyProfileEditAddMultipleEmployeesComponent' => 'render',
    ];

    public $rules = [
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|unique:users|email:rfc,dns',
    ];

    public function render()
    {
        return view('livewire.companies.profile.company-profile-add-multiple-employees-component');
    }

    public function updatedFile()
    {
        $extension = $this->file->getClientOriginalExtension();
        if (!in_array(strtolower(trim($extension)), ['txt', 'csv'])) {
            $this->alert()->error(['title' => 'Please upload a valid CSV file. You can download the template below to get you started.']);
            return;
        }

        $validator = \Validator::make(['file' => $this->file], ['file' => 'required|max:2560']);

        if ($validator->fails()) {
            $message = $this->getErrorFromValidator($validator, true);

            $this->alert()->error(['title' => $message]);

            $validator->validate();

            $this->isUploaded = false;
        }

        if ($fp = fopen($this->file->getRealPath(), 'r+')) {
            $this->alert()->success(['title' => 'File was successfully opened']);
            $errorsFound = false;

            while (!feof($fp)) {
                $data = fgets($fp);
                if (!empty(trim($data))) {
                    list($firstName, $lastName, $email) = explode(',', $data);

                    if (!$this->isUserInvited($email)) {
                        $this->invites[] = [
                            'first_name' => trim($firstName),
                            'last_name' => trim($lastName),
                            'email' => trim($email),
                            'status' => '',
                            'sent' => 0,
                        ];
                    } else {
                        $this->errors[] = '<p >This user ' . $email . ' was invited </p>';
                        $errorsFound = true;
                    }
                }
            }

            if (count($this->invites)) {
                $this->alert()->success(['title' => 'Employees found in this file ' . count($this->invites)]);

                $this->isUploaded = true;
            } else {
                $this->alert()->error(['title' => 'Employees not found in this file']);

                $this->isUploaded = false;
            }
            if ($errorsFound) {
                $this->alert()->error(['title' => implode(',', $this->errors), 'timeout' => 10000]);
            }
            fclose($fp);
        } else {
            $this->alert()->error(['title' => 'Can\' opened this file']);

            $this->isUploaded = false;
        }
    }

    public function sentInvitations()
    {
        foreach ($this->invites as $key => $invite) {
            if (!User::whereEmail($invite['email'])->first()) {
                if ($this->invite($invite)) {
                    $this->invites[$key]['status'] = 'sent';
                } else {
                    $this->invites[$key]['status'] = 'fail';
                }
            } else {
                $this->alert()->error(['title' => 'Your invitations weren\'t sent successfully']);
                unset($this->invites[$key]);
            }
        }
        $total = 0;
        foreach ($this->invites as $invite) {
            $total += $invite['status'] === 'sent' ? 1 : 0;
        }
        if ($total == count($this->invites)) {
            $this->alert()->success(['title' => 'Your invitations have been sent successfully.']);
            $this->emit('closeModals');
        } elseif($this->errorsFound) {
            $this->alert()->error(['title' => '<p>Not all of your emails were sent successfully. Please check the list below: </p>' . implode( '', $this->errors), 'timeout' => 100000]);
            $this->emit('closeModals');
        } elseif ($total >= 1) {
            $this->alert()->success(['title' => 'Your almost all invitations was sent']);
            $this->emit('closeModals');
        } else {
            $this->alert()->success(['title' => 'Your invitations weren\'t sent successfully']);
        }

        $this->emit('CompanyProfileEditAddMultipleEmployeesComponent');
        $this->startOver();
    }

    private function invite($invite = []): bool
    {
        $data = [
            'type' => 'employee',
            'status_id' => 3,
            'confirmed' => 'pending',
            'accept_agreements' => true,
            'email' => $invite['email'],
            'password' => \Hash::make(\Str::random(9))
        ];

        try {
            $validator = \Validator::make($invite, $this->rules);

            if ($validator->fails()) {
                $message = $this->getErrorFromValidator($validator, true);
                $this->alert()->error(['title' => $message]);
                $this->validate();
            }
        }catch (\Exception $e ) {
            $this->errors[] = '<p>The email: ' . $invite['email'] . ' hasn\'t a valid extension or no exist.</p>';
            $this->errorsFound = true;
            return false;
        }

        $user = User::create($data);

        $data = [
            'first_name' => $invite['first_name'],
            'last_name' => $invite['last_name'],
        ];

        $employee = Employee::create($data);

        $user->employee()->save($employee);

        $token = GlobalService::generateToken();

        $data = [
            'employee_id' => $user->employee->id,
            'company_id' => $this->company->id
        ];

        $employee->user->user_token()->save(UserToken::create(['token' => $token]));
        $companyInvite = CompanyInvite::create($data);
        $this->company->invites()->save($companyInvite);
        $this->company->employees()->attach($employee->id);
        return true;
    }

    public function startOver()
    {
        $this->invites = [];

        $this->isUploaded = false;

        $this->reset(['file', 'isUploaded', 'invites']);
        $this->emit('CompanyProfileEditAddMultipleEmployeesComponent');
        $this->emit('renderCompanyProfileEditAddMultipleEmployeesComponent');
        $this->emit('updateDOM');
    }

    /**
     * @param string $email
     */
    private function isUserInvited($email): bool
    {
        $user = User::where('email', trim($email))->exists();

        if ($user) {
            return true;
        }
        return false;
    }

}
