<?php

namespace App\Http\Livewire\Employee\Profile;

use App\Services\ActivityService;
use Livewire\Component;
use Livewire\WithPagination;

class ActivitiesComponent extends Component
{
	use WithPagination;
	public $activities ;

    public function render()
    {
        return view('livewire.employee.profile.activities-component');
    }

    public function mount()
    {
	    $this->activities = ActivityService::getAll();
    }
}
