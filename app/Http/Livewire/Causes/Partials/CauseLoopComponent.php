<?php

namespace App\Http\Livewire\Causes\Partials;

use App\Models\Activity;
use App\Traits\SupportUiNotification;
use App\Traits\ValidatorErrorManagementTrait;
use Livewire\Component;

class CauseLoopComponent extends Component
{

    use SupportUiNotification;
    use ValidatorErrorManagementTrait;

    public $cause;
    public $count;
    public $isFavorite;

    protected $listeners = ['CauseLoopComponent' => 'render', 'refreshCauseLoopComponent' => 'doRefresh'];

    public function render()
    {
        return view('livewire.causes.partials.cause-loop-component');
    }

    public function mount($cause)
    {
        $this->cause = $cause;
        $this->cause = $this->cause->refresh();
        $this->isFavorite = \Auth::check() ? $this->cause->isFavoritedBy(auth()->user()) : false;
        $this->count = $this->cause->favoriters()->count() ?? 0;
    }

    public function doRefresh()
    {
        $this->cause = $this->cause->refresh();

        $this->isFavorite = \Auth::check() ? $this->cause->isFavoritedBy(auth()->user()) : false;

        $this->count = $this->cause->favoriters()->count() ?? 0;

    }

    public function doLike()
    {
        if (auth()->check()) {

            $user = auth()->user();

            if (!$this->cause->isFavoritedBy($user)) {

                $user->favorite($this->cause);
                $activity = new Activity(['user_id' => $user->id, 'type' => 'favorite']);
                $this->cause->activities()->save($activity);

                $this->alert()->success(['title' => 'You\'ve Favorited this Cause']);

            } else {

                $user->unfavorite($this->cause);

                $this->alert()->success(['title' => 'You\'ve unfavorited this Cause']);

            }

            $this->cause = $this->cause->refresh();

            $this->count = $this->cause->favoriters()->count() ?? 0;

            $this->emit('causesComponent');

            $this->emit('CauseLoopComponent');

            $this->emit('refreshCauseLoopComponent');


            $this->emit('updateDOM');

        } else {

            $this->alert()->success(['title' => 'You must be  logged to perform this action']);

        }
    }
}
