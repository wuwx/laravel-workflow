<?php

namespace Wuwx\LaravelWorkflow\Entities;

use Illuminate\Database\Eloquent\Model;
use Silber\Bouncer\Database\Role;

class Place extends Model
{
    protected $table = "workflow_places";
    protected $fillable = [
        'name', 'title', 'description', 'icon', 'color', 'attributes',
    ];
    protected $casts = [
        'attributes' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::created(function($place){
            if(empty($place->name)) {
                $place->name = "place_" . $place->id;
                $place->save();
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

    public function processes()
    {
        return $this->belongsToMany(Process::class, 'workflow_place_process');
    }

    public function getProcessIdsAttribute()
    {
        return $this->processes->pluck('id')->all();
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'workflow_place_role');
    }

    public function getRoleIdsAttribute()
    {
        return $this->roles()->pluck('roles.id')->all();
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'entity');
    }

    public function attributes()
    {
        return $this->morphMany(Attribute::class, 'entity');
    }
}
