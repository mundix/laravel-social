<?php

namespace App\Http\Livewire\Companies\Profile\Edit;

use App\Models\Involvement;
use Livewire\Component;
use Livewire\WithPagination;

class CompanyProfileEditInvolvementsComponent extends Component
{
    use WithPagination;

    public $company;

    protected $listeners = ['companyProfileEditInvolvementsComponent' => '$refresh'];

    public function render()
    {
        return view('livewire.companies.profile.edit.company-profile-edit-involvements-component', [
            'involvements' => $this->company->involvements()->paginate(config('bondeed.frontend.dashboards.limit'))
        ]);
    }

}
