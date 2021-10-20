<?php

namespace App\Http\Livewire\Companies\Profile\Causes;

use App\Models\Activity;
use App\Traits\SupportUiNotification;
use Livewire\Component;

class CompanyProfileCauseSocialComponent extends Component
{

    use SupportUiNotification;

    public $cause;
    public $count;

    protected $listeners = ['likeMe' => 'doLike', 'companyProfileCauseLoopComponent' => '$refresh'];

    public function render()
    {
        return view('livewire.companies.profile.causes.company-profile-cause-social-component');
    }

    public function mount($cause)
    {
        $this->cause = $cause;
        $this->count = $cause->favoriters()->count() ?? 0;
    }

    public function doLike()
    {

        $user = auth()->user();
        if(!$this->cause->isFavoritedBy(auth()->user())) {
            $user->favorite($this->cause);
            $activity = new Activity(['user_id' => $user->id, 'type' => 'favorite']);
            $this->cause->activities()->save($activity);
            $this->alert()->success(['title' => 'You\'ve Favorited this Cause']);
        }else {
            $user->unfavorite($this->cause);
            $this->alert()->success(['title' => 'You\'ve unfavorited this Cause.']);
        }

        $this->cause = $this->cause->refresh();

        $this->count = $this->cause->favoriters()->count() ?? 0;

        $this->emit('companyProfileCauseLoopComponent');

        $this->emit('refreshCompanyProfileCauseLoopComponent');

        $this->emit('CompanyProfileCausesComponent');

        $this->emit('updateDOM');

    }
}
