<?php

namespace App\Http\Livewire\Companies\Onboarding;

use App\Services\CompanyService;
use App\Traits\SupportUiNotification;
use Livewire\Component;
use Livewire\WithFileUploads;

class CompanyOnboardingBasicDetailsComponent extends Component
{
    use WithFileUploads;
    use SupportUiNotification;

    public $steps = [ 'step1', 'step2', 'step3',], $step, $currentStep = 0;
    public $name;
    public $location;
    public $description;
    public $email;
    public $user;
    public $company;
    public $picture_ready = false, $cover_ready = false;

    protected $listeners = [
        'refreshComponent' => '$refresh',
        'pictureReady' => 'isPictureReady',
        'coverReady' => 'isCoverReady'
    ];


    public function render()
    {
        return view('livewire.companies.onboarding.company-onboarding-basic-details-component')
            ->layout('layouts.base',
                [
                    'extraClass' => 'UserOnboarding CompanyOnboarding',
                    'siteSection' => 'User Onboarding'
                ]
            );
    }

    /**
     * Hook method for initialize
     * */
    public function mount()
    {
        $user = CompanyService::getUser();
        $this->company = $user->company;
        $this->user = $user;
        $this->name = $user->company->name;
        $this->location = $user->company->location;
        $this->description = $user->company->description;
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
        $validator = \Validator::make([
            'name' => $this->name,
            'email' => $this->email,
            'location' => $this->location,
        ], [
            'name' => 'required|min:3',
            'email' => 'required|email:rfc,dns',
            'location' => 'required'
        ]);
        if ($validator->fails()) {
            $this->alert()->error(['title' => 'Please check required fields to continue']);
            return;
        }
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
        $this->user->update(['on_boarding_complete' => true]);
        return redirect()->route('company.profile');
    }
}
