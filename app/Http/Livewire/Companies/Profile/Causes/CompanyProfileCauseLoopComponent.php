<?php

namespace App\Http\Livewire\Companies\Profile\Causes;

use Livewire\Component;

class CompanyProfileCauseLoopComponent extends Component
{
    public $cause;
    public $isFavorite;

    protected $listeners = ['CompanyProfileCauseLoopComponent' => '$refresh', 'refreshCompanyProfileCauseLoopComponent' => 'doRefresh'];

    public function render()
    {
        return view('livewire.companies.profile.causes.company-profile-cause-loop-component');
    }

    public function mount()
    {
        $this->isFavorite = \Auth::check() ? $this->cause->isFavoritedBy(auth()->user()) : false;
    }

    public function doRefresh()
    {
        $this->cause = $this->cause->refresh();

        $this->isFavorite = $this->cause->isFavoritedBy(auth()->user()) ? true : false;

        $this->count = $this->cause->favoriters()->count() ?? 0;

    }
}
