<?php

namespace App\Http\Livewire\Companies\Profile\Edit;

use App\Services\CompanyService;
use App\Traits\SupportUiNotification;
use App\Traits\ValidatorErrorManagementTrait;
use Livewire\Component;
use Livewire\WithFileUploads;

class CompanyProfileEditAboutMessageComponent extends Component
{

    use WithFileUploads;
    use SupportUiNotification;
    use ValidatorErrorManagementTrait;

    public $about;
    public $title;
    public $link;
    public $user;
    public $company;
    public $cover;
    public $currentCover;

    protected $listeners = ['companyProfileEditAboutMessageComponent' => '$refresh'];

    public function render()
    {
        return view('livewire.companies.profile.edit.company-profile-edit-about-message-component');
    }

    public function mount()
    {
        $this->user = CompanyService::getUser();

        $this->company = $this->user->company;

        $this->about = $this->company->about;

        $this->title = $this->company->about_title;

        $this->link = $this->company->about_link;

        $this->currentCover = $this->company->cover->url ?? '';
    }

    public function updatedTitle()
    {
        $this->validate([
            'title' => 'required',
        ]);

        $this->company->update(['about_title' => $this->title]);

        $this->alert()->success(['title' => 'About Title Updated']);
    }

    public function updatedAbout()
    {
        $this->validate([
            'about' => 'required',
        ]);

        $this->company->update(['about' => $this->about]);

        $this->alert()->success(['title' => 'About Message Updated']);

    }

    public function updatedLink()
    {
        $this->validate([
            'link' => 'required',
        ]);

        $this->company->update(['about_link' => $this->link]);

        $this->alert()->success(['title' => 'About Link Updated']);

    }

    /**
     * Livewire Hooks, when a property updated by any interaction, in this case when choose an image.
     */
    public function updatedCover()
    {
        $validator = \Validator::make(['cover' => $this->cover], ['cover' => 'image|max:50240']);

        if($validator->fails()) {

            $message = $this->getErrorFromValidator($validator);

            $this->alert()->error(['title' => $message]);

            $validator->validate();

        }

        $file_name = 'about_picture_' . $this->company->id . '.' . $this->cover->getClientOriginalExtension();

        $this->company->addMedia($this->cover->getRealPath())->usingName($file_name)->toMediaCollection('about');

        $this->company = $this->company->refresh();

        $this->currentCover = $this->company->cover->url ?? '';

        $this->cover = $this->company->cover->url ?? '';

        $this->alert()->success(['title' => 'About Cover Image updated']);

        $this->emit('companyProfileEditAboutMessageComponent');


    }
}
