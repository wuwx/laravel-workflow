<?php

namespace Wuwx\LaravelWorkflow\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Entity;

class Workflow extends Model
{
    protected $table = "workflow_workflows";
    protected $fillable = [
        'name', 'title', 'type', 'initial_place',
    ];
    protected $casts = [
        'marking_store' => 'array',
        'supports' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::created(function($workflow){
            if(empty($workflow->name)) {
                $workflow->name = "workflow_" . $workflow->id;
                $workflow->save();
            }
        });
    }

    public function places()
    {
        return $this->hasMany(Place::class);
    }

    public function transitions()
    {
         return $this->hasMany(Transition::class);
    }

    public function versions()
    {
        return $this->hasMany(Version::class);
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'entity');
    }
}
