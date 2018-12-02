<?php

namespace Wuwx\LaravelWorkflow\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
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
        $request    = app('request');
        $subject    = $event->getSubject();
        $workflow   = $event->getWorkflow();
        $transition = $event->getTransition();

        $history = $subject->histories()->make();
        $history->workflow_name = $event->getWorkflowName();
        $history->transition_name = $transition->getName();
        $history->transition_froms = $transition->getFroms();
        $history->transition_tos = $transition->getTos();
        $history->user()->associate($request->user());

        $attributes = [];
        foreach(array_get($workflow->getMetadataStore()->getTransitionMetadata($transition), 'attributes', []) as $attribute) {
            $name = $attribute['name'];
            $attributes[$name] = $request->input($name);
        }
        $history->attributes = $attributes;

        $history->save();
    }
}
