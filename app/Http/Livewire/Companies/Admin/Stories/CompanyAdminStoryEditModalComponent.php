<?php

namespace App\Http\Livewire\Companies\Admin\Stories;

use App\Models\Employee;
use App\Models\Story;
use App\Traits\SupportUiNotification;
use App\Traits\ValidatorErrorManagementTrait;
use Livewire\Component;
use Livewire\WithFileUploads;

class CompanyAdminStoryEditModalComponent extends Component
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
        'CompanyAdminStoryEditModalComponent' => '$refresh'
    ];

    public $rules = [
        'title' => 'required|min:3|max:255',
        'content' => 'required|min:3|max:255'
    ];

    public function render()
    {
        return view('livewire.companies.admin.stories.company-admin-story-edit-modal-component');
    }

    public function setEdit($id)
    {
        $story = Story::find($id);
        $this->story = $story;
        $this->title = $story->title;
        $this->content = $story->content;
        $this->currentPicture = $this->story->picture->url ?? null;

        $this->emit('openEditStoryModal');

        if($this->story->status === 'draft') {
            $this->emit('closeRequestModal');
        }
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

        if($this->story->status === 'draft') {
            $this->emit('companyProfileEditApprovalStoriesComponent');
            $this->emit('renderCompanyProfileEditApprovalStoriesComponent');
        }else{
            $this->emit('companyAdminStoriesComponent');
            $this->emit('renderCompanyAdminStoriesComponent');
        }

        $this->alert()->success(['title' => 'Story  Picture Updated']);
        $this->emit('closeModals');
    }

    public function hydrate()
    {
        $this->emit('companyProfileEditStoriesComponent');
    }

    public function save()
    {
        $data = [
            'title' => $this->title,
            'content' => $this->content,
        ];

        $validator = \Validator::make($data, $this->rules);

        if ($validator->fails()) {
            $message = $this->getErrorFromValidator($validator);
            $this->alert()->error(['title' => $message]);
            $validator->validate();
        }

        $this->story->update($data);

        $this->alert()->success(['title' => 'The Story was updated']);

        if($this->story->status === 'draft') {
            $this->emit('companyProfileEditApprovalStoriesComponent');
            $this->emit('renderCompanyProfileEditApprovalStoriesComponent');
            $this->emit('openRequestModal');
        }else {
            $this->emit('companyAdminStoriesComponent');
            $this->emit('renderCompanyAdminStoriesComponent');
            $this->emit('CompanyAdminStoryLoopComponent');
            $this->emit('renderCompanyAdminStoryLoopComponent');
        }

    }

    public function deleteSponsor($sponsor)
    {
        $user = Employee::find($sponsor)->user;
        $this->story->sponsors()->detach($user->id);
        $this->alert()->success(['title' => 'Sponsor was removed']);

        $this->emit('CompanyProfileEditStoryUpdateModalComponent');
    }
}
