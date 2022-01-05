<?php

namespace App\Http\Controllers\FrontSite;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Event;
use Carbon\Carbon;

class EventsController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('date_start', 'desc')->paginate(30)
            ->through(fn ($event) => [
                'id' => $event->id,
                'name' => $event->name,
                'description' => strlen($event->description) > 255 ? substr($event->description, 0, 255) . '...' : $event->description,
                'addrTown' => $event->addrTown,
                'date_start' => Carbon::parse($event->date_start)->toFormattedDateString(),
                'date_end' => Carbon::parse($event->date_end)->toFormattedDateString(),
                'is_finished' => $event->is_finished,
                'photo_path' => $event->photo_path
        ]);
        
        return inertia('Events', [
            'events' => $events,
        ]);
    }
}
