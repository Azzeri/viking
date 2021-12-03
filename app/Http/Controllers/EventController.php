<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\InventoryItem;
use App\Models\User;
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
            'field' => ['in:id,name,addrTown,date_start'],
            'filter' => ['in:1,2']
        ]);

        $query = Event::query();

        if (request('search')) {
            $query->where('name', 'ILIKE', '%' . request('search') . '%')
                ->orWhere('addrTown', 'ILIKE', '%' . request('search') . '%');
        }

        if (request('filter')) 
            $query->where('is_finished', request('filter'));
        else
            $query->where('is_finished', false);

        if (request()->has(['field', 'direction'])) {
            $query->orderBy(request('field'), request('direction'));
        } else
            $query->orderBy('id');

        $events = $query->paginate()->withQueryString()
            ->through(fn ($event) => [
                'id' => $event->id,
                'name' => $event->name,
                'addrTown' => $event->addrTown,
                'date_start' => $event->date_start,
                'date_end' => $event->date_end,
                'is_finished' => $event->is_finished,
            ]);


        // $event = Event::where('items', '!=', null)->first();
        // $encoded = json_decode($event->items);
        // dd(InventoryItem::whereIn('id', $encoded )->get());

        return inertia('Admin/Events', [
            'events' => $events,
            'filters' => request()->all(['search', 'field', 'direction', 'filter']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        $eventMapped = array(
            'id' => $event->id,
            'name' => $event->name,
            'description' => $event->description,
            'description_summary' => $event->description_summary,
            'addrStreet' => $event->addrStreet,
            'addrNumber' => $event->addrNumber,
            'addrHouseNumber' => $event->addrHouseNumber,
            'addrPostCode' => $event->addrPostCode,
            'addrTown' => $event->addrTown,
            'date_start' => $event->date_start,
            'date_end' => $event->date_end,
            'time_start' => $event->time_start,
            'time_end' => $event->time_end,
            'is_finished' => $event->is_finished,
            'participants' => User::whereIn('id', json_decode($event->items))->orderBy('name')->get()->map(fn ($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'surname' => $user->surname,
                'nickname' => $user->nickname,
            ]),
            'items' => InventoryItem::whereIn('id', json_decode($event->items))->orderBy('name')->get()->map(fn ($item) => [
                'id' => $item->id,
                'name' => $item->name,
            ])
        );

        return inertia('Admin/EventDetails', [
            'event' => $eventMapped,
        ]);
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
