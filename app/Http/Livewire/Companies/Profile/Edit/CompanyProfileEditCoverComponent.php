<?php

namespace App\Http\Livewire\Companies\Profile\Edit;

use App\Services\CompanyService;
use App\Traits\SupportUiNotification;
use App\Traits\ValidatorErrorManagementTrait;
use Livewire\Component;
use Livewire\WithFileUploads;

class CompanyProfileEditCoverComponent extends Component
{
    use WithFileUploads;
    use SupportUiNotification;
    use ValidatorErrorManagementTrait;

    protected $listeners = ['companyProfileEditCoverComponent' => '$refresh'];

    public $company;
    public $cover;
    public $currentCover;
    public $user;

    public function render()
    {
        return view('livewire.companies.profile.edit.company-profile-edit-cover-component');
    }

    public function mount()
    {
        $this->currentCover = $this->company->background->url;
    }

    /**
     * Livewire Hooks, when a property updated by any interaction, in this case when choose an image.
     */
    public function updatedCover()
    {
        $validator = \Validator::make(['cover' => $this->cover], ['cover' => 'image|max:'. config('bondeed.uploads.limits.size'),]);

        if($validator->fails()) {
            $message = $this->getErrorFromValidator($validator);
            $this->alert()->error(['title' => $message]);
            $validator->validate();
        }

        $file_name = 'background_' . $this->company->id . '.' . $this->cover->getClientOriginalExtension();

        $this->company->addMedia($this->cover->getRealPath())->usingName($file_name)->toMediaCollection('background');

        $this->company = $this->company->refresh();

        $this->currentCover = $this->company->background->url;

        $this->cover = $this->company->background->url;

        $this->emit('companyProfileEditPictureComponent');

    }

}
