<?php

namespace App\Http\Livewire\Companies\Profile\Edit;

use Livewire\Component;
use Livewire\WithPagination;

class CompanyProfileEditCommunityComponent extends Component
{

    use WithPagination;

    public $company;
    public $employee;
    private $pageName = 'communityPage';
    public $isEmployee = false;

    protected $listeners = [
        'companyProfileEditCommunityComponent' => '$refresh',
        'renderCompanyProfileEditCommunityComponent' => 'render',
    ];

    public function render()
    {
        $user = auth()->user();
        return view('livewire.companies.profile.edit.company-profile-edit-community-component', [
            'testimonials' => $user->testimonials()
                ->paginate(config('bondeed.frontend.dashboards.limit'), ['*'], $this->pageName),
            'total_draft' => $user->testimonials()->where('status', 'pending')->count() ?? 0
        ]);
    }

    public function paginationView()
    {
        return 'vendor.livewire.custom-pagination-foundation';
    }

    public function mount($company, $employee = null)
    {
        if(auth()->check() && auth()->user()->type === 'employee') {
            $this->isEmployee = true;
        }

        $this->employee = $employee;
        $this->company = $company;
    }
}
