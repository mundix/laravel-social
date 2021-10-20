<?php

namespace App\Http\Livewire\Companies\Admin\Stories;

use App\Models\Story;
use App\Services\CompanyService;
use App\Traits\SupportUiNotification;
use Livewire\Component;

class CompanyAdminEditStorySponsorsModalComponent extends Component
{

    use SupportUiNotification;

    public $company;
    public $sponsors;
    public $story;
    public $searchQuery;

    protected $queryString = [
        'searchQuery' => ['except' => ''],
    ];

    protected $listeners = [
        'CompanyAdminEditStorySponsorsModalComponent' => 'setSponsors',
        'renderCompanyAdminEditStorySponsorsModalComponent' => 'render',
        'refreshCompanyAdminEditStorySponsorsModalComponent' => '$refresh',
    ];

    public function render()
    {
        $employees = (new CompanyService)->getActiveEmployeesByCompany($this->company, $this->searchQuery);
        return view('livewire.companies.admin.stories.company-admin-edit-story-sponsors-modal-component', [
            'employees' => $employees ,
            'sponsors' => $this->sponsors
        ]);
    }

    public function mount()
    {
        $this->sponsors = collect([]);
    }

    public function updateSponsors($userId, $checked)
    {
        if (!$checked) {
            $key = $this->sponsors->search($userId);
            $this->sponsors->pull($key);
            $this->sponsors = $this->sponsors->values();
            return;
        }

        $this->sponsors->push($userId);
    }

    public function setSponsors($sponsors, $story)
    {
        $this->story = Story::find($story['id']);
        $this->sponsors = collect($sponsors);
        $this->emit('updateDOM');
    }

    public function save()
    {
        if ($this->story) {
            $this->story->sponsors()->sync($this->sponsors->toArray());
            $this->emit('CompanyAdminStoryEditModalComponent');
        } else {
            $this->emit('CompanyAdminStoryCreateModalComponentChange', $this->sponsors->toArray());
        }
    }
}
