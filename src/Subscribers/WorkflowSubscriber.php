<?php

namespace Wuwx\LaravelWorkflow\Subscribers;

use Bouncer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Request;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use ExpressionLanguage;
use Wuwx\LaravelWorkflow\Entities\Workflow;
use Wuwx\LaravelWorkflow\Entities\Place;
use Wuwx\LaravelWorkflow\Entities\Transition;
use Wuwx\LaravelWorkflow\Entities\History;

use Wuwx\LaravelWorkflow\Events\GuardEvent;
use Wuwx\LaravelWorkflow\Events\LeaveEvent;
use Wuwx\LaravelWorkflow\Events\TransitionEvent;
use Wuwx\LaravelWorkflow\Events\EnterEvent;
use Wuwx\LaravelWorkflow\Events\EnteredEvent;
use Wuwx\LaravelWorkflow\Events\CompletedEvent;
use Wuwx\LaravelWorkflow\Events\AnnounceEvent;

class WorkflowSubscriber implements EventSubscriberInterface
{

    public function onGuard($event)
    {
        event(new GuardEvent);
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

    public function onLeave($event)
    {
        event(new LeaveEvent);
    }

    public function onTransition($event)
    {
        event(new TransitionEvent);
    }

    public function onEnter($event)
    {
        event(new EnterEvent);
    }

    public function onEntered($event)
    {
        event(new EnteredEvent);
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
            $workflow = $event->getWorkflow();
        }

    }

    public function onCompleted($event)
    {
        event(new CompletedEvent);
    }

    public function onAnnounce($event)
    {
        event(new AnnounceEvent);
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

    public static function getSubscribedEvents()
    {
        return [
            'workflow.guard'      => ['onGuard'],
            'workflow.leave'      => ['onLeave'],
            'workflow.transition' => ['onTransition'],
            'workflow.enter'      => ['onEnter'],
            'workflow.entered'    => ['onEntered'],
            'workflow.completed'  => ['onCompleted'],
            'workflow.announce'   => ['onAnnounce'],
        ];
    }
}
