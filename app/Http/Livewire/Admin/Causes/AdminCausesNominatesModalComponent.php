<?php

namespace App\Http\Livewire\Admin\Causes;

use App\Models\CategoryCause;
use App\Models\Cause;
use App\Models\Nominate;
use App\Services\CauseService;
use App\Traits\SupportUiNotification;
use Livewire\Component;
use Livewire\WithPagination;

class AdminCausesNominatesModalComponent extends Component
{
    use WithPagination;
    use SupportUiNotification;

    public $sortField = 'name';
    public $sortAsc = true;
    public $pageName = 'nominatePages';

    protected $listeners = ['adminCausesNominatesModalComponent' => 'render', 'rejectNominate' => 'doReject'];

    public function render()
    {
        $nominates = CauseService::getNominatesByCompany(
            null,
            config('bondeed.frontend.dashboards.limit'),
            $this->sortField,
            $this->sortAsc
        );

        return view('livewire.admin.causes.admin-causes-nominates-modal-component', [
            'nominates' => $nominates
        ]);
    }

    /**
     * Approving and create a Cause
    */
    public function approved($id)
    {
        $nominate = Cause::find($id);

        if($nominate->status === 'nominate')
        {
            $nominate->update(['status' => 'approved']);
            $this->alert()->success(['title' =>'Your nominated cause was accepted']);
        }

        $this->emit('adminCausesComponent');

        $this->emit('updateDOM');
    }

    public function decline($id)
    {
        $this->alert()->confirm([
            'icon' => 'warning',
            'title' => 'Do you want to reject ?' ,
            'confirmButtonText' => 'Yes',
            'method' => 'rejectNominate',
            'params' => $id
        ]);
    }

    public function doReject($id)
    {
        $nominate = Cause::find($id);

        $nominate->update(['status' => 'rejected']);
        $this->alert()->success(['title' =>'Your nominated cause was declined']);
        $this->emit('adminCausesComponent');

        $this->emit('updateDOM');

    }

    public function paginationView()
    {
        return 'vendor.livewire.custom-pagination-foundation';
    }

    public function sortBy($field)
    {
        if ($this->sortField == $field) {
            $this->sortAsc = !$this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
        $this->emit('updateDOM');
    }

}
