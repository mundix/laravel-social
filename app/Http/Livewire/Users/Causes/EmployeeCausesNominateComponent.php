<?php

namespace App\Http\Livewire\Users\Causes;

use App\Models\CategoryCause;
use App\Models\Cause;
use App\Services\EmployeeService;
use Livewire\Component;

class EmployeeCausesNominateComponent extends Component
{
	public $name, $description, $website, $status, $is_promoted, $email, $user;
	protected $listeners = ['nominatedCause' => 'saveCause', 'successCause', 'errorCause'];

	public function render()
	{
		return view('livewire.users.causes.nominate');
	}

	public function mount()
	{
		$this->user = EmployeeService::getUser();
		$this->email = $this->user->email;
	}

	public function saveCause()
	{
		$validatedDate = $this->validate([
			'name' => 'required',
			'description' => 'required',
			'email' => 'required|email:rfc,dns',
		]);

		$cause = new Cause();
		$data = [
			'name' => $this->name,
			'description' => $this->description,
			'is_promoted' => $this->is_promoted ? true : false,
			'email' => $this->email,
			'category_id' => CategoryCause::first()->id,
		];
		$cause->fill($data);
		$this->user->causes()->save($cause);
		session()->flash('success', 'Your cause was nominated.');
		$this->resetValues();
		$this->emit('nominatedCause');
		$this->emit('successCause', true);

	}

	private function resetValues()
	{
		$this->name = '';
		$this->description = '';
		$this->is_promoted = false;
	}


}
