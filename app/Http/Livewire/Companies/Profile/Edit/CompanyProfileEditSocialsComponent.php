<?php

namespace App\Http\Livewire\Companies\Profile\Edit;

use App\Services\EmployeeService;
use App\Traits\SupportUiNotification;
use Livewire\Component;

class CompanyProfileEditSocialsComponent extends Component
{
    use SupportUiNotification;

    public $instagram;
    public $twitter;
    public $facebook;
    public $linkedin;
    public $user;
    public $loading = false;
    public $company;

    public function render()
    {
        return view('livewire.companies.profile.edit.company-profile-edit-socials-component');
    }

    public function mount()
    {
        $user = EmployeeService::getUser();
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

        $user = $this->user;
        $user->update(['instagram' => $this->removeChars($this->instagram)]);

        $this->alert()->success([
            'title' => 'Instagram updated'
        ]);
    }

    public function updatedTwitter()
    {
        $this->validate([
            'twitter' => 'required',
        ]);

        $user = $this->user;
        $user->update(['twitter' => $this->removeChars($this->twitter)]);

        $this->alert()->success([
            'title' => 'Twitter updated'
        ]);
    }

    public function updatedFacebook()
    {
        $this->validate([
            'facebook' => 'required',
        ]);

        $user = $this->user;
        $user->update(['facebook' => $this->removeChars($this->facebook)]);

        $this->alert()->success([
            'title' => 'Facebook updated'
        ]);
    }

    public function updatedLinkedin()
    {
        $this->validate([
            'facebook' => 'required',
        ]);

        $user = $this->user;
        $user->update([ 'linkedin' => $this->removeChars($this->linkedin)]);

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
