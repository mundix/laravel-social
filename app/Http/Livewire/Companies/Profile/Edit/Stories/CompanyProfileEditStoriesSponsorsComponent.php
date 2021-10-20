<?php

namespace App\Http\Livewire\Companies\Profile\Edit\Stories;

use App\Models\User;
use App\Traits\SupportUiNotification;
use Livewire\Component;

class CompanyProfileEditStoriesSponsorsComponent extends Component
{
    use SupportUiNotification;

    public $company;
    public $sponsors;

    protected $listeners = [
        'CompanyProfileEditStoriesSponsorsComponent' => 'updateSponsors',
        'deleteSponsor' => 'deleteSponsor'
    ];

    public function render()
    {
        return view('livewire.companies.profile.edit.stories.company-profile-edit-stories-sponsors-component');
    }

    public function updateSponsors($sponsors)
    {
        $this->sponsors = User::whereIn('id', $sponsors)->get();
    }

    public function deleteSponsorAction($employeeId)
    {
        $this->alert()->confirm([
            'icon' => 'warning',
            'title' => 'Do you want to remove this sponsor ?' ,
            'confirmButtonText' => 'Yes',
            'method' => 'deleteSponsor',
            'params' => $employeeId
        ]);
    }

    public function deleteSponsor($employeeId)
    {
        $this->sponsors = $this->sponsors->filter(function(User $user) use ($employeeId){
            return $user->id != $employeeId;
        });

        $this->emit('CompanyProfileEditNewStoryComponentChange', $this->sponsors->pluck('id')->toArray());
    }
}
