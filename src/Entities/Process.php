<?php

namespace Wuwx\LaravelWorkflow\Entities;

use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    protected $table = "workflow_processes";
    protected $fillable = ['title', 'type'];

    protected static function boot()
    {
        parent::boot();
        static::created(function($process){
            $process->name = "process_" . $process->id;
            $process->save();
        });
    }

    public function version()
    {
        return $this->belongsTo(Version::class);
    }

    public function places()
    {
        return $this->hasMany(Place::class);
    }

    public function transitions()
    {
         return $this->hasMany(Transition::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'entity');
    }
}
