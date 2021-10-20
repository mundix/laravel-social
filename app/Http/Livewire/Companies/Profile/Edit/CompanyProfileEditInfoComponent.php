<?php

namespace App\Http\Livewire\Companies\Profile\Edit;

use App\Services\CompanyService;
use App\Traits\SupportUiNotification;
use Livewire\Component;

class CompanyProfileEditInfoComponent extends Component
{
    use SupportUiNotification;

    public $name, $location, $caption, $description, $company;

    public function render()
    {
        return view('livewire.companies.profile.edit.company-profile-edit-info-component');
    }

    public function mount()
    {
        $this->name = $this->company->name;
        $this->location = $this->company->location;
        $this->caption = $this->company->caption;
        $this->description = $this->company->description;

    }

    public function updatedName()
    {
        $this->validate([
            'name' => 'required',
        ]);
        $this->company->update(['name' => $this->name]);

        $this->alert()->success([
            'title' => 'Company Name updated'
        ]);
    }

    public function updatedLocation()
    {
        $this->validate([
            'location' => 'required',
        ]);
        $this->company->update(['location' => $this->location]);

        $this->alert()->success([
            'title' => 'Location updated'
        ]);
    }

    public function updatedCaption()
    {
        $this->validate([
            'caption' => 'required',
        ]);
        $this->company->update(['caption' => $this->caption]);

        $this->alert()->success([
            'title' => 'Caption updated'
        ]);
    }

    public function updatedDescription()
    {
        $this->validate([
            'description' => 'required|max:500',
        ]);
        $this->company->update(['description' => $this->description]);

        $this->alert()->success([
            'title' => 'Company Description updated'
        ]);
    }
}
