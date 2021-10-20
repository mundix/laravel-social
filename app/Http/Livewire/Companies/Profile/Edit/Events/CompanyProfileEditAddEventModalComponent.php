<?php

namespace App\Http\Livewire\Companies\Profile\Edit\Events;

use App\Models\Employee;
use App\Models\Event;
use App\Models\User;
use App\Traits\SupportUiNotification;
use App\Traits\ValidatorErrorManagementTrait;
use Livewire\Component;
use Livewire\WithFileUploads;

class CompanyProfileEditAddEventModalComponent extends Component
{
    use WithFileUploads;
    use SupportUiNotification;
    use ValidatorErrorManagementTrait;

    public $company;
    public $employee;
    public $user;
    public $profile;
    public $name;
    public $description;
    public $total_amount;
    public $global_amount;
    public $due_date;
    public $participants;
    public $sponsors;
    public $canManageSponsors = true;

    protected $listeners = [
        'updateSponsorsCompanyProfileEditAddEventModalComponent' => 'updateSponsors'
    ];

    public $rules = [
        'profile' => 'required',
        'name' => 'required',
        'description' => 'required',
        'global_amount' => 'required',
        'participants' => 'required',
        'due_date' => 'required',

    ];

    protected $messages = [
        'profile.required' => 'Please add an image to this Event.',
        'global_amount.required' => 'Please add the Amount Goal.',
        'total_amount.required' => 'Please add the Amount Reached.',
    ];

    protected $validationAttributes = [
        'profile' => 'Event Image'
    ];

    public function mount($company)
    {
        $this->employee = null;
        if (auth()->check()) {
            $this->user = auth()->user();

            if ($this->user->type === 'employee') {
                $this->employee = $this->user->employee;
            } else {
                $this->canManageSponsors = true;
            }
        }
        $this->company = $company;
        $this->sponsors = collect([]);
        $this->emit('renderCompanyProfileEditEventSponsorsModalComponent');
        $this->emit('refreshCompanyProfileEditEventSponsorsModalComponent');

    }

    /**
     * This will update and sent sponsors selected to updateCompanyAdminNewEventSponsorsLoopComponent
    */
    public function updateSponsors($sponsors)
    {
        $this->sponsors = $sponsors;
        $this->emit('updateCompanyAdminNewEventSponsorsLoopComponent', $sponsors);
    }

    public function render()
    {
        return view('livewire.companies.profile.edit.events.company-profile-edit-add-event-modal-component');
    }

    public function save()
    {
        $data = [
            'profile' => $this->profile,
            'user_id' => $this->company->user->id,
            'name' => $this->name,
            'participants' => $this->participants,
            'description' => $this->description,
            'global_amount' => $this->global_amount,
            'due_date' => $this->due_date,
            'total_amount' => $this->total_amount ?? 0,
        ];

        $validator = \Validator::make($data, $this->rules, $this->messages, $this->validationAttributes);

        if ($validator->fails()) {
            $message = $this->getErrorFromValidator($validator, true);
            $this->alert()->error(['title' => $message]);
            $validator->validate();
        }

        if (!is_null($this->employee)) {
            $data['referral_id'] = $this->employee->user->id;
            $data['status'] = 'draft';
        } else {
            $data['status'] = 'enabled';
        }

        $event = Event::create($data);

        if ($this->sponsors) {
            $event->sponsors()->attach($this->sponsors);
        }

        if ($this->profile) {
            $file_name = 'picture_' . $event->id . '.' . $this->profile->getClientOriginalExtension();

            $event->addMedia($this->profile->getRealPath())->usingName($file_name)->toMediaCollection('profile');
        }

        $this->company->user->events()->save($event);

        $this->user = auth()->user();

        if ($this->user->type === 'employee') {
            $this->alert()->success(['title' => 'Your Event Request was sent successfully']);
        } else {
            $this->alert()->success(['title' => 'Your Event was sent successfully']);
        }

        $this->emit('companyProfileEditEventsComponent');

        $this->emit('updateDOM');
        $this->emit('closeModals');

        $this->resetInputs();
    }

    public function resetInputs()
    {
        $this->reset([
            'profile',
            'name',
            'description',
            'total_amount',
            'due_date',
            'sponsors',
            'participants',
            'global_amount',
        ]);

        $this->sponsors = collect([]);
        $this->emit('clearCompanyAdminNewEventSponsorsLoopComponent');
    }

    public function updatedDueDate($value)
    {
        $this->due_date = \Carbon\Carbon::parse($value)->format('Y-m-d');
    }
}
