<?php

namespace App\Http\Livewire\Causes;

use App\Models\Cause;
use App\Services\CauseService;
use App\Services\EmployeeService;
use Livewire\Component;
use Livewire\WithPagination;


class FavoriteCauses extends Component
{
	use WithPagination;
	public $company;
	public $searchQuery;
	public $category;

	public function render()
	{
        $user = auth()->user();
        $causes= $this->company->user->causes;
		return view('livewire.causes.favorite-causes', [
			'causes' => $causes,
			'user' => $user,
			'categories' => CauseService::getCategories()
		]);
	}

}
