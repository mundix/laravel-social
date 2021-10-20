<?php

namespace App\Http\Livewire\Admin\Causes;

use App\Models\Cause;
use App\Traits\SupportUiNotification;
use Livewire\Component;

class AdminCauseLoopComponent extends Component
{
    use SupportUiNotification;

    public $cause;

    protected $listeners = [
        'renderAdminCauseLoopComponent' => 'render',
        'refreshAdminCauseLoopComponent' => '$refresh',
        'disableCause' => 'doDisable',
        'enableCause' => 'doEnable',
        'deleteCause' => 'doDelete'
    ];

    public function render()
    {
        return view('livewire.admin.causes.admin-cause-loop-component');
    }

    public function setDisable($id)
    {
        $this->alert()->confirm([
            'icon' => 'warning',
            'title' => 'Are you sure you want to disable this Cause?',
            'confirmButtonText' => 'Disable',
            'method' => 'disableCause',
            'params' => $id
        ]);
    }

    public function doDisable($id)
    {
        $obj = Cause::find($id);
        if ($obj) {
            $obj->update(['status' => 'pending']);
            $this->alert()->success(['title' => 'Cause Disabled']);
        }
       $this->refreshComponent();
    }

    public function setEnable($id)
    {
        $this->alert()->confirm([
            'icon' => 'warning',
            'title' => 'Are you sure you want to enable this Cause?',
            'confirmButtonText' => 'Enable',
            'method' => 'enableCause',
            'params' => $id
        ]);
    }

    public function doEnable($id)
    {
        $obj = Cause::find($id);
        if ($obj) {
            $obj->update(['status' => 'approved']);
            $this->alert()->success(['title' => 'Cause Enabled']);
        }
        $this->refreshComponent();
    }

    public function delete($id)
    {
        $this->alert()->confirm([
            'icon' => 'warning',
            'title' => 'Are you sure you want to delete this Cause?',
            'confirmButtonText' => 'Delete',
            'method' => 'deleteCause',
            'params' => $id
        ]);
    }

    public function doDelete($id)
    {
        $obj = Cause::find($id);
        if ($obj) {
            $obj->delete();
            $this->alert()->success(['title' => 'Cause Deleted']);
        }
        $this->refreshComponent();
    }

    private function refreshComponent()
    {
        $this->emit('renderAdminCauseLoopComponent');
        $this->emit('refreshAdminCauseLoopComponent');
        $this->emit('adminCausesComponent');
        $this->emit('renderAdminCausesComponent');
    }
}
