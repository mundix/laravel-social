<?php

namespace App\Http\Livewire\Companies\Profile\Edit\Events;

use App\Models\Event;
use App\Traits\SupportUiNotification;
use Livewire\Component;

class CompanyProfileEditEventLoopComponent extends Component
{
    public $event;
    public $employee;
    use SupportUiNotification;

    protected $listeners = [
        'CompanyProfileEditEventLoopComponent' => '$refresh',
        'deleteEvent' => 'doDelete'
    ];

    public function render()
    {
        return view('livewire.companies.profile.edit.events.company-profile-edit-event-loop-component');
    }

    public function mount($employee = null)
    {
        $this->employee = $employee;
        $this->emit('updateDOM');
    }

    public function delete()
    {
        $this->alert()->confirm([
            'icon' => 'warning',
            'title' => 'Are you sure you want to delete this?' ,
            'confirmButtonText' => 'Yes',
            'method' => 'deleteEvent',
            'params' => $this->event->id
        ]);
    }

    public function doDelete($id)
    {
        $obj = Event::find($id)->first();
        $obj->delete();
        $this->alert()->success(['title' => 'Event was deleted']);
        return redirect()->route('company.admin.index');
    }

}
