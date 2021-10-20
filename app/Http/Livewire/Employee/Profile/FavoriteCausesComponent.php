<?php

namespace App\Http\Livewire\Employee\Profile;

use Livewire\Component;

class FavoriteCausesComponent extends Component
{
	public $user, $causes = [];

    public function render()
    {
        return view('livewire.employee.profile.favorite-causes-component');
    }

    public function mount()
    {
    	$this->user = auth()->user();
		$this->causes = collect($this->user->FavoriteCauses->get());
    }
}
