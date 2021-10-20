<?php

namespace App\Http\Livewire\Admin;

use App\Models\Cause;
use App\Models\Company;
use App\Services\CauseService;
use App\Services\CompanyService;
use App\Traits\SupportUiNotification;
use Livewire\Component;
use Livewire\WithPagination;

class AdminCausesComponent extends Component
{
    use WithPagination;
    use SupportUiNotification;

    public $searchQuery;
    public $searchQueryCategory;
    public $searchQueryCompany;
    public $status = 'all';
    public $disabled = null;
    public $companiesIds = null;
    public $matchable = 'all';
    public $favorite = 'all';
    public $sortField = 'name';
    public $category;
    public $cause;
    public $locationType;
    public $sortAsc = true;
    public $isDirty = false;

    protected $listeners = [
        'adminCausesComponent' => '$refresh',
        'renderAdminCausesComponent' => 'render',
        'updateAdminCausesComponent' => 'isUpdated',
    ];

    protected $queryString = [
        'searchQuery' => ['except' => ''],
        'status' => 'all',
        'searchQueryCategory' => ['except' => ''],
        'searchQueryCompany' => ['except' => ''],
        'matchable' => 'all',
    ];

    public function isUpdated()
    {
        $this->isDirty = true;
    }

    public function render()
    {

        $causes = (new CauseService)
            ->search(
                $this->companiesIds,
                config('bondeed.frontend.dashboards.limit'),
                $this->category,
                $this->locationType,
                $this->searchQuery,
                $this->status,
                $this->disabled,
                $this->matchable,
                $this->favorite,
                $this->sortField,
                $this->sortAsc
            );

        $companies = (new CauseService)->getCompaniesHasCauses($this->searchQueryCompany);

        $totalNominates = CauseService::getNominatePendingTotal();

        return view('livewire.admin.admin-causes-component', [
            'causes' => $causes,
            'total' => $causes->total(),
            'totalNominate' => $totalNominates,
            'categories' => (new CauseService)->getCauseCategories($this->searchQueryCategory),
            'companies' => $companies
        ]);
    }


    public function mount()
    {
        $this->category = null;
        $this->companies = (new CompanyService)->getAllCompanies();
    }

    public function hydrate()
    {
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
        $this->emit('updateDOM');
    }

    public function paginationView()
    {
        return 'vendor.livewire.custom-pagination-foundation';
    }

    public function updatedCategory($values)
    {
        $this->category = $values;
    }

    public function updatedLocationType($values)
    {
        $this->locationType = $values;
    }

    public function updatedCompaniesIds($value)
    {
        $this->companiesIds = $value;
    }


}
