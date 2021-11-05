<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'addrStreet',
        'addrNumber',
        'addrHouseNumber',
        'addrPostcode',
        'addrTown',
        'date_start',
        'date_end',
        'time_start',
        'time_end',
        'is_finished',
        'participants'
    ];
    
}
