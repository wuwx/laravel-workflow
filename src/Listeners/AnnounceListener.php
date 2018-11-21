<?php

namespace Wuwx\LaravelWorkflow\Listeners;

use ExpressionLanguage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class AnnounceListener
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
        $workflow = $event->getWorkflow();
        $transition = $event->getTransition();

        // Process Notifications;
        foreach(array_get($workflow->getMetadataStore()->getWorkflowMetadata(), 'notifications', []) as $notification) {
            $notifiables = ExpressionLanguage::evaluate($notification->notifiables, compact('subject'));
            Notification::sendNow($notifiables, new $notification->name, $notification->channels);
        }

        // Transition Notifications
        foreach(array_get($workflow->getMetadataStore()->getTransitionMetadata($transition), 'notifications', []) as $notification) {
            $notifiables = ExpressionLanguage::evaluate($notification->notifiables, compact('subject'));
            Notification::sendNow($notifiables, new $notification->name, $notification->channels);
        }

        foreach ($workflow->getEnabledTransitions($subject) as $transition) {
            if (ExpressionLanguage::evaluate(array_get($workflow->getMetadataStore()->getTransitionMetadata($transition), 'automatic'), compact('subject')) === true) {
                #TODO: 自动执行的时候，可能需要无视权限
                $workflow->apply($subject, $transition->getName());
                $subject->save();
                break;
            }
        }
    }
}
