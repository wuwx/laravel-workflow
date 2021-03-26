<?php

namespace Wuwx\LaravelWorkflow\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Wuwx\LaravelWorkflow\Models\History;

class TransitionListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $history = new History();
        $history->subject()->associate($event->getSubject());
        $history->workflow_name = $event->getWorkflowName();
        $history->transition_name = $event->getTransition()->getName();
        $history->save();
    }
}
