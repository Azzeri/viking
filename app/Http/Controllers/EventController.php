<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', Event::class);

        request()->validate([
            'direction' => ['in:asc,desc'],
            // 'field' => ['in:id,name, privilege_id']
        ]);

        $query = Event::query();

        if (request('search')) {
            $query->where('name', 'ILIKE', '%' . request('search') . '%')
                ->orWhere('addrTown', 'ILIKE', '%' . request('search') . '%')
                ->orWhere('description', 'ILIKE', '%' . request('search') . '%');
        }

        if (request()->has(['field', 'direction'])) {
            $query->orderBy(request('field'), request('direction'));
        } else
            $query->orderBy('date_start');


        $events = $query->paginate()->withQueryString()
            ->through(fn ($event) => [
                'id' => $event->id,
                'name' => $event->name,
                'description' => $event->description,
                'addrStreet' => $event->addrStreet,
                'addrNumber' => $event->addrNumber,
                'addrHouseNumber' => $event->addrHouseNumber,
                'addrPostcode' => $event->addrPostcode,
                'addrTown' => $event->addrTown,
                'date_start' => $event->date_start,
                'date_end' => $event->date_end,
                'time_start' => $event->time_start,
                'time_end' => $event->time_end,
                'is_finished' => $event->is_finished,
                'participants' => $event->participants,
            ]);

        return inertia('Admin/Events', [
            'events' => $events,
            'filters' => request()->all(['search', 'field', 'direction']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        //
    }
}
