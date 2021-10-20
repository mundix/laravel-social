<?php

namespace App\Http\Livewire\Companies\Admin\Causes;

use App\Models\Cause;
use App\Models\Story;
use App\Traits\SupportUiNotification;
use Livewire\Component;

class CompanyAdminCauseLoopComponent extends Component
{
    use SupportUiNotification;
    public $cause;
    public $matchable;
    public $status;
    public $favorite;
    public $user;

    protected $listeners = [
        'companyAdminCauseLoopComponent' => 'render',
        'refreshCompanyAdminCauseLoopComponent' => '$refresh',
        'disableCause' => 'doDisable',
        'enableCause' => 'doEnable',
    ];

    public function render()
    {
        $this->favorite = $this->cause->isFavoritedBy($this->user);
        return view('livewire.companies.admin.causes.company-admin-cause-loop-component');
    }

    public function mount()
    {
        $this->user = auth()->user();
    }

    public function setStatus($status )
    {
        $this->cause->update(['status' => $status]);
        $this->alert()->success(['title'=> 'Cause '. $status]);
        $this->emit('refreshCompanyAdminCausesComponent');
        $this->emit('renderCompanyAdminCausesComponent');
    }

    public function toggleMatchable()
    {
        $matchable = !$this->cause->matchable;
        $this->cause->update(['matchable' => $matchable]);
        $this->alert()->success(['title'=>  (int) $matchable ?  'This Cause is now Matchable' : 'This Cause is now Unmatchable']);
    }

    public function toggleFavorite()
    {
        $this->user->toggleFavorite($this->cause);
        $this->favorite = $this->cause->isFavoritedBy($this->user);
        $status = $this->favorite ? 'Unfavorited' : 'Favorited';
        $this->emit('companyAdminCauseLoopComponent');
        $this->alert()->success(['title' => 'You\'ve  ' . $status . ' this Cause' ]);
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
        $this->emit('refreshCompanyAdminCausesComponent');
        $this->emit('renderCompanyAdminCausesComponent');
        $this->emit('companyAdminCauseLoopComponent');
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
        $this->emit('refreshCompanyAdminCausesComponent');
        $this->emit('renderCompanyAdminCausesComponent');
        $this->emit('companyAdminCauseLoopComponent');
    }
}
