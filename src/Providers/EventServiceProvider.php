<?php

namespace Wuwx\LaravelWorkflow\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Wuwx\LaravelWorkflow\Events\GuardEvent;
use Wuwx\LaravelWorkflow\Listeners\AnnounceListener;
use Wuwx\LaravelWorkflow\Listeners\CompletedListener;
use Wuwx\LaravelWorkflow\Listeners\EnteredListener;
use Wuwx\LaravelWorkflow\Listeners\EnterListener;
use Wuwx\LaravelWorkflow\Listeners\GuardListener;
use Wuwx\LaravelWorkflow\Listeners\LeaveListener;
use Wuwx\LaravelWorkflow\Listeners\TransitionListener;

class EventServiceProvider extends ServiceProvider
{

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        "workflow.guard"      => [GuardListener::class],
        'workflow.leave'      => [LeaveListener::class],
        'workflow.transition' => [TransitionListener::class],
        'workflow.enter'      => [EnterListener::class],
        'workflow.entered'    => [EnteredListener::class],
        'workflow.completed'  => [CompletedListener::class],
        'workflow.announce'   => [AnnounceListener::class],
    ];

    protected $subscribe = [

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
