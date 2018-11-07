<?php

namespace Wuwx\LaravelWorkflow\Entities;

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    protected $table = "workflow_versions";
    protected $fillable = ['name', 'status'];

    public function workflow()
    {
        return $this->belongsTo(Workflow::class);
    }

    public function processes()
    {
        return $this->hasMany(Process::class);
    }
}
