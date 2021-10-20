<?php

namespace App\Http\Livewire\Employees\Profile\Edit;

use App\Traits\SupportUiNotification;
use Livewire\Component;

class EmployeeProfileEditSocialsComponent extends Component
{

    use SupportUiNotification;

    public $instagram;
    public $twitter;
    public $facebook;
    public $linkedin;
    public $user;

    public function render()
    {
        return view('livewire.employees.profile.edit.employee-profile-edit-socials-component');
    }

    public function mount($user)
    {
        $this->user = $user;
        $this->instagram = $user->instagram;
        $this->twitter = $user->twitter;
        $this->facebook = $user->facebook;
        $this->linkedin = $user->linkedin;
    }
    public function updatedInstagram()
    {
        $validatedDate = $this->validate([
            'instagram' => 'required',
        ]);

        $this->user->update(['instagram' => $this->removeChars($this->instagram)]);
        $this->alert()->success(['title' => 'instagram Saved']);
    }

    public function updatedTwitter()
    {
        $validatedDate = $this->validate([
            'twitter' => 'required',
        ]);

        $this->user->update(['twitter' => $this->removeChars($this->twitter)]);
        $this->alert()->success(['title' => 'instagram Saved']);
    }

    public function updatedFacebook()
    {
        $validatedDate = $this->validate([
            'facebook' => 'required',
        ]);

        $this->user->update(['facebook' => $this->removeChars($this->facebook)]);
        $this->alert()->success(['title' => 'facebook Saved']);
    }

    public function updatedLinkedin()
    {
        $validatedDate = $this->validate([
            'linkedin' => 'required',
        ]);

        $this->user->update(['linkedin' => $this->removeChars($this->linkedin)]);
        $this->alert()->success(['title' => 'linkedin Saved']);
    }

    private function removeChars($string = '')
    {
        $string = str_replace( '@', '', $string);
        return $string;
    }
}
