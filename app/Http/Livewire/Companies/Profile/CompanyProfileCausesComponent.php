<?php

namespace App\Http\Livewire\Companies\Profile;

use App\Models\CategoryCause;
use App\Models\Cause;
use App\Services\CauseService;
use App\Services\CompanyService;
use Livewire\Component;
use Livewire\WithPagination;

class CompanyProfileCausesComponent extends Component
{
    use WithPagination;

    public $company;
    public $searchQuery = '';
    public $category = 'all';
    public $showActions = false;

    protected $listeners = ['CompanyProfileCausesComponent' => '$refresh'];

    public function mount()
    {
        if(\Auth::user() && \Auth::user()->type === 'company' && $this->company === \Auth::user()->company->id) {
            $this->showActions = true;
        }
    }

    protected $queryString = [
        'searchQuery' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function render()
    {
        $causes = $this->company->user->getFavoriteItems(Cause::class)->get();

        return view('livewire.companies.profile.company-profile-causes-component', [
            'causes' => $causes,
            'categories' => CategoryCause::all()
        ]);
    }

    public function hydrate()
    {
        $this->emit('updateDOM');
    }
}
