<?php

namespace App\Http\Controllers;

use App\Models\EventTask;
use App\Models\EventTaskState;
use Illuminate\Http\Request;

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
        //
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
