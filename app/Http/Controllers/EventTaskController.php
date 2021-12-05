<?php

namespace App\Http\Controllers;

use App\Models\EventTask;
use App\Models\EventTaskState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventTaskController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', EventTask::class);

        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:128'],
            'description' => ['nullable', 'min:3', 'max:255'],
            'date_due' => ['nullable', 'date', 'after_or_equal:today'],
            'event_id' => ['required', 'integer'],
            'event_task_state_id' => ['required', 'integer'],
        ]);

        EventTask::create([
            'name' => $request['name'],
            'description' => $request['description'],
            'date_due' => $request['date_due'],
            'event_id' => $request['event_id'],
            'event_task_state_id' => $request['event_task_state_id'],
            'user_id' => Auth::user()->id,
        ]);

        return redirect()->back()->with('message', 'Pomyślnie dodano zadanie');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EventTask  $eventTask
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EventTask $eventTask)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EventTask  $eventTask
     * @return \Illuminate\Http\Response
     */
    public function destroy(EventTask $eventTask)
    {
        //
    }

    /**
     * Change the state of the specified event task.
     *
     * @param  \App\Models\EventTask  $eventTask
     * @return \Illuminate\Http\Response
     */
    public function change_state($eventTask, EventTaskState $eventTaskState)
    {
        $task = EventTask::find($eventTask);

        $this->authorize('update', $task, EventTask::class);

        $task->update([
            'event_task_state_id' => $eventTaskState->id
        ]);

        return redirect()->back();
    }
}
