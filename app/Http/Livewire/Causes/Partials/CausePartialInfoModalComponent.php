<?php

namespace App\Http\Livewire\Causes\Partials;

use App\Models\Activity;
use App\Models\Cause;
use App\Traits\SupportUiNotification;
use Livewire\Component;

class CausePartialInfoModalComponent extends Component
{
    use SupportUiNotification;

    public $company;
    public $cause;
    public $name;
    public $picture;
    public $matchable;
    public $count;
    public $description;
    public $isFavorite;
    public $website;
    public $email;

    protected $listeners = ['setCauseInfoModal' => 'setInfo'];

    public function render()
    {
        return view('livewire.causes.partials.cause-partial-info-modal-component');
    }

    public function setInfo($id)
    {
        $cause = Cause::find($id);
        $this->cause = $cause->refresh();
        $this->name = $this->cause->name;
        $this->matchable = $this->cause->matchable;
        $this->website = $this->cause->website;
        $this->likes = $this->cause->likeCount;
        $this->description = $this->cause->description;
        $this->email = $this->cause->email;
        $this->isFavorite = \Auth::check() ? $this->cause->isFavoritedBy(auth()->user()) : false;
        $this->count = $this->cause->favoriters()->count() ?? 0;

        $this->emit('openCauseInfoModal');

    }

    public function doLike()
    {
        if (auth()->check()) {

            $user = auth()->user();

            if (!$this->cause->isFavoritedBy($user)) {

                $user->favorite($this->cause);
                $activity = new Activity(['user_id' => $user->id, 'type' => 'favorite']);
                $this->cause->activities()->save($activity);
                $this->alert()->success(['title' => 'You\'ve Favorited this Cause.']);

            } else {

                $user->unfavorite($this->cause);
                $this->alert()->success(['title' => 'You\'ve unfavorited this Cause']);

            }

            $this->cause = $this->cause->refresh();
            $this->isFavorite = \Auth::check() ? $this->cause->isFavoritedBy(auth()->user()) : false;
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
