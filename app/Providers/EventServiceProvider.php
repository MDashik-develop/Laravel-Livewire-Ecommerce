<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\MergeSessionCartIntoDatabase',
        ],
    ];

    public function boot(): void
    {
        parent::boot();
        //
    }
}
