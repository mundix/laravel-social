<?php

namespace App\Http\Livewire\Companies\Posts;

use App\Models\CategoryPost;
use Livewire\Component;
use Livewire\WithPagination;

class CompanyNewsComponent extends Component
{
    use WithPagination;

    public $company;
    public $user;
    public $searchQuery = '';
    public $category = 'all';
    private $pageName = 'news';

    protected $queryString = [
        'searchQuery' => ['except' => ''],
        'category' => 'all',
        'page' => ['except' => 1],
    ];

    public function render()
    {
        $posts = $this->company->user->posts()
            ->when($this->searchQuery, function ($query) {
                $query->where('title', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('title', 'content', '%' . $this->searchQuery . '%');
            })
            ->when((!empty($this->category) && $this->category !== 'all'), function ($query) {
                $query->where('category_id', $this->category);
            })
            ->paginate(config('bondeed.frontend.dashboards.limit'), ['*'], $this->pageName);

        return view('livewire.companies.posts.company-news-component', [
            'posts' => $posts,
            'categories' => CategoryPost::all()
        ]);
    }

    public function paginationView()
    {
        return 'vendor.livewire.custom-pagination-foundation';
    }
}
