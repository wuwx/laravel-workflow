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
        $workflowName   = $event->getWorkflowName();
        $transitionName = $event->getTransition()->getName();

        event(new GuardEvent($event));
        event('workflow.guard', $event);
        event(sprintf('workflow.%s.guard', $workflowName), $event);
        event(sprintf('workflow.%s.guard.%s', $workflowName, $transitionName), $event);

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
        $places       = $event->getTransition()->getFroms();
        $workflowName = $event->getWorkflowName();

        event(new LeaveEvent($event));
        event('workflow.leave', $event);
        event(sprintf('workflow.%s.leave', $workflowName), $event);

        foreach ($places as $place) {
            event(sprintf('workflow.%s.leave.%s', $workflowName, $place), $event);
        }
    }

    public function onTransition($event)
    {
        $workflowName   = $event->getWorkflowName();
        $transitionName = $event->getTransition()->getName();

        event(new TransitionEvent($event));
        event('workflow.transition', $event);
        event(sprintf('workflow.%s.transition', $workflowName), $event);
        event(sprintf('workflow.%s.transition.%s', $workflowName, $transitionName), $event);
    }

    public function onEnter($event)
    {
        $places       = $event->getTransition()->getTos();
        $workflowName = $event->getWorkflowName();

        event(new EnterEvent($event));
        event('workflow.enter', $event);
        event(sprintf('workflow.%s.enter', $workflowName), $event);

        foreach ($places as $place) {
            event(sprintf('workflow.%s.enter.%s', $workflowName, $place), $event);
        }
    }

    public function onEntered($event)
    {
        $places       = $event->getTransition()->getTos();
        $workflowName = $event->getWorkflowName();

        event(new EnteredEvent($event));
        event('workflow.entered', $event);
        event(sprintf('workflow.%s.entered', $workflowName), $event);

        foreach ($places as $place) {
            event(sprintf('workflow.%s.entered.%s', $workflowName, $place), $event);
        }

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

    public function onCompleted($event)
    {
        $workflowName   = $event->getWorkflowName();
        $transitionName = $event->getTransition()->getName();

        event(new CompletedEvent($event));
        event('workflow.completed', $event);
        event(sprintf('workflow.%s.completed', $workflowName), $event);
        event(sprintf('workflow.%s.completed.%s', $workflowName, $transitionName), $event);
    }

    public function onAnnounce($event)
    {
        $workflowName   = $event->getWorkflowName();

        event(new AnnounceEvent($event));
        event('workflow.announce', $event);
        event(sprintf('workflow.%s.announce', $workflowName), $event);

        $subject = $event->getSubject();
        foreach ($event->getWorkflow()->getEnabledTransitions($subject) as $transition) {
            event(sprintf('workflow.%s.announce.%s', $workflowName, $transition->getName()), $event);
        }

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
