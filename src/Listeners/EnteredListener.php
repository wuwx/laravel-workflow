<?php

namespace Wuwx\LaravelWorkflow\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Wuwx\LaravelWorkflow\Entities\Workflow;

class EnteredListener
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
        $subject    = $event->getSubject();
        $workflow   = $event->getWorkflow();
        $transition = $event->getTransition();

        $history = $subject->histories()->make();
        $history->workflow_name = $event->getWorkflowName();
        $history->transition_name = $transition->getName();
        $history->transition_froms = $transition->getFroms();
        $history->transition_tos = $transition->getTos();

        $history->content = Request::input('content');
        $history->user_id = Auth::id();

        $history->save();
    }
}
