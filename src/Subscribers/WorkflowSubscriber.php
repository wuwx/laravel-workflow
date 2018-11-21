<?php

namespace Wuwx\LaravelWorkflow\Subscribers;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
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
        $subject      = $event->getSubject();
        $workflowName = $event->getWorkflowName();

        event(new AnnounceEvent($event));
        event('workflow.announce', $event);
        event(sprintf('workflow.%s.announce', $workflowName), $event);

        foreach ($event->getWorkflow()->getEnabledTransitions($subject) as $transition) {
            event(sprintf('workflow.%s.announce.%s', $workflowName, $transition->getName()), $event);
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
