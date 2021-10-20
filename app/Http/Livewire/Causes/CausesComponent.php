<?php

namespace App\Http\Livewire\Causes;

use App\Models\Company;
use App\Services\CauseService;
use Livewire\Component;
use Livewire\WithPagination;

class CausesComponent extends Component
{

    use WithPagination;

    protected $listeners = ['causesComponent' => 'render' ];

    public $company;
    public $searchQuery = '';
    public $category = 'all';
    public $matchable = 'all';
    public $status = 'approved';

    protected $queryString = [
        'searchQuery' => ['except' => ''],
        'status' => 'all',
        'matchable' => 'all',
    ];

    public function render()
    {

        return view('livewire.causes.causes-component', [
            'causes' => $this->getCauses(),
            'categories' => CauseService::getCategories()
        ]);
    }

    public function mount($company)
    {
        $this->company = $company;

    }

    public function paginationView()
    {
        return 'vendor.livewire.custom-pagination-foundation';
    }

    public function getCauses()
    {
            if(auth()->check() && auth()->user()->type === 'company') {
                return  CauseService::getCauses(
                    20,
                    $this->searchQuery,
                    $this->category,
                    'causesPage'
                );
            }

        return CauseService::getCompanyCauses(
            $this->company,
            20,
            $this->searchQuery,
            $this->category,
            'causesPage'
        );
    }
}
