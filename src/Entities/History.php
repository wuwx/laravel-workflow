<?php

namespace Wuwx\LaravelWorkflow\Entities;

use Illuminate\Database\Eloquent\Model;
use App\User;

class History extends Model
{
    protected $table = "workflow_histories";
    protected $fillable = [];
    protected $casts = [
        'transition_froms' => 'array',
        'transition_tos' => 'array',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function transition()
    {
        return $this->belongsTo(Transition::class);
    }

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
