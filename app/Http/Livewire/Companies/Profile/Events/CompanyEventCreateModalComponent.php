<?php

namespace App\Http\Livewire\Companies\Profile\Events;

use App\Models\Event;
use Livewire\Component;
use Livewire\WithFileUploads;

class CompanyEventCreateModalComponent extends Component
{
    use WithFileUploads;

    public $company;
    public $profile;
    public $name;
    public $description;
    public $total_amount;
    public $due_date;
    public $participants;

    public $rules = [
        'profile' => 'required',
        'name' => 'required',
        'description' => 'required',
        'total_amount' => 'required',
        'participants' => 'required',
        'due_date' => 'required',
    ];

    public function render()
    {
        return view('livewire.companies.profile.events.company-event-create-modal-component');
    }

    public function save()
    {
        $data = [
            'profile' => $this->profile,
            'name' => $this->name,
            'participants' => $this->participants,
            'description' => $this->description,
            'total_amount' => $this->total_amount,
            'due_date' => $this->due_date,
        ];
        $event = Event::create($data);

        if ($this->profile) {
            $file_name = 'picture_' . $event->id . '.' . $this->profile->getClientOriginalExtension();
            $event->addMedia($this->profile->getRealPath())->usingName($file_name)->toMediaCollection('profile');
        }

        $this->company->user->events()->save($event);
        $this->resetInputs();
        $this->emit('closeModals');
        $this->emit('openSuccessEvent');
        $this->emit('companyEventsCompany');
        $this->emit('updateDOM');
    }

    public function resetInputs()
    {
        $this->profile = null;
        $this->name = '';
        $this->description = '';
        $this->due_date = '';
        $this->participants = '';
        $this->total_amount = '';
    }
}
