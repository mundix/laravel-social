<?php

namespace App\Providers;

use App\Contracts\UiNotificationSystem;
use App\Helpers\SweetAlertNotification;
use App\Models\Admin;
use App\Models\Cause;
use App\Models\CompanyInvite;
use App\Models\Invite;
use App\Models\User;
use App\Observers\AdminObserver;
use App\Observers\CompanyInviteObserve;
use App\Observers\InviteObserve;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }

        $this->app->bind(
            UiNotificationSystem::class,
            SweetAlertNotification::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        CompanyInvite::observe(CompanyInviteObserve::class);
        Invite::observe(InviteObserve::class);
        Admin::observe(AdminObserver::class);
    }
}
