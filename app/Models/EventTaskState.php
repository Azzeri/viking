<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTaskState extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public const AWAITING = 1;
    public const UNPERFORMED = 2;
    public const IN_PROGRESS = 3;
    public const PERFORMED = 4;
    public const ABANDONED = 5;
}
