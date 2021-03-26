<?php

namespace Wuwx\LaravelWorkflow\Http\Livewire;

use Livewire\Component;

class Workflow extends Component
{

    public $subject;
    public $modal = false;

    public function mount($subject)
    {
        $this->subject = $subject;
    }

    public function apply($transitionName)
    {
        $workflow = app('workflow.registry')->get($this->subject, $this->subject->workflow_name);
        $workflow->apply($this->subject, $transitionName);
        $this->subject->save();
    }

    public function render()
    {
        $workflow = app('workflow.registry')->get($this->subject, $this->subject->workflow_name);

        if (config('workflow.debug')) {
            logger($workflow->getMetadataStore()->getWorkflowMetadata());
            logger($this->subject->marking);
            logger($workflow->getMetadataStore()->getPlaceMetadata('place_3'));
        }

        $transitions = $workflow->getEnabledTransitions($this->subject);

        foreach($transitions as $transition) {
            if (config("workflow.debug")) {
                logger($workflow->getMetadataStore()->getTransitionMetadata($transition));
            }
        }

        return view('workflow::livewire.workflow', compact('workflow', 'transitions'));
    }
}
