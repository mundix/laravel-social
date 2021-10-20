<?php

namespace App\Http\Livewire\Companies\Onboarding;

use App\Services\CompanyService;
use App\Traits\SupportUiNotification;
use App\Traits\ValidatorErrorManagementTrait;
use Livewire\Component;
use Livewire\WithFileUploads;

class CompanyOnboardingProfilePictureComponent extends Component
{
    use WithFileUploads;
    use SupportUiNotification;
    use ValidatorErrorManagementTrait;

    public $picture;
    public $user;

    protected $listeners = ['companyOnboardingProfilePictureComponent' => '$refresh'];

    public function render()
    {
        return view('livewire.companies.onboarding.company-onboarding-profile-picture-component');
    }

    public function mount()
    {
        $user = CompanyService::getUser();
        $this->user = $user;
    }

    /**
     * Livewire Hooks, when a property updated by any interaction, in this case when choose an image.
     */
    public function updatedPicture()
    {
        $validator = \Validator::make(['picture' => $this->picture], ['picture' => 'image|max:'. config('bondeed.uploads.limits.size'),]);

        if($validator->fails()) {
            $message = $this->getErrorFromValidator($validator);
            $this->alert()->error(['title' => $message]);
            $validator->validate();
        }

        $user = $this->user;
        $company = $user->company;

        $file_name = 'picture_' . $user->id . '.' . $this->picture->getClientOriginalExtension();

        $company->addMedia($this->picture->getRealPath())->usingName($file_name)->toMediaCollection('profile');
        $company = $company->refresh();
        $this->picture = $company->profile->url;

        $this->alert()->success(['title' => 'Your profile picture was uploaded.']);
        $this->emit('pictureReady', true);
        $this->emit('companyOnboardingProfilePictureComponent');

    }
}
