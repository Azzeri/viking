<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSubTask extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'date_due', 'is_finished', 'event_task_id'];

    public function task()
    {
        return $this->belongsTo(EventTask::class, 'event_task_id');
    }
}
