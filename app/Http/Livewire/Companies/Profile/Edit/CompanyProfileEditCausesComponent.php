<?php

namespace App\Http\Livewire\Companies\Profile\Edit;

use App\Models\CategoryCause;
use App\Models\Cause;
use App\Services\CauseService;
use Livewire\Component;
use Livewire\WithPagination;

class CompanyProfileEditCausesComponent extends Component
{
    use WithPagination;

    public $categories;
    public $company;
    public $searchQuery = '';
    public $category = 'all';

    protected $queryString = [
        'searchQuery' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    protected $listeners = ['companyProfileEditCausesComponent' => '$refresh'];

    public function render()
    {
        $causes = CauseService::getFavoriteCauses(
            $this->company, config('bondeed.frontend.dashboards.limit'),
            $this->searchQuery,
            $this->category,
            'causesPage'
        );
        return view('livewire.companies.profile.edit.company-profile-edit-causes-component', [
            'causes' => $causes
        ]);
    }

    public function mount()
    {
        $this->categories = CategoryCause::all();
    }

    public function paginationView()
    {
        return 'vendor.livewire.custom-pagination-foundation';
    }

}
