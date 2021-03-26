<?php

namespace Wuwx\LaravelWorkflow\Models;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Workflow\Workflow;
use Carbon\Carbon;
use Wuwx\LaravelWorkflow\Traits\WorkflowTrait;

class Subject extends Model
{
    use WorkflowTrait;

    protected $table = "workflow_subjects";
    protected $fillable = ['process_id'];

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
