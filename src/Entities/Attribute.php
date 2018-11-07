<?php

namespace Wuwx\LaravelWorkflow\Entities;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $table = "workflow_attributes";
    protected $fillable = ['name', 'type', 'options'];

    public function entity()
    {
        return $this->morphTo();
    }
}
