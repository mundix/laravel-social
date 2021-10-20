<?php

namespace App\Http\Livewire\Companies\Profile\Edit\Events;

use App\Models\Event;
use App\Traits\SupportUiNotification;
use Livewire\Component;

class CompanyProfileEditApprovalEventsLoopComponent extends Component
{
    use SupportUiNotification;

    public $event;
    public $isRemoved = false;
    protected $listeners = [
        'removeEvent' => 'doRemove',
        'renderCompanyProfileEditApprovalEventsLoopComponent' => 'render',
        'refreshCompanyProfileEditApprovalEventsLoopComponent' => '$refresh',
    ];

    public function render()
    {
        return view('livewire.companies.profile.edit.events.company-profile-edit-approval-events-loop-component');
    }

    public function approve($id)
    {
        $event = Event::find($id);
        $event->update(['status' => 'enabled']);
        $this->alert()->success(['title' => 'Your event was approved']);
        $this->emit('companyProfileEditEventsComponent');
        $this->emit('updateDOM');
        $this->isRemoved = true;

    }

    public function decline($id)
    {
        $this->alert()->confirm([
            'icon' => 'warning',
            'title' => 'Do you want to remove ' . $this->event->name . '?' ,
            'confirmButtonText' => 'Yes',
            'method' => 'removeEvent',
            'params' => $id
        ]);
    }

    public function doRemove($id)
    {
        $event = Event::find($id);
        $event->delete();
        $this->alert()->success(['title' => 'Your Event was declined']);
        $this->emit('companyProfileEditEventsComponent');
        $this->emit('updateDOM');
        $this->isRemoved = true;

    }
}
