<?php

namespace App\Http\Livewire\Causes;

use App\Models\Activity;
use App\Models\Cause;
use App\Models\User;
use App\Traits\SupportUiNotification;
use Livewire\Component;

class CauseFavoritesModalComponent extends Component
{

    use SupportUiNotification;

    public $company;
    public $cause;
    public $count;
    public $isFavorite;

    protected $listeners = [
        'CauseFavoritesModalComponent' => '$refresh',
        'setCauseFavoritesModalComponent' => 'setCause'
    ];

    public function render()
    {
        return view('livewire.causes.cause-favorites-modal-component');
    }

    public function setCause($id)
    {
        $this->cause = Cause::find($id);

        $this->isFavorite = \Auth::check() ? $this->cause->isFavoritedBy(auth()->user()) : false;

        $this->count = $this->cause->favoriters()->count() ?? 0;
        $this->favoriteCauses = $this->cause->favoriters;

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

                $this->alert()->success(['title' => 'You\'ve unfavorited this Cause.']);
            }

            $this->cause = $this->cause->refresh();

            $this->isFavorite = \Auth::check() ? $this->cause->isFavoritedBy(auth()->user()) : false;

            $this->count = $this->cause->favoriters()->count() ?? 0;

            $this->emit('CauseLoopComponent');

            $this->emit('refreshCauseLoopComponent');

            $this->emit('updateDOM');
        } else {
            $this->alert()->success(['title' => 'You must be  logged to perform this action']);
        }
    }

}
