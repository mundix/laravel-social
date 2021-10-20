<?php

namespace App\Http\Livewire\Companies\Profile\Edit\Stories;

use App\Models\Story;
use App\Traits\SupportUiNotification;
use Livewire\Component;

class CompanyProfileEditStoryLoopComponent extends Component
{
    use SupportUiNotification;
    public $story;
    public $status;

    protected $listeners = [
        'CompanyProfileEditStoryLoopComponent' => '$refresh',
        'deleteStory' => 'doDelete'
    ];

    public function render()
    {
        return view('livewire.companies.profile.edit.stories.company-profile-edit-story-loop-component');
    }

    public function delete($id)
    {
        $this->alert()->confirm([
            'icon' => 'warning',
            'title' => 'Are you sure you want to delete this?' ,
            'confirmButtonText' => 'Yes',
            'method' => 'deleteStory',
            'params' => $this->story->id
        ]);
    }

    public function doDelete($id)
    {
        $obj = Story::find($id);
        if($obj) {
            $obj->delete();
        }

        $this->alert()->success(['title' => 'Story was deleted']);
        $this->emit('companyProfileEditStoriesComponent');
//        return redirect()->route('company.profile.edit');
    }
}
