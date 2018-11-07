<?php

namespace Wuwx\LaravelWorkflow\Entities;

use Illuminate\Database\Eloquent\Model;
use Silber\Bouncer\Database\Role;

class Transition extends Model
{
    protected $table = "workflow_transitions";
    protected $fillable = [
        'name', 'title', 'description', 'froms', 'tos', 'guard', 'automatic',
    ];
    protected $casts = [
        'froms' => 'array',
        'tos' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::created(function($transition){
            if(empty($transition->name)) {
                $transition->name = "transition_" . $transition->id;
                $transition->save();
            }
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

    public function attributes()
    {
        return $this->morphMany(Attribute::class, 'entity');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'workflow_role_transition');
    }

    public function processes()
    {
        return $this->belongsToMany(Process::class, 'workflow_process_transition');
    }

    public function getRoleIdsAttribute()
    {
        return $this->roles()->pluck('roles.id')->all();
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'entity');
    }
}
