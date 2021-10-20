<?php

namespace App\Http\Livewire\Users;

use App\Services\EmployeeService;
use Livewire\Component;

class EmployeeProfileInfoComponent extends Component
{
	public $loading = false;
	public $first_name, $last_name, $location, $description, $email, $user;

	public function render()
	{
		return view('livewire.users.profile.info');
	}

	public function mount()
	{
		$user = EmployeeService::getUser();
		$this->user = $user;
		$this->first_name = $user->employee->first_name;
		$this->last_name = $user->employee->last_name;
		$this->location = $user->employee->location;
		$this->description = $user->employee->description;
		$this->email = $user->email;
	}

	public function startLoading()
	{
		$this->loading = true;
	}

	public function updatedFirstName()
	{
		$validatedDate = $this->validate([
			'first_name' => 'required',
		]);

		$user = $this->user;
		$user->employee()->update(['first_name' => $this->first_name]);
		$this->loading = false;
		session()->flash('success', 'First Name Saved');
	}

	public function updatedLastName()
	{
		$validatedDate = $this->validate([
			'last_name' => 'required',
		]);

		$user = $this->user;
		$user->employee()->update(['last_name' => $this->last_name]);
		$this->loading = false;
		session()->flash('success', 'Last Name Saved');
	}

	public function updatedLocation()
	{
		$validatedDate = $this->validate([
			'location' => 'required',
		]);

		$user = $this->user;
		$user->employee()->update(['location' => $this->location]);
		$this->loading = false;
		session()->flash('success', 'Location Saved');
	}

	public function updatedEmail()
	{
		$validatedDate = $this->validate([
			'email' => 'required|email:rfc,dns|unique:users,email,' . auth()->id(),
		]);

		$user = $this->user;
		$user->update(['email' => $this->email]);
		$this->loading = false;
		session()->flash('success', 'Email Saved');
	}

	public function updatedDescription()
	{
		$validatedDate = $this->validate([
			'description' => 'required|max:500',
		]);

		$user = $this->user;
		$user->employee()->update(['description' => $this->description]);
		$this->loading = false;
		session()->flash('success', 'Description Saved');
	}


}
