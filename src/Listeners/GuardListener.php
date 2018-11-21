<?php

namespace Wuwx\LaravelWorkflow\Listeners;

use Bouncer;
use ExpressionLanguage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Wuwx\LaravelWorkflow\Entities\Workflow;

class GuardListener
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

            $transition = $workflow->transitions()->whereName($event->getTransition()->getName())->first();

            if ($event->getTransition()->getName() == 'start') {
                return;
            }

            if (!empty($transition->guard) && ExpressionLanguage::evaluate($transition->guard, compact('subject')) !== false) {
                $event->setBlocked(true);
            }

            if (!Bouncer::allows('apply', $transition)) {
                $event->setBlocked(true);
            }
        }
    }
}
