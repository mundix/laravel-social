<?php

namespace App\Http\Livewire\Companies\Admin\Stories;

use App\Models\User;
use App\Traits\SupportUiNotification;
use Livewire\Component;

class CompanyAdminStorySponsorsComponent extends Component
{
    use SupportUiNotification;

    public $company;
    public $sponsors;

    protected $listeners = [
        'CompanyAdminStorySponsorsComponent' => 'updateSponsors',
        'deleteSponsor' => 'deleteSponsor'
    ];

    public function render()
    {
        return view('livewire.companies.admin.stories.company-admin-story-sponsors-component');
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
