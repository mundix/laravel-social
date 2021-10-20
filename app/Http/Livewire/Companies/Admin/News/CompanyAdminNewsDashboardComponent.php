<?php

namespace App\Http\Livewire\Companies\Admin\News;

use App\Models\CategoryPost;
use App\Services\PostService;
use Livewire\Component;
use Livewire\WithPagination;

class CompanyAdminNewsDashboardComponent extends Component
{

    use WithPagination;

    public $company;
    public $searchQuery = '';
    public $status = 'all';
    public $disabled = null;
    public $category = 'all';
    private $pageName = 'news';
    public $sortField = 'title';
    public $sortAsc = true;

    protected $queryString = [
        'searchQuery' => ['except' => ''],
        'category' => 'all',
        'status' => 'all',
        'page' => ['except' => 1],
    ];

    protected $listeners = [
        'companyAdminNewsDashboardComponent' => '$refresh',
        'renderCompanyAdminNewsDashboardComponent' => 'render',
    ];

    public function render()
    {
        $posts = (new PostService)
            ->search(
                $this->company,
                $this->searchQuery,
                $this->category,
                $this->status,
                $this->disabled,
                config('bondeed.frontend.dashboards.limit'),
                $this->sortField,
                $this->sortAsc,
                $this->pageName
            );
        return view('livewire.companies.admin.news.company-admin-news-dashboard-component', [
            'posts' => $posts,
            'totalPosts' => $posts->count(),
            'categories' => CategoryPost::all()
        ]);
    }

    public function mount()
    {
        $this->category = [];
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
