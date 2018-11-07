<?php

namespace Wuwx\LaravelWorkflow\Entities;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = "workflow_notifications";
    protected $fillable = ['name', 'notifiables'];
    protected $casts = [
        'channels' => 'array',
    ];

    public function process()
    {
        return $this->belongsTo(Process::class);
    }

    public function workflow()
    {
        return $this->belongsTo(Workflow::class);
    }
}
