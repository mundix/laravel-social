<?php

namespace App\Http\Livewire\Companies\Profile\Edit\Stories;

use App\Models\Employee;
use App\Models\Story;
use App\Traits\SupportUiNotification;
use App\Traits\ValidatorErrorManagementTrait;
use Livewire\Component;
use Livewire\WithFileUploads;

class CompanyProfileEditStoryUpdateModalComponent extends Component
{

    use WithFileUploads;
    use ValidatorErrorManagementTrait;
    use SupportUiNotification;

    public $picture;
    public $currentPicture;
    public $title;
    public $content;
    public $company;
    public $story;
    public $sponsors;

    protected $listeners = [
        'setCompanyProfileEditStoryUpdateModalComponent' => 'setEdit',
        'CompanyProfileEditStoryUpdateModalComponent' => '$refresh'
    ];

    public $rules = [
        'picture' => 'image',
        'title' => 'required',
        'content' => 'required'
    ];

    public function render()
    {
        return view('livewire.companies.profile.edit.stories.company-profile-edit-story-update-modal-component');
    }

    public function setEdit($id)
    {
        $story = Story::find($id);

        $this->story = $story;

        $this->title = $story->title;

        $this->content = $story->content;

        $this->currentPicture = $this->story->picture->url ?? null;

        $this->emit('openEditStoryModal');
    }

    /**
     * Livewire Hooks, when a property updated by any interaction, in this case when choose an image.
     */
    public function updatedPicture()
    {
        $validator = \Validator::make(['picture' => $this->picture], ['picture' => 'image|max:102400']);

        if ($validator->fails()) {

            $message = $this->getErrorFromValidator($validator);

            $this->alert()->error(['title' => $message]);

            $validator->validate();
        }

        $file_name = 'story_picture_' . $this->story->id . '.' . $this->picture->getClientOriginalExtension();

        $this->story->addMedia($this->picture->getRealPath())->usingName($file_name)->toMediaCollection('picture');

        $this->story = $this->story->refresh();

        $this->currentPicture = $this->story->picture->url;

        $this->alert()->success(['title' => 'Story  Picture Updated']);
    }

    public function updatedTitle($value)
    {
        $validator = \Validator::make(['title' => $this->title], ['title' => 'required|min:3|max:255']);

        if ($validator->fails()) {

            $message = $this->getErrorFromValidator($validator);

            $this->alert()->error(['title' => $message]);

            $validator->validate();
        }

        $this->story->update(['title' => $value]);

        $this->alert()->success(['title' => 'Story title was updated']);
    }

    public function updatedContent($value)
    {
        $validator = \Validator::make(['content' => $this->content], ['content' => 'required|min:3|max:255']);

        if ($validator->fails()) {

            $message = $this->getErrorFromValidator($validator);

            $this->alert()->error(['title' => $message]);

            $validator->validate();
        }

        $this->story->update(['content' => $value]);

        $this->alert()->success(['title' => 'Event Content updated']);
    }

    public function hydrate()
    {
        $this->emit('companyProfileEditStoriesComponent');
    }

    public function save()
    {
        return redirect()->route('company.admin.index');
    }

    public function deleteSponsor($sponsor)
    {
        $user = Employee::find($sponsor)->user;
        $this->story->sponsors()->detach($user->id);
        $this->alert()->success(['title' => 'Sponsor was removed']);
        $this->emit('CompanyProfileEditStoryUpdateModalComponent');
    }
}
