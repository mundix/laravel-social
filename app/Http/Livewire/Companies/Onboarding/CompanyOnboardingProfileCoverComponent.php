<?php

namespace App\Http\Livewire\Companies\Onboarding;

use App\Services\CompanyService;
use App\Traits\SupportUiNotification;
use App\Traits\ValidatorErrorManagementTrait;
use Livewire\Component;
use Livewire\WithFileUploads;

class CompanyOnboardingProfileCoverComponent extends Component
{
    use WithFileUploads;
    use SupportUiNotification;
    use ValidatorErrorManagementTrait;

    public $cover;
    public $user;

    protected $listeners = ['companyOnboardingProfileCoverComponent' => '$refresh'];


    public function render()
    {
        return view('livewire.companies.onboarding.company-onboarding-profile-cover-component');
    }

    public function mount()
    {
        $user = CompanyService::getUser();
        $this->user = $user;
    }

    public function updatedCover()
    {
        $validator = \Validator::make(['cover' => $this->cover], ['cover' => 'image|max:'. config('bondeed.uploads.limits.size'),]);

        if($validator->fails()) {
            $message = $this->getErrorFromValidator($validator);
            $this->alert()->error(['title' => $message]);
            $validator->validate();
        }

        $user = $this->user;
        $company = $user->company;

        $file_name = 'cover_' . $user->id . ' . ' . $this->cover->getClientOriginalExtension();

        $company->addMedia($this->cover->getRealPath())->usingName($file_name)->toMediaCollection('background');
        $company = $company->refresh();
        $this->cover = $company->background->url;

        $this->alert()->success(['title' => 'Your cover picture was uploaded.']);
        $this->emit('companyOnboardingProfileCoverComponent');
        $this->emit('coverReady', true);
    }
}
