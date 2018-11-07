<?php

namespace Wuwx\LaravelWorkflow\Entities;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Workflow\SupportStrategy\SupportStrategyInterface;
use Symfony\Component\Workflow\Workflow;
use Carbon\Carbon;

class Subject extends Model implements SupportStrategyInterface
{
    protected $table = "workflow_subjects";
    protected $fillable = ['process_id'];

    public function supports(Workflow $workflow, $subject)
    {
        return $subject instanceof self;
    }

    protected static function boot()
    {
        parent::boot();
        static::updated(function($subject){
            $entity = $subject->entity;
            $entity->updated_at = Carbon::now();
            $entity->save();
        });
    }

    public function workflow()
    {
        return $this->belongsTo(Workflow::class);
    }

    public function process()
    {
        return $this->belongsTo(Process::class);
    }

    public function parent()
    {
        return $this->belongsTo(Subject::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Subject::class, 'parent_id');
    }

    public function histories()
    {
        return $this->hasMany(History::class);
    }

    public function entity()
    {
        return $this->morphTo();
    }
}
