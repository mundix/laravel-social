<?php

namespace App\Http\Livewire\Employees\Profile\Edit\Involvements;

use App\Services\InvolvementService;
use App\Traits\SupportUiNotification;
use Livewire\Component;

class EmployeeProfileEditInvolvementComponent extends Component
{

    public $employee;
    public $searchQuery = '';
    public $sortField = 'causes.name';
    public $sortAsc = true;
    public $sortOrder = 'asc';

    public function render()
    {
        $involvement  = new InvolvementService($this->employee);
        $involvements = $involvement->getEmployeeInvolvement($this->searchQuery, $this->sortField, $this->sortOrder);
        return view('livewire.employees.profile.edit.involvements.employee-profile-edit-involvement-component')
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
