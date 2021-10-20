<?php

namespace App\Http\Livewire\Companies\Profile\Edit;

use App\Models\CategoryPost;
use Livewire\Component;
use Livewire\WithPagination;

class CompanyProfileEditNewsComponent extends Component
{

    use WithPagination;

    public $company;
    public $searchQuery = '';
    public $category = 'all';
    private $pageName = 'news';

    protected $queryString = [
        'searchQuery' => ['except' => ''],
        'category' => 'all',
        'page' => ['except' => 1],
    ];

    protected $listeners = ['companyProfileEditNewsComponent' => '$refresh'];

    public function render()
    {
        return view('livewire.companies.profile.edit.company-profile-edit-news-component', [
            'posts' => $this->company->posts()
                ->when($this->searchQuery, function ($query) {
                    $query->where('title', 'like', '%' . $this->searchQuery . '%')
                        ->orWhere('title', 'content', '%' . $this->searchQuery . '%');
                })
                ->when((!empty($this->category) && $this->category !== 'all'), function ($query) {
                    $query->where('category_id', $this->category);
                })
                ->paginate(config('bondeed.frontend.dashboards.limit'), ['*'], $this->pageName),
            'categories' => CategoryPost::all()
        ]);
    }

    public function mount()
    {
        $this->category = [];
    }

    public function paginationView()
    {
        return 'vendor.livewire.custom-pagination-foundation';
    }
}
