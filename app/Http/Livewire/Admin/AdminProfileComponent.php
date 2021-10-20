<?php

namespace App\Http\Livewire\Admin;

use App\Traits\SupportUiNotification;
use App\Traits\ValidatorErrorManagementTrait;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdminProfileComponent extends Component
{

    use WithFileUploads;
    use SupportUiNotification;
    use ValidatorErrorManagementTrait;

    public  $user;
    public  $email;
    public  $phone;
    public  $picture;
    public  $currentPicture;
    public  $first_name;
    public  $last_name;
    public  $password;
    public  $loading = false;

    public function render()
    {
        return view('livewire.admin.admin-profile-component');
    }

    public function mount()
    {
        $user = auth()->user();
        $this->user = $user;
        $this->first_name = $user->admin->first_name ?? '';
        $this->last_name = $user->admin->last_name ?? '';
        $this->phone = $user->admin->phone ?? '';
        $this->email = $user->email ?? '';
        $this->currentPicture = $user->admin->profile->url ?? null;
    }

    public function save($data)
    {
        $validator = \Validator::make($data, [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'numeric|min:9|digits_between:9,11',
            'password' => 'confirmed|min:8',
        ]);

        if($validator->fails()){
            $message = $this->getErrorFromValidator($validator);
            $this->alert()->error(['title' => $message]);
            $validator->validate();
        }

        $this->user->admin->update([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
        ]);
        if(!empty($data['password'])) {
            $this->user->update([
                'password' => \Hash::make($data['password'])
            ]);
        }
        $this->alert()->success(['title' => 'Profile Updated'], 'alert');
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

        $file_name = 'profile_picture_' . $this->user->id . '.' . $this->picture->getClientOriginalExtension();

        $this->user->admin->addMedia($this->picture->getRealPath())->usingName($file_name)->toMediaCollection('picture');
        $this->user->admin = $this->user->admin->refresh();
        $this->currentPicture = $this->user->admin->profile->url;
        $this->alert()->success(['title' => 'Your profile picture was updated']);
        $this->emit('adminCausesComponent');
    }
}
