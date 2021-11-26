<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Privilege extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public const IS_ADMIN = 1;
    public const IS_COORDINATOR = 2;
}
