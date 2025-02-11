<?php

namespace App\Providers;

use App\Models\Notification;
use App\Notifications\NewMatchNotification;
use Illuminate\Support\ServiceProvider;
use Illuminate\Notifications\Channels\DatabaseChannel as IlluminateDatabaseChannel;
use Illuminate\Notifications\DatabaseNotification as BaseNotification;
use App\Channels\DatabaseChannel;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->instance(IlluminateDatabaseChannel::class, new DatabaseChannel());
        //$this->app->instance(NewMatchNotification::class, new Notification()); //toto treba???
    }
}
