<?php

namespace App\Http\Livewire\Companies\Profile\Edit\Stories;

use App\Models\Story;
use App\Services\StoryService;
use App\Traits\SupportUiNotification;
use App\Traits\ValidatorErrorManagementTrait;
use Livewire\Component;
use Livewire\WithPagination;

class CompanyProfileEditApprovalStoriesComponent extends Component
{

    use WithPagination;
    use SupportUiNotification;
    use ValidatorErrorManagementTrait;

    public $company;
    public $pageName = 'storyApprovalPage';
    public $sortField = 'title';
    public $sortAsc = true;

    protected $listeners = [
        'companyProfileEditApprovalStoriesComponent' => '$refresh',
        'renderCompanyProfileEditApprovalStoriesComponent' => 'render',
        'removeStory' => 'doRemove'
    ];

    public function render()
    {
        $stories = (new StoryService)
            ->search($this->company, config('bondeed.frontend.dashboards.limit'),
                'draft',
                null,
                $this->sortField,
                $this->sortAsc,
            );

        return view('livewire.companies.profile.edit.stories.company-profile-edit-approval-stories-component', [
            'stories' => $stories
        ]);
    }

    public function mount($company)
    {
        $this->company = $company;
    }

    public function hydrate()
    {
        $this->emit('updateDOM');
    }

    public function paginationView()
    {
        return 'vendor.livewire.custom-pagination-foundation';
    }

    public function approve($id)
    {
        $event = Story::find($id);
        $event->update(['status' => 'publish']);
        $this->alert()->success(['title' => 'Your story was approved']);
        $this->emit('companyProfileEditApprovalStoriesComponent');
        $this->emit('companyProfileEditStoriesComponent');
        $this->emit('companyAdminStoriesComponent');
        $this->emit('updateDOM');
        $this->isRemoved = true;
    }

    public function decline($id)
    {
        $story = Story::find($id);
        $this->alert()->confirm([
            'icon' => 'warning',
            'title' => 'Are your sure you want to decline this Request ?',
            'confirmButtonText' => 'Yes',
            'method' => 'removeStory',
            'params' => $id
        ]);
        $this->emit('updateDOM');
    }

    public function doRemove($id)
    {
        Story::find($id)->delete();
        $this->alert()->success(['title' => 'Your story request was declined']);
        $this->emit('companyProfileEditApprovalStoriesComponent');
        $this->emit('companyProfileEditStoriesComponent');
        $this->emit('updateDOM');
    }

    public function sortBy($field)
    {
        if ($this->sortField == $field) {
            $this->sortAsc = !$this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
        $this->emit('companyProfileEditApprovalStoriesComponent');
        $this->emit('companyProfileEditStoriesComponent');
        $this->emit('companyAdminStoriesComponent');
        $this->emit('updateDOM');
    }
}
