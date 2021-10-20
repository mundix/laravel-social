<?php

namespace App\Http\Livewire\Companies\Admin\Stories;

use App\Models\Story;
use App\Traits\SupportUiNotification;
use Livewire\Component;

class CompanyAdminStoryLoopComponent extends Component
{
    use SupportUiNotification;

    public $story;
    public $status;

    protected $listeners = [
        'CompanyAdminStoryLoopComponent' => '$refresh',
        'renderCompanyAdminStoryLoopComponent' => 'render',
        'deleteStory' => 'doDelete',
        'pendingStory' => 'setPending',
        'publishStory' => 'setPublish',
    ];

    public function render()
    {
        return view('livewire.companies.admin.stories.company-admin-story-loop-component');
    }

    public function pending($id)
    {
        $this->alert()->confirm([
            'icon' => 'warning',
            'title' => 'Are you sure you want to disable this?',
            'confirmButtonText' => 'Disable',
            'method' => 'pendingStory',
            'params' => $id
        ]);
    }

    public function setPending($id)
    {
        $obj = Story::find($id);
        if ($obj) {
            $obj->update(['status' => 'pending']);
            $this->status = 'pending';
            $this->alert()->success(['title' => 'Story disabled!']);
        }
        $this->emit('companyAdminStoriesComponent');
        $this->emit('CompanyAdminStoryLoopComponent');
        $this->emit('renderCompanyAdminStoryLoopComponent');
    }

    public function publish($id)
    {
        $this->alert()->confirm([
            'icon' => 'warning',
            'title' => 'Are you sure you want to enable this?',
            'confirmButtonText' => 'Yes',
            'method' => 'publishStory',
            'params' => $id
        ]);
    }

    public function setPublish($id)
    {
        $obj = Story::find($id);
        if ($obj) {
            $obj->update(['status' => 'publish']);
            $this->status = 'publish';
            $this->alert()->success(['title' => 'Story enabled']);
        }
        $this->emit('companyAdminStoriesComponent');
        $this->emit('companyAdminStoriesComponent');
        $this->emit('CompanyAdminStoryLoopComponent');
        $this->emit('renderCompanyAdminStoryLoopComponent');
    }

    public function delete($id)
    {
        $this->alert()->confirm([
            'icon' => 'warning',
            'title' => 'Are you sure you want to delete this?',
            'confirmButtonText' => 'Yes',
            'method' => 'deleteStory',
            'params' => $this->story->id
        ]);
    }

    public function doDelete($id)
    {
        $obj = Story::find($id);
        if ($obj) {
            $obj->delete();
        }

        $this->alert()->success(['title' => 'Story was deleted']);
        $this->emit('companyAdminStoriesComponent');
        $this->emit('companyAdminStoriesComponent');
        $this->emit('CompanyAdminStoryLoopComponent');
        $this->emit('renderCompanyAdminStoryLoopComponent');
    }
}
