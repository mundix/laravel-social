<?php

namespace App\Http\Livewire\Companies\Profile\Edit\Involvements;

use App\Services\InvolvementService;
use Livewire\Component;

class CompanyProfileEditInvolvementsCreateModalComponent extends Component
{
    public $company;
    public $searchQuery = '';
    public $sortField = 'causes.name';
    public $sortAsc = true;
    public $sortOrder = 'asc';

    protected $listeners = ['refreshCompanyProfileEditInvolvementsCreateModalComponent' => 'setValues'];

    public function render()
    {
        $involvement  = new InvolvementService();
        $involvements = $involvement->getCompanyInvolvements($this->company, $this->searchQuery, $this->sortField, $this->sortOrder);
        return view('livewire.companies.profile.edit.involvements.company-profile-edit-involvements-create-modal-component')
            ->with(['involvements' => $involvements]);
    }

    public function sortBy($field)
    {
        if ($this->sortField == $field) {
            $this->sortAsc = !$this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        if($this->sortAsc) {
            $this->sortOrder = 'asc';
        }else{
            $this->sortOrder = 'desc';
        }

        $this->sortField = $field;
        $this->emit('updateDOM');
    }
}
