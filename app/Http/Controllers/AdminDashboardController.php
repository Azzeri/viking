<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\InventoryItem;
use App\Models\InventoryService;
use App\Models\Post;
use App\Models\StoreRequest;

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

        return inertia('Admin/Dashboard', ['stats' => $stats]);
    }
}
