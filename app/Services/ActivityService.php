<?php


namespace App\Services;


use App\Interfaces\Activities;

use App\Interfaces\Users;
use App\Models\Cause;
use App\Models\User;

use Spatie\Activitylog\Models\Activity;

class ActivityService implements Activities, Users
{

	public static function getUser()
	{
		return auth()->user();
	}


	/**
	 * Say Thanks To a User
	 * @param User $user
	 */
	public static function sayThanksToUser(User $user): void
	{
		$loggedUser = self::getUser();
		activity()
			->causedBy($loggedUser)
			->withProperties(['key' => self::TYPES_THANK, 'user_id' => $user->id])
			->by($loggedUser)
			->log(self::MESSAGE_THANK);
	}

	/**
	 * Sent a Kudo to User
	 * @param User $user
	 */
	public static function giveKudoToUser(User $user): void
	{
		$loggedUser = self::getUser();
		activity()
			->causedBy($loggedUser)
			->withProperties(['key' => self::TYPES_KUDO, 'user_id' => $user->id])
			->by($loggedUser)
			->log(self::MESSAGE_KUDO);
	}
	/**
	 * Add a Cause to Favorite
	 * @param User $user
	 */
	public static function addFavoriteCause(Cause $cause)
	{
		$loggedUser = self::getUser();

		activity()
			->causedBy($loggedUser)
			->withProperties(['key' => self::TYPES_FAVORITE, 'cause_id' => $cause->id])
			->by($loggedUser)
			->log(self::MESSAGE_FAVORITE);
	}

	public static function getAll( $limit = self::LIMIT)
	{
		$activities = collect(Activity::all())->take($limit);
		return $activities;
	}


	public function getActivityPropertiesToArray($activity) : array
    {

        $subject = $this->getActivitySubject($activity);
        $properties = [];

        if (isset($activity->properties['old'])) {
            foreach ($activity->properties['old'] ?? [] as $key => $property) {
                if ($property != $activity->properties['attributes'][$key]) {
                    $properties[$key] = $activity->properties['attributes'][$key];
                }
            }
        } else {
            if ($subject == 'Media') {
                foreach ($activity->properties as $key => $property) {
                    $properties[$key] = $property;
                }
            } else {
                if (isset($activity->properties['attributes'])) {
                    foreach ($activity->properties['attributes'] as $key => $property) {
                        $properties[$key] = $property;
                    }
                }
            }
        }

        return $properties;
    }

    public function getActivitySubject($activity) : string
    {
        $subject = '';
        if(isset($activity->subject)) {
            $subject = str_replace('App\Models\\', '', get_class($activity->subject));
            $subject = str_replace('Spatie\MediaLibrary\MediaCollections\Models\\', '', $subject);
        }

        return $subject;
    }
}