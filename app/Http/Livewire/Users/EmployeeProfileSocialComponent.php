<?php

namespace App\Http\Livewire\Users;

use App\Services\EmployeeService;
use Livewire\Component;

class EmployeeProfileSocialComponent extends Component
{
    public $instagram, $twitter, $facebook, $linkedin, $user, $loading = false;

    public function render()
    {
        return view('livewire.users.profile.social');
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

    public function startLoading()
    {
        $this->loading = true;
    }

    public function updatedInstagram()
    {
        $validatedDate = $this->validate([
            'instagram' => 'required',
        ]);

        $user = $this->user;
        $user->update(['instagram' => $this->removeChars($this->instagram)]);
        $this->loading = false;
        session()->flash('success', 'Instagram Saved');
    }

    public function updatedTwitter()
    {
        $validatedDate = $this->validate([
            'twitter' => 'required',
        ]);

        $user = $this->user;
        $user->update(['twitter' => $this->removeChars($this->twitter)]);
        $this->loading = false;
        session()->flash( 'success', 'Twitter Saved');
    }

    public function updatedFacebook()
    {
        $validatedDate = $this->validate([
            'facebook' => 'required',
        ]);

        $user = $this->user;
        $user->update(['facebook' => $this->removeChars($this->facebook)]);
        $this->loading = false;
        session()->flash('success', 'Facebook Saved');
    }

    public function updatedLinkedin()
    {
        $validatedDate = $this->validate([
            'facebook' => 'required',
        ]);

        $user = $this->user;
        $user->update([ 'linkedin' => $this->removeChars($this->linkedin)]);
        $this->loading = false;
        session()->flash( 'success', 'Linkedin Saved');
    }

    private function removeChars($string = '')
    {
        $string = str_replace( '@', '', $string);
        return $string;
    }
}
