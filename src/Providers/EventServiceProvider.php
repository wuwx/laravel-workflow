<?php

namespace Wuwx\LaravelWorkflow\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $subscribe = [

    ];

    public function boot()
    {
        parent::boot();
    }
}
