<?php

namespace App\Http\Livewire\Users;

use App\Services\EmployeeService;
use Livewire\Component;
use Livewire\WithFileUploads;

class UserOnboardingComponent extends Component
{
    use WithFileUploads;

    public $steps = ['step1', 'step2', 'step3'];
    public $step;
    public $currentStep = 0;
    public $first_name;
    public $last_name;
    public $location;
    public $description;
    public $email;
    public $user;
    public $picture_ready = false;
    public $cover_ready = false;

    protected $listeners = [
        'refreshComponent' => '$refresh',
        'pictureReady' => 'isPictureReady',
        'coverReady' => 'isCoverReady'
    ];

    public function render()
    {
        return view('livewire.users.onboarding')
            ->layout('layouts.base',
                [
                    'extraClass' => 'User UserOnboarding',
                    'siteSection' => 'User Onboarding'
                ]
            );
    }

    /**
     * Hook method for initialize
     * */
    public function mount()
    {
        $user = EmployeeService::getUser();
        $this->user = $user;
        $this->first_name = $user->employee->first_name;
        $this->last_name = $user->employee->last_name;
        $this->location = $user->employee->location;
        $this->description = $user->employee->description;
        $this->email = $user->email;
        $this->step = $this->steps[0];
    }

    public function nextStep()
    {
        if ($this->currentStep < sizeof($this->steps) - 1) {
            $this->step = $this->steps[++$this->currentStep];
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 0) {
            $this->step = $this->steps[--$this->currentStep];
        }
    }

    public function isPictureReady($ready)
    {
        $this->picture_ready = $ready;
    }

    public function isCoverReady($ready)
    {
        $this->cover_ready = $ready;
    }

    public function saveProfileInfo()
    {
        $validatedDate = $this->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'location' => 'required',
            'email' => 'required|email:rfc,dns|unique:users,email,' . auth()->id(),
        ]);

        $user = $this->user;

        $user->email = $this->email;

        $data = [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'location' => $this->location,
            'description' => $this->description,
        ];

        $user->employee()->update($data);

        $this->user = $user;

        $this->nextStep();
    }

    public function doSkip()
    {
        $this->nextStep();
    }

    public function updateGallery()
    {
        $this->nextStep();
    }

    public function completeProfile()
    {
        $this->user->update(['on_boarding_complete' => true, 'status' => 'active']);
        return redirect()->route('users.profile');
    }

}
