<?php

namespace App\Http\Controllers;

use App\Models\EventTask;
use App\Models\EventTaskState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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

        $validated = $request->validate([
            'event_id' => ['required', 'integer', Rule::exists('events', 'id')->where(function ($query) {
                return $query->where('is_finished', false);
            })],
            'name' => ['required', 'string', 'min:3', 'max:128'],
            'description' => ['nullable', 'min:3', 'max:255'],
            'date_due' => ['nullable', 'date'],
            'event_task_state_id' => ['required', 'integer'],
            'assigned_user' => ['nullable', 'integer', 'exists:users,id']
        ]);

        EventTask::create($validated + [
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
        $this->authorize('update', $eventTask, EventTask::class);

        $eventTask->update(
            $request->validate([
                'name' => ['required', 'string', 'min:3', 'max:128'],
                'description' => ['nullable', 'min:3', 'max:255'],
                'date_due' => ['nullable', 'date'],
                'event_id' => ['required', 'integer', Rule::exists('events', 'id')->where(function ($query) {
                    return $query->where('is_finished', false);
                })],
                'assigned_user' => ['nullable', 'integer', 'exists:users,id']
            ])
        );

        return redirect()->back()->with('message', 'Pomyślnie zaktualizowano zadanie');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EventTask  $eventTask
     * @return \Illuminate\Http\Response
     */
    public function destroy(EventTask $eventTask)
    {
        $this->authorize('delete', $eventTask, EventTask::class);

        $eventTask->delete();

        return redirect()->back()->with('message', 'Pomyślnie usunięto zadanie');
    }

    /**
     * Change the state of the specified event task.
     *
     * @param  \App\Models\EventTask  $eventTask
     * @return \Illuminate\Http\Response
     */
    public function change_state(EventTask $eventTask, EventTaskState $eventTaskState)
    {
        $this->authorize('update', $eventTask, EventTask::class);
        
        $eventTask->update([
            'event_task_state_id' => $eventTaskState->id
        ]);

        return redirect()->back();
    }
}
