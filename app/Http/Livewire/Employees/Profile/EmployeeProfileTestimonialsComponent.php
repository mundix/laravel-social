<?php

namespace App\Http\Livewire\Employees\Profile;

use Livewire\Component;

class EmployeeProfileTestimonialsComponent extends Component
{

    public $company;
    public $employee;

    public function render()
    {
        return view('livewire.employees.profile.employee-profile-testimonials-component', [
            'testimonials' => $this->employee->user->testimonials
        ]);
    }

    public function mount($company = null , $employee = null )
    {
        $this->company = $company;
        $this->employee = $employee;
    }
}
