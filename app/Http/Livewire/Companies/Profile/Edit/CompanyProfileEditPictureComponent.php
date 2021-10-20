<?php

namespace App\Http\Livewire\Companies\Profile\Edit;

use App\Services\CompanyService;
use App\Traits\SupportUiNotification;
use App\Traits\ValidatorErrorManagementTrait;
use Livewire\Component;
use Livewire\WithFileUploads;

class CompanyProfileEditPictureComponent extends Component
{
    use WithFileUploads;
    use SupportUiNotification;
    use ValidatorErrorManagementTrait;

    protected $listeners = ['companyProfileEditPictureComponent' => '$refresh'];

    public $picture;
    public $currentPicture;
    public $company;

    public function render()
    {
        return view('livewire.companies.profile.edit.company-profile-edit-picture-component');
    }

    public function mount()
    {
        $this->currentPicture = $this->company->profile->url ?? null;
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

        $file_name = 'profile_' . $this->company->id . '.' . $this->picture->getClientOriginalExtension();

        $this->company->addMedia($this->picture->getRealPath())->usingName($file_name)->toMediaCollection('profile');

        $this->company = $this->company->refresh();

        $this->currentPicture = $this->company->profile->url;

        $this->alert()->success(['title' => 'Company Profile Picture was updated']);

        $this->emit('companyProfileEditPictureComponent');

    }
}
