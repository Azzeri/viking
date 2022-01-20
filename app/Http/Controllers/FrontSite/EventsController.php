<?php

namespace App\Http\Controllers\FrontSite;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Carbon\Carbon;

class EventsController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('date_start', 'desc')->where('is_public', true)->paginate(30)
            ->through(fn ($event) => [
                'id' => $event->id,
                'name' => $event->name,
                'description' => strlen($event->description) > 255 ? substr($event->description, 0, 255) . '...' : $event->description,
                'addrTown' => $event->addrTown,
                'date_start' => Carbon::parse($event->date_start)->locale('pl')->isoFormat('Do MMM YYYY'),
                'date_end' => Carbon::parse($event->date_end)->locale('pl')->isoFormat('Do MMM YYYY'),
                'is_finished' => $event->is_finished,
                'photo_path' => $event->photo_path
            ]);

        return inertia('Events', [
            'events' => $events,
        ]);
    }

    public function show(Event $event)
    {
        if ($event->is_public == false)
            abort(403);

        $mapped = array(
            'id' => $event->id,
            'name' => $event->name,
            'description' => $event->description,
            'description_summary' => $event->description_summary,
            'addrStreet' => $event->addrStreet,
            'addrNumber' => $event->addrNumber,
            'addrPostCode' => $event->addrPostCode,
            'addrTown' => $event->addrTown,
            'date_start' => Carbon::parse($event->date_start)->locale('pl')->isoFormat('Do MMM YYYY'),
            'date_end' => Carbon::parse($event->date_end)->locale('pl')->isoFormat('Do MMM YYYY'),
            'time_start' => substr($event->time_start, 0, 5),
            'time_end' => substr($event->time_end, 0, 5),
            'is_finished' => $event->is_finished,
            'photo_path' => $event->photo_path
        );

        return inertia('EventDetails', [
            'event' => $mapped,
        ]);
    }
}
