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
        $subject    = $event->getSubject();
        $workflow   = $event->getWorkflow();
        $transition = $event->getTransition();
        $metadata   = $workflow->getMetadataStore()->getTransitionMetadata($transition);
        $guard      = array_get($metadata, 'guard');

        # TODO: 一些自动化操作需要把权限处理掉
        if ($event->getTransition()->getName() == 'start') {
            return;
        }

        if (!empty($guard) && ExpressionLanguage::evaluate($guard, compact('subject')) !== false) {
            $event->setBlocked(true);
        }

        #TODO: 需要处理好与 Bouncer 的关系
        //if (!Bouncer::allows('apply', $transition)) {
        //    $event->setBlocked(true);
        //}
    }
}
