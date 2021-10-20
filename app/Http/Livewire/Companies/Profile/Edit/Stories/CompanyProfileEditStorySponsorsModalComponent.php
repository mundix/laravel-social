<?php

namespace App\Http\Livewire\Companies\Profile\Edit\Stories;

use App\Models\Story;
use Livewire\Component;

class CompanyProfileEditStorySponsorsModalComponent extends Component
{

    public $company;
    public $sponsors;
    public $story;

    protected $listeners = [
        'CompanyProfileEditStorySponsorsModalComponent' => 'setSponsors',
    ];

    public function render()
    {
        return view('livewire.companies.profile.edit.stories.company-profile-edit-story-sponsors-modal-component',[
            'employees' => $this->company->employees,
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
            $this->emit('CompanyProfileEditUpdateStoryModalComponent');
        } else {
            $this->emit('CompanyProfileEditNewStoryComponentChange', $this->sponsors->toArray());
        }
    }
}
