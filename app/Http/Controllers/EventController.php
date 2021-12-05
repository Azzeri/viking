<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventTask;
use App\Models\InventoryItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
            'filter' => ['in:0,1']
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
        $this->authorize('create', Event::class);

        Event::create(
            $request->validate([
                'name' => ['required', 'string', 'min:3', 'max:64', 'unique:events'],
                'date_start' => ['required', 'date', 'after_or_equal:today'],
                'time_start' => ['required', 'date_format:H:i'],
                'date_end' => ['required', 'date', 'after_or_equal:date_start'],
                'time_end' => ['nullable', 'date_format:H:i', 'after_or_equal:time_start'],
                'addrStreet' => ['required'],
                'addrNumber' => ['required', 'alpha_num'],
                'addrPostCode' => ['required', 'alpha_dash'],
                'addrTown' => ['required'],
                'description' => ['required', 'min:3', 'max:255']
            ])
        );

        return redirect()->back()->with('message', 'Pomyślnie utworzono wydarzenie');
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
            'addrPostCode' => $event->addrPostCode,
            'addrTown' => $event->addrTown,
            'date_start' => $event->date_start,
            'date_end' => $event->date_end,
            'time_start' => $event->time_start,
            'time_end' => $event->time_end,
            'is_finished' => $event->is_finished,
            'participants' => $event->participants ? User::whereIn('id', json_decode($event->participants))->orderBy('name')->get()->map(fn ($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'surname' => $user->surname,
                'nickname' => $user->nickname,
            ]) : null,
            'items' => $event->items ? InventoryItem::whereIn('id', json_decode($event->items))->orderBy('name')->get()->map(fn ($item) => [
                'id' => $item->id,
                'name' => $item->name,
            ]) : null
        );

        return inertia('Admin/EventDetails', [
            'event' => $eventMapped,
        ]);
    }

    /**
     * Display the specified resource task manager
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function taskManager(Event $event)
    {
        $eventMapped = array(
            'id' => $event->id,
            'name' => $event->name,
            'is_finished' => $event->is_finished,
        );

        $tasks = $event->tasks ? EventTask::where('event_id', $event->id)->orderBy('name')->get()->map(fn ($task) => [
            'id' => $task->id,
            'name' => $task->name,
        ]) : null;

        return inertia('Admin/EventTaskManager', [
            'event' => $eventMapped,
            'tasks' => $tasks
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
        $this->authorize('update', $event, Event::class);

        $event->update(
            $request->validate([
                'name' => ['required', 'string', 'min:3', 'max:64', Rule::unique('events')->ignore($event)],
                'date_start' => ['required', 'date', 'after_or_equal:today'],
                'time_start' => ['required', 'date_format:H:i'],
                'date_end' => ['required', 'date', 'after_or_equal:date_start'],
                'time_end' => ['nullable', 'date_format:H:i', 'after_or_equal:time_start'],
                'addrStreet' => ['required'],
                'addrNumber' => ['required', 'alpha_num'],
                'addrPostCode' => ['required', 'alpha_dash'],
                'addrTown' => ['required'],
                'description' => ['required', 'min:3', 'max:255']
            ])
        );

        return redirect()->back()->with('message', 'Pomyślnie zaktualizowano wydarzenie');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        $this->authorize('delete', $event, Event::class);

        $event->delete();

        return redirect()->route('admin.events.index')->with('message', 'Pomyślnie usunięto wydarzenie');
    }

    /**
     * Marks the specified event as finished
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function finish(Request $request, Event $event)
    {
        $this->authorize('update', $event, Event::class);

        $event->update(
            $request->validate([
                'description_summary' => ['required', 'min:3', 'max:255'],
                'is_finished' => ['required', 'boolean', 'accepted']
            ])
        );

        return redirect()->back()->with('message', 'Pomyślnie podsumowano wydarzenie');
    }

    /**
     * Adds authenticated user to event's participants list
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function confirm_participation(Event $event)
    {
        $this->authorize('update', $event, Event::class);

        $participants = $event->participants ? json_decode($event->participants) : array();

        if (!in_array(Auth::user()->id, $participants)) {
            array_push($participants, Auth::user()->id);
            $event->update([
                'participants' => $participants
            ]);

            return redirect()->back()->with('message', 'Potwierdzono Twój udział w wydarzeniu');
        } else {
            unset($participants[array_search(Auth::user()->id, $participants)]);
            $event->update([
                'participants' => $participants
            ]);

            return redirect()->back()->with('message', 'Wypisano Cię z wydarzenia');
        }
    }
}
