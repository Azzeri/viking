<?php

namespace App\Http\Controllers;

use App\Models\InventoryService;
use Illuminate\Http\Request;

class InventoryServiceController extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', InventoryService::class);

        request()->validate([
            'direction' => ['in:asc,desc'],
            // 'field' => ['in:id,name, privilege_id']
        ]);

        $query = InventoryService::query();

        if (request('search')) {
            $query->where('name', 'ILIKE', '%' . request('search') . '%');
        }

        if (request()->has(['field', 'direction'])) {
            $query->orderBy(request('field'), request('direction'));
        } else
            $query->orderBy('date_due');

        $services = $query->paginate()->withQueryString()
            ->through(fn ($inventoryItem) => [
                'id' => $inventoryItem->id,
                'name' => $inventoryItem->name,
                'description' => $inventoryItem->description,
                'created_at' => $inventoryItem->created_at,
                'date_due' => $inventoryItem->date_due,
                'notification' => $inventoryItem->notification,
                'is_finished' => $inventoryItem->is_finished,
                'inventory_item_id' => $inventoryItem->item->id,
                'inventory_item_name' => $inventoryItem->item->name
            ]);

            return inertia('Admin/InventoryServices', [
            'services' => $services,
            'filters' => request()->all(['search', 'field', 'direction']),
        ]);
    }

    public function finish(Request $request)
    {
        $service = InventoryService::find($request->id);
        $service->is_finished = true;
        $service->save();

        return redirect()->back();
    }

}
