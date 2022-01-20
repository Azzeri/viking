<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;
    protected $cascadeDeletes = ['tasks'];

    protected $fillable = [
        'name',
        'description',
        'description_summary',
        'addrStreet',
        'addrNumber',
        'addrPostCode',
        'addrTown',
        'date_start',
        'date_end',
        'time_start',
        'time_end',
        'is_finished',
        'photo_path',
        'is_public'
    ];
    
    public function tasks() 
    {
        return $this->hasMany(EventTask::class);
    }

    public function participants()
    {
        return $this->belongsToMany(User::class);
    }
}