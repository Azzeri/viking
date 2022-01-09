<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\EventTask;
use App\Models\InventoryItem;
use App\Models\InventoryService;
use App\Models\Post;
use App\Models\StoreRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $stats = [
            ['label' => 'Członków grupy', 'value' => User::count()],
            ['label' => 'Zakończonych wydarzeń', 'value' => Event::where('is_finished', true)->count()],
            ['label' => 'Zrealizowanych zamówień', 'value' => StoreRequest::where('is_finished', true)->count()],
            ['label' => 'Przedmiotów w grupie', 'value' => InventoryItem::count()],
            ['label' => 'Wykonanych serwisów', 'value' => InventoryService::where('is_finished', true)->count()],
            ['label' => 'Umieszczonych postów', 'value' => Post::count()],
        ];

        $eventTasks = EventTask::where('assigned_user', Auth::user()->id)->where('event_task_state_id', '!=', 4)->where('event_task_state_id', '!=', 5)->get()->map(fn ($task) => [
            'name' => $task->name,
            'date_due' => $task->date_due ? Carbon::parse($task->date_due)->toFormattedDateString() : 'Nie określono',
            'description' => $task->description,
            'event' => $task->event->name
        ]);

        $inventoryTasks = InventoryService::where('assigned_user', Auth::user()->id)->where('is_finished', false)->get()->map(fn ($task) => [
            'name' => $task->name,
            'date_due' => $task->date_due ? Carbon::parse($task->date_due)->toFormattedDateString() : 'Nie określono',
            'description' => $task->description,
            'item' => $task->item->name
        ]);

        return inertia('Admin/Dashboard', ['stats' => $stats, 'eventTasks' => $eventTasks, 'inventoryTasks' => $inventoryTasks]);
    }
}
