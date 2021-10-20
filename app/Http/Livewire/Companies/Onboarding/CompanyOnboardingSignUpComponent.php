<?php

namespace App\Http\Livewire\Companies\Onboarding;

use App\Traits\SupportUiNotification;
use Livewire\Component;

class CompanyOnboardingSignUpComponent extends Component
{
    use SupportUiNotification;

    public $name;
    public $location;
    public $description;
    public $email;
    public $user;
    public $company;
    public $step = 'step1';

    public $rules = [
        'name' => 'required',
        'location' => 'required',
    ];

    public function render()
    {
        return view('livewire.companies.onboarding.company-onboarding-sign-up-component');
    }

    public function mount()
    {
        $this->user = auth()->user();
        $this->name = $this->user->company->name;
        $this->location = $this->user->company->location;
        $this->email = $this->user->email;
        $this->description = $this->user->company->description;
        $this->company = $this->user->company;
        $this->step = 'step1';
    }

    public function save()
    {
        $validator = \Validator::make([
           'name' => $this->name,
           'location' => $this->location,
        ], $this->rules);

        if($validator->fails()) {
            $this->alert()->error(['title' => 'Input fields are required']);
            $validator->validate();
        }
        $this->step = 'step2';
    }

    public function updatedName()
    {
        $this->validate([
            'name' => 'required',
        ]);

        $this->company->update(['name' => $this->name]);

        $this->alert()->success([
            'title' => 'Company Name updated'
        ]);
    }

    public function updatedEmail()
    {
        $this->validate([
            'email' => 'required|email:rfc,dns|unique:users,email,' . auth()->id(),
        ]);

        $this->user->update(['email' => $this->name]);

        $this->alert()->success([
            'title' => 'Company email updated'
        ]);
    }

    public function updatedLocation()
    {
        $this->validate([
            'location' => 'required',
        ]);

        $this->company->update(['location' => $this->location]);

        $this->alert()->success([
            'title' => 'Company Location updated'
        ]);
    }

    public function updatedDescription()
    {
        $this->validate([
            'description' => 'required|max:500',
        ]);

        $this->company->update(['description' => $this->description]);

        $this->alert()->success([
            'title' => 'Company description updated'
        ]);
    }
}
