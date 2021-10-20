<?php


namespace App\Traits;


use App\Interfaces\Activities;
use App\Models\Activity;
use App\Models\Cause;
use App\Models\User;

class SociableTrait implements Activities
{
    public function isKudos(User $user)
    {

    }

    public function isGreeting(User $user)
    {

    }

    public function isFavorite(Cause $cause)
    {

    }

    public function sendKudos(User $user)
    {
        $user->activities()->create(['type'=>self::TYPES_KUDO, 'user_id' => auth()->user()->id]);
    }

    public function sendGreetings(User $user)
    {
        $user->activities()->create(['type'=>self::TYPES_THANK, 'user_id' => auth()->user()->id]);
    }

    public function sendFavorites(Cause $cause)
    {
        $cause->activities()->create(['type'=>self::TYPES_FAVORITE, 'user_id' => auth()->user()->id]);
    }
}