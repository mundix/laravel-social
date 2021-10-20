<?php

namespace App\Http\Livewire\Companies\Profile\Edit\Causes;

use App\Models\Cause;
use App\Traits\SupportUiNotification;
use Livewire\Component;
use Livewire\WithPagination;

class CompanyProfileEditCauseLoopComponent extends Component
{
    use SupportUiNotification;

    public $cause;
    public $user;
    public $count;
    public $favorite = false;

    protected $listeners = [
        'companyProfileEditCauseLoopComponent' => 'render',
        'deleteCause' => 'doDelete',
    ];

    public function render()
    {
        $this->favorite = $this->cause->isFavoritedBy($this->user) ? true : false;
        $this->count = $this->cause->favoriters()->count() ?? 0;
        return view('livewire.companies.profile.edit.causes.company-profile-edit-cause-loop-component');
    }

    public function mount()
    {
        $this->user = auth()->user();
        $this->favorite = $this->cause->isFavoritedBy($this->user);
    }

    public function hydrate()
    {
        $this->emit('updateDOM');
    }

    public function toggleFavorite()
    {
        $this->user->toggleFavorite($this->cause);
        $this->favorite = $this->cause->isFavoritedBy($this->user);
        $status = $this->favorite ? 'Unfavorited' : 'Favorited';

        if($status) {
            $this->alert()->success(['title' => 'This Cause was favorited' ]);
        }else{
            $this->alert()->success(['title' => 'This Cause was unfavorited' ]);
        }
        $this->emit('companyProfileEditCauseLoopComponent');

    }
}
