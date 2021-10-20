<?php

namespace App\Http\Livewire\Users;

use App\Services\EmployeeService;
use Livewire\Component;
use Livewire\WithFileUploads;

class EmployeeProfilePictureComponent extends Component
{
    use WithFileUploads;

    public $profile_picture , $user;

    public function render()
    {
        return view('livewire.users.profile.picture');
    }

    public function mount()
    {
        $user = EmployeeService::getUser();
        $this->user = $user;
        $this->profile_picture = EmployeeService::getProfilePicture();
    }

    /**
     * Livewire Hooks, when a property updated by any interaction, in this case when choose an image.
     */
    public function updatedProfilePicture()
    {
        $this->validate([
            'profile_picture' => 'image|max:1024',
        ]);
        $user = $this->user;
        $employee = $user->employee;

        $profile_file_name  = 'profile_picture_' . $user->id . '.' .$this->profile_picture->getClientOriginalExtension();

        $employee->addMedia($this->profile_picture->getRealPath())->usingName($profile_file_name)->toMediaCollection('profile');
        $this->profile_picture = $employee->profile->url;

        session()->flash('success',trans('users.onboarding.profile.profile_picture.success'));

    }
}
