<?php

namespace App\Http\Livewire\Companies\Profile\Edit;

use App\Services\CompanyService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class CompanyProfileEditStoriesComponent extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $company;
    private $pageName = 'storiesPage';

    protected $listeners = ['companyProfileEditStoriesComponent' => '$refresh'];

    public function render()
    {
        $stories = $this->company->stories()->whereStatus('publish')->paginate(config('bondeed.frontend.dashboards.limit'),
            ['*'], $this->pageName);

        return view('livewire.companies.profile.edit.company-profile-edit-stories-component', [
            'stories' => $stories,
            'storiesSubmitted' => $this->company->stories()->whereStatus('draft')->get(),
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
}
