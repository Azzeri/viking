<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventTask;
use App\Models\EventTaskState;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
                'name' => strlen($event->name) > 43 ? substr($event->name, 0, 43) . '...' : $event->name,
                'addrTown' => $event->addrTown,
                'date_start' => Carbon::parse($event->date_start)->toFormattedDateString(),
                'date_end' => Carbon::parse($event->date_end)->toFormattedDateString(),
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

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:128', 'unique:events'],
            'date_start' => ['required', 'date', 'after_or_equal:today'],
            'time_start' => ['required', 'date_format:H:i'],
            'date_end' => ['required', 'date', 'after_or_equal:date_start'],
            'time_end' => ['nullable', 'date_format:H:i', 'after_or_equal:time_start'],
            'addrStreet' => ['required', 'min:3', 'max:64'],
            'addrNumber' => ['required', 'alpha_num', 'min:1', 'max:10'],
            'addrPostCode' => ['required', 'alpha_dash', 'min:3', 'max:10'],
            'addrTown' => ['required', 'min:3', 'max:64'],
            'description' => ['required', 'min:3', 'max:512'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:2048'],
            'create_post' => ['required', 'boolean']
        ]);

        $image_path = $request->hasFile('image') ? '/storage/' . $request->file('image')->store('image', 'public') : null;

        $event = Event::create($validated + [
            'photo_path' => $image_path ? $image_path : '/images/default.png'
        ]);

        if ($request->create_post)
            Post::create([
                'title' => 'Nadchodzące wydarzenie: ' . request()->name,
                'body' => request()->description,
                'resource_link' => 'admin/events/' . $event->id,
                'user_id' => Auth::user()->id,
                'photo_path' => $image_path ? $image_path : '/images/default.png'
            ]);

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
        $this->authorize('view', Event::class);

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
            'date_start_parsed' => Carbon::parse($event->date_start)->toFormattedDateString(),
            'date_end_parsed' => Carbon::parse($event->date_end)->toFormattedDateString(),
            'time_start' => substr($event->time_start, 0, 5),
            'time_end' => substr($event->time_end, 0, 5),
            'is_finished' => $event->is_finished,
            'photo_path' => $event->photo_path,
            'participants' => $event->participants ? User::whereIn('id', json_decode($event->participants))->withTrashed()->orderBy('name')->get()->map(fn ($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'surname' => $user->surname,
                'nickname' => $user->nickname,
            ]) : null,
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
        $this->authorize('view', Event::class);

        $eventMapped = array(
            'id' => $event->id,
            'name' => $event->name,
            'is_finished' => $event->is_finished,
        );

        $tasks = $event->tasks ? EventTask::where('event_id', $event->id)->get()->map(fn ($task) => [
            'id' => $task->id,
            'name' => $task->name,
            'description' => $task->description,
            'date_due' => $task->date_due,
            'date_due_formatted' => Carbon::parse($task->date_due)->toFormattedDateString(),
            'event_task_state_id' => $task->event_task_state_id,
            'created_by' => array(
                'id' => $task->user->id,
                'name' => $task->user->getFullName(),
            ),
            'assigned_user' => $task->assigned_user ? array(
                'id' => $task->assignedUser->id,
                'name' => $task->assignedUser->getFullName(),
            ) : null,
            'subtasks' => $task->subtasks ? $task->subtasks->map(fn ($subtask) => [
                'id' => $subtask->id,
                'name' => $subtask->name,
                'is_finished' => $subtask->is_finished,
                'date_due' => $subtask->date_due,
                'date_due_formatted' => Carbon::parse($subtask->date_due)->toFormattedDateString(),
                'date_created' => $subtask->created_at,
                'event_task_id' => $subtask->event_task_id
            ]) : null
        ]) : null;

        $task_states = EventTaskState::all();

        $users = User::get()->map(fn ($user) => [
            'id' => $user->id,
            'name' => $user->getFullName(),
        ]);


        return inertia('Admin/EventTaskManager', [
            'event' => $eventMapped,
            'tasks' => $tasks,
            'users' => $users,
            'task_states' => $task_states
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

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:128', Rule::unique('events')->ignore($event)],
            'date_start' => ['required', 'date', 'after_or_equal:today'],
            'time_start' => ['required', 'date_format:H:i'],
            'date_end' => ['required', 'date', 'after_or_equal:date_start'],
            'time_end' => ['nullable', 'date_format:H:i', 'after_or_equal:time_start'],
            'addrStreet' => ['required', 'min:3', 'max:64'],
            'addrNumber' => ['required', 'alpha_num', 'min:1', 'max:10'],
            'addrPostCode' => ['required', 'alpha_dash', 'min:3', 'max:10'],
            'addrTown' => ['required', 'min:3', 'max:64'],
            'description' => ['required', 'min:3', 'max:512'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:2048'],
            'deleteImage' => ['boolean', 'required']
        ]);

        $image_path = null;
        if ($request->hasFile('image')) {
            $image_path =  '/storage/' . $request->file('image')->store('image', 'public');
            if ($event->photo_path != '/images/default.png')
                Storage::delete('public/' . ltrim($event->photo_path, '/storage'));
        }

        if ($request->deleteImage) {
            Storage::delete('public/' . ltrim($event->photo_path, '/storage'));
            $image_path = '/images/default.png';
        }

        $event->update($validated + [
            'photo_path' => $image_path ? $image_path : $event->photo_path
        ]);

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
        Storage::delete('public/' . ltrim($event->photo_path, '/storage'));

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
                'description_summary' => ['nullable', 'min:3', 'max:512'],
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
            $message = 'Potwierdzono Twój udział w wydarzeniu';
            array_push($participants, Auth::user()->id);
        } else {
            $message = 'Wypisano Cię z wydarzenia';
            unset($participants[array_search(Auth::user()->id, $participants)]);
        }

        $event->update([
            'participants' => $participants
        ]);

        return redirect()->back()->with('message', $message);
    }
}
