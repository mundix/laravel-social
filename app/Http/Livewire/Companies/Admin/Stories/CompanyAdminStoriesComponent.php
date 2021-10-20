<?php

namespace App\Http\Livewire\Companies\Admin\Stories;

use App\Services\StoryService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class CompanyAdminStoriesComponent extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $company;
    public $searchQuery = '';
    public $status = null;
    private $pageName = 'storiesPage';
    public $sortAsc = true;
    public $sortField = 'title';

    protected $queryString = [
        'searchQuery' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    protected $listeners = [
        'companyAdminStoriesComponent' => '$refresh',
        'renderCompanyAdminStoriesComponent' => 'render',
    ];

    public function render()
    {
        $stories = (new StoryService)
            ->search($this->company, config('bondeed.frontend.dashboards.limit-10'),
                $this->status,
                $this->searchQuery,
                $this->sortField,
                $this->sortAsc,
            );
        return view('livewire.companies.admin.stories.company-admin-stories-component', [
            'stories' => $stories,
            'storiesSubmitted' => $this->company->stories()->whereStatus('draft')->count(),
        ]);
    }

    public function hydrate()
    {
        $this->emit('updateDOM');
    }

    public function mount()
    {
        $this->emit('updateDOM');
    }

    public function paginationView()
    {
        return 'vendor.livewire.custom-pagination-foundation';
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
}
