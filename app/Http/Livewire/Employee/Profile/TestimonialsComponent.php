<?php

namespace App\Http\Livewire\Employee\Profile;

use App\Services\EmployeeService;
use Livewire\Component;
use Livewire\WithPagination;

class TestimonialsComponent extends Component
{

	use WithPagination;
	public $user, $testimonials = [], $company;

    public function render()
    {
        return view('livewire.employee.profile.testimonials-component');
    }
	public function mount()
	{
		$this->user = auth()->user();
		$this->company = EmployeeService::getCompany();
		$this->testimonials = collect($this->company->testimonials)->shuffle()->take(10);
	}
}
