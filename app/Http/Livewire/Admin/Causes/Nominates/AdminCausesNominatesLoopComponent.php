<?php

namespace App\Http\Livewire\Admin\Causes\Nominates;

use App\Models\CategoryCause;
use App\Models\Cause;
use App\Models\Nominate;
use App\Traits\SupportUiNotification;
use Livewire\Component;

class AdminCausesNominatesLoopComponent extends Component
{
    use SupportUiNotification;
    public $nominate;
    public $status;

    protected $listeners = ['adminCausesNominatesLoopComponent' => '$refresh'];

    public function render()
    {
        return view('livewire.admin.causes.nominates.admin-causes-nominates-loop-component');
    }

    public function mount()
    {
        $this->status = $this->nominate->status;
    }


}
