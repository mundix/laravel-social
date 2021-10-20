<?php

namespace App\Http\Livewire\Companies\Admin\Events;

use App\Models\Event;
use App\Traits\SupportUiNotification;
use Livewire\Component;

class CompanyAdminEventLoopComponent extends Component
{
    use SupportUiNotification;

    public $event;
    public $status;
    public $toggleStatusLabel;

    protected $listeners = [
        'CompanyProfileEditEventLoopComponent' => '$refresh',
        'renderCompanyProfileEditEventLoopComponent' => 'render',
        'deleteEvent' => 'doDelete',
        'disableEvent' => 'doDisable',
        'enableEvent' => 'doEnable',
    ];

    public function render()
    {
        return view('livewire.companies.admin.events.company-admin-event-loop-component');
    }

    public function mount()
    {
        $this->setStatus();
    }

    public function delete()
    {
        $this->alert()->confirm([
            'icon' => 'warning',
            'title' => 'Are you sure you want to delete this?',
            'confirmButtonText' => 'Yes',
            'method' => 'deleteEvent',
            'params' => $this->event->id
        ]);
    }

    public function doDelete($id)
    {
        $obj = Event::find($id);
        if ($obj) {
            $obj->delete();
        }
        $this->alert()->success(['title' => 'Event was deleted']);
        $this->emit('companyAdminEventsDashboardComponent');
    }

    public function setStatus()
    {
        $this->toggleStatusLabel = $this->event->status === 'enabled' ? 'Disable' : 'Enable';
        $this->status = $this->event->status;
    }

    public function setDisable($id)
    {
        $this->alert()->confirm([
            'icon' => 'warning',
            'title' => 'Are you sure you want to disable this Event?',
            'confirmButtonText' => 'Disable',
            'method' => 'disableEvent',
            'params' => $id
        ]);
    }

    public function doDisable($id)
    {
        $obj = Event::find($id);
        if ($obj) {
            $obj->update(['status' => 'pending']);
            $this->alert()->success(['title' => 'Event Disabled']);
        }
        $this->setStatus();

        $this->emit('companyProfileEditEventsComponent');
        $this->emit('companyAdminEventsDashboardComponent');
    }

    public function setEnable($id)
    {
        $this->alert()->confirm([
            'icon' => 'warning',
            'title' => 'Are you sure you want to enable this Event?',
            'confirmButtonText' => 'Enable',
            'method' => 'enableEvent',
            'params' => $id
        ]);
    }

    public function doEnable($id)
    {
        $obj = Event::find($id);
        if ($obj) {
            $obj->update(['status' => 'enabled']);
            $this->alert()->success(['title' => 'Event Enabled']);
        }
        $this->setStatus();

        $this->emit('companyProfileEditEventsComponent');
        $this->emit('companyAdminEventsDashboardComponent');
    }

}
