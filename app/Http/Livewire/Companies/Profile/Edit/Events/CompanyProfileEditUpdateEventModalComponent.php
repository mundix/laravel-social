<?php

namespace App\Http\Livewire\Companies\Profile\Edit\Events;

use App\Models\Employee;
use App\Models\Event;
use App\Traits\SupportUiNotification;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;

class CompanyProfileEditUpdateEventModalComponent extends Component
{

    use WithFileUploads;
    use SupportUiNotification;

    public $company;
    public $event;
    public $profile;
    public $currentProfile;
    public $name;
    public $description;
    public $total_amount;
    public $global_amount;
    public $due_date;
    public $participants;
    public $sponsors;

    protected $listeners = [
        'setCompanyProfileEditUpdateEventModalComponent' => 'setEvent',
        'CompanyProfileEditUpdateEventModalComponent' => '$refresh'
    ];

    public $rules = [
        'name' => 'required',
        'description' => 'required',
        'global_amount' => 'required',
        'participants' => 'required',
        'due_date' => 'required',
    ];

    public function render()
    {
        return view('livewire.companies.profile.edit.events.company-profile-edit-update-event-modal-component');
    }
    public function save($action = null)
    {

        $total_amount = empty(trim($this->total_amount)) ? 0: $this->total_amount;
        $data = [
            'name' => $this->name,
            'participants' => $this->participants,
            'description' => $this->description,
            'global_amount' => $this->global_amount,
            'due_date' => $this->due_date,
            'total_amount' => $total_amount,
        ];

        $validator = \Validator::make($data, $this->rules);

        if ($validator->fails()) {
            $message = $this->getErrorFromValidator($validator);
            $this->alert()->error(['title' => $message]);
            $validator->validate();
        }

        $this->event->update($data);
        if(is_null($action) && $action != 'cancel') {
            $this->alert()->success(['title' => 'Your Event has been updated.']);
        }

        $this->emit('closeEditEventModal');
        if($this->event->status === 'draft') {
            $this->emit('renderCompanyProfileEditApprovalEventsLoopComponent');
            $this->emit('refreshCompanyProfileEditApprovalEventsLoopComponent');
            $this->emit('openRequestModal');
        }else {
            $this->emit('CompanyProfileEditEventLoopComponent');
            $this->emit('renderCompanyProfileEditEventLoopComponent');
        }

    }
    public function resetInputs()
    {
        $this->reset([
            'profile',
            'name',
            'description',
            'due_date',
            'total_amount',
            'participants',
            'global_amount',
        ]);
    }

    public function setEvent($id)
    {
        $this->event = Event::find($id);
        $this->name = $this->event->name;
        $this->currentProfile = $this->event->profile->url ?? null;
        $this->sponsors = $this->event->sponsors;
        $this->description = $this->event->description;
        $this->due_date = $this->event->due_date;

        $this->due_date = Carbon::parse($this->event->due_date)->format('Y-m-d');

        $this->participants = $this->event->participants;
        $this->total_amount = $this->event->total_amount;
        $this->global_amount = $this->event->global_amount;

        $this->emit('openEditEventModal');
        $this->emit('set-date', $this->due_date);

        if($this->event->status === 'draft') {
            $this->emit('closeRequestModal');
        }
    }

    /**
     * Livewire Hooks, when a property updated by any interaction, in this case when choose an image.
     */
    public function updatedProfile()
    {
        $this->validate([
            'profile' => 'image|max:1024',
        ], [
            'profile.required' => 'Please add an image to this Event.'
        ]);

        $file_name = 'event_profile_' . $this->event->id . '.' . $this->profile->getClientOriginalExtension();

        $this->event->addMedia($this->profile->getRealPath())->usingName($file_name)->toMediaCollection('profile');
        $this->currentProfile = $this->event->profile->url ?? '';
        $this->alert()->success(['title' => 'Event Profile Picture Updated']);
    }

    public function deleteSponsor($sponsor)
    {
        $user = Employee::find($sponsor)->user;
        $this->event->sponsors()->detach($user->id);
        $this->alert()->success(['title' => 'Sponsor was removed']);
        $this->emit('CompanyProfileEditUpdateEventModalComponent');
    }


}
