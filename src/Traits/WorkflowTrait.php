<?php

namespace Wuwx\LaravelWorkflow\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Wuwx\LaravelWorkflow\Entities\Workflow;
use Wuwx\LaravelWorkflow\Entities\Process;
use Wuwx\LaravelWorkflow\Entities\Subject;
use Wuwx\LaravelWorkflow\Entities\Place;
use Wuwx\LaravelWorkflow\Entities\History;

trait WorkflowTrait
{

    protected static function bootWorkflowTrait()
    {
        static::creating(function($subject) {
            app('workflow.registry')->get($subject, $subject->workflow_name)->getMarking($subject);
        });
    }

    public function supports(\Symfony\Component\Workflow\Workflow $workflow, $subject)
    {
        return $subject instanceof self;
    }

    public function workflow()
    {
        return $this->belongsTo(Workflow::class, 'workflow_name', 'name');
    }

    public function subjects()
    {
        return $this->morphMany(Subject::class, 'entity');
    }

    public function histories()
    {
        return $this->morphMany(History::class, 'subject');
    }

    public function getMarkingAttribute()
    {
        return $this->workflow_marking;
    }

    public function setMarkingAttribute($value)
    {
        $this->workflow_marking = $value;
    }

    public function getMarking()
    {
        return $this->workflow_marking;
    }

    public function setMarking($value)
    {
        $this->workflow_marking = $value;
    }
}
