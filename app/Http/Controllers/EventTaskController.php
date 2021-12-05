<?php

namespace App\Http\Controllers;

use App\Models\EventTask;
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
}
