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
        $subject = $event->getSubject();

        if ($workflow = Workflow::whereName($event->getWorkflowName())->first()) {
            $place = $workflow->places()->whereIn('name', array_keys($event->getMarking()->getPlaces()))->first();
            $transition = $workflow->transitions()->whereName($event->getTransition()->getName())->first();

            $history = $subject->histories()->make();
            $history->transition_id = $transition->id;
            $history->place_id = $place->id;
            $history->user_id = Auth::id();
            $history->content = Request::input('content');
            $history->save();
        } else {
            $history = $subject->histories()->make();
            $history->workflow_name = $event->getWorkflowName();
            $history->transition_name = $event->getTransition()->getName();
            $history->save();
        }
    }
}
