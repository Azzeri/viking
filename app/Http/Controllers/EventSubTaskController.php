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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EventSubTask  $eventSubTask
     * @return \Illuminate\Http\Response
     */
    public function destroy(EventSubTask $eventSubTask)
    {
        //
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
