<?php


namespace App\Traits;


use App\Contracts\UiNotificationSystem;

trait SupportUiNotification
{
    /**
     * @return UiNotificationSystem
     */
    public function alert()
    {
        return app()->make(UiNotificationSystem::class, ['context' => $this]);
    }
}
