<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;

class EventTask extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $fillable = ['name', 'description', 'date_due', 'user_id', 'event_id', 'event_task_state_id', 'assigned_user'];
    protected $cascadeDeletes = ['subtasks'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function subtasks()
    {
        return $this->hasMany(EventSubTask::class)->orderBy('name');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user')->withTrashed();
    }
}
