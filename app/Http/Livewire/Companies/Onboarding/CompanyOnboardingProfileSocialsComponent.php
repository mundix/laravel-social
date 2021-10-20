<?php

namespace App\Http\Livewire\Companies\Onboarding;

use App\Traits\SupportUiNotification;
use Livewire\Component;

class CompanyOnboardingProfileSocialsComponent extends Component
{
    use SupportUiNotification;

    public $company;
    public $instagram;
    public $twitter;
    public $facebook;
    public $linkedin;
    public $user;

    public function render()
    {
        return view('livewire.companies.onboarding.company-onboarding-profile-socials-component');
    }

    public function mount()
    {
        $user = $this->company->user;
        $this->user = $user;
        $this->instagram = $user->instagram;
        $this->twitter = $user->twitter;
        $this->facebook = $user->facebook;
        $this->linkedin = $user->linkedin;
    }

    public function updatedInstagram()
    {
        $this->validate([
            'instagram' => 'required',
        ]);

        $this->user->update(['instagram' => $this->removeChars($this->instagram)]);

        $this->alert()->success([
            'title' => 'Instagram updated'
        ]);
    }

    public function updatedTwitter()
    {
        $this->validate([
            'twitter' => 'required',
        ]);

        $this->user->update(['twitter' => $this->removeChars($this->twitter)]);

        $this->alert()->success([
            'title' => 'Twitter updated'
        ]);
    }

    public function updatedFacebook()
    {
        $this->validate([
            'facebook' => 'required',
        ]);

        $this->user->update(['facebook' => $this->removeChars($this->facebook)]);

        $this->alert()->success([
            'title' => 'Facebook updated'
        ]);
    }

    public function updatedLinkedin()
    {
        $this->validate([
            'facebook' => 'required',
        ]);

        $this->user->update([ 'linkedin' => $this->removeChars($this->linkedin)]);

        $this->alert()->success([
            'title' => 'Linkedin updated'
        ]);
    }

    private function removeChars($string = '')
    {
        $string = str_replace( '@', '', $string);
        return $string;
    }
}
