<?php

namespace App\Http\Controllers;

use App\Models\EventSubTask;
use Illuminate\Http\Request;

class EventSubTaskController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', EventSubTask::class);

        EventSubTask::create(
            $request->validate([
                'name' => ['required', 'min:1'],
                'date_due' => ['nullable', 'date', 'after_or_equal:today'],
                'event_task_id' => ['required', 'integer']
            ])
        );

        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EventSubTask  $eventSubTask
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EventSubTask $eventSubTask)
    {
        $this->authorize('update', $eventSubTask, EventSubTask::class);

        $eventSubTask->update(
            $request->validate([
                'name' => ['required', 'min:1'],
                'date_due' => ['nullable', 'date', 'after_or_equal:today'],
                'event_task_id' => ['required', 'integer']
            ])
        );

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EventSubTask  $eventSubTask
     * @return \Illuminate\Http\Response
     */
    public function destroy(EventSubTask $eventSubTask)
    {
        $this->authorize('delete', $eventSubTask, EventSubTask::class);    
        
        $eventSubTask->delete();

        return redirect()->back();
    }

    /**
     * Marks the specified resource as finished.
     *
     * @param  \App\Models\EventSubTask  $eventSubTask
     * @return \Illuminate\Http\Response
     */
    public function finish(EventSubTask $eventSubTask)
    {
        $this->authorize('update', $eventSubTask, EventSubTask::class);

        $state = $eventSubTask->is_finished ? false : true;

        $eventSubTask->update([
            'is_finished' => $state
        ]);

        return redirect()->back();
    }
}
