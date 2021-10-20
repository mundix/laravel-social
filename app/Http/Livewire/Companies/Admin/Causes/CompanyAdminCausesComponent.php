<?php

namespace App\Http\Livewire\Companies\Admin\Causes;

use App\Models\Cause;
use App\Services\CauseService;
use App\Traits\SupportUiNotification;
use Livewire\Component;
use Livewire\WithPagination;

class CompanyAdminCausesComponent extends Component
{

    use WithPagination;
    use SupportUiNotification;

    public $searchQuery;
    public $searchQueryCategory;
    public $status = 'all';
    public $disabled = null;
    public $matchable;
    public $favorite = 'all';
    public $sortField = 'name';
    public $category;
    public $cause;
    public $company;
    public $locationType;
    public $sortAsc = true;
    public $isDirty = false;

    protected $listeners = [
        'refreshCompanyAdminCausesComponent' => '$refresh',
        'renderCompanyAdminCausesComponent' => 'render',
        'updateAdminCausesComponent' => 'isUpdated',
        'deleteCause' => 'doDelete'
    ];

    protected $queryString = [
        'searchQuery' => ['except' => ''],
        'searchQueryCategory' => ['except' => ''],
        'status' => 'all',
        'favorite' => 'all',
        'matchable' => 'all',
    ];

    public function isUpdated()
    {
        $this->isDirty = true;
    }

    public function render()
    {
        $causes = (new CauseService)
            ->searchByCompany(
                $this->company,
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

        $totalNominates = CauseService::getNominatePendingTotal($this->company);
        return view('livewire.companies.admin.causes.company-admin-causes-component', [
            'causes' => $causes,
            'total' => $causes->total(),
            'totalNominate' => $totalNominates,
            'categories' => CauseService::getCategoriesHasCauses($this->searchQueryCategory)
        ]);
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

}
