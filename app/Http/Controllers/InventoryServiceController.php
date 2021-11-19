<?php

namespace App\Http\Controllers;

use App\Models\InventoryService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            $query->where('name', 'ILIKE', '%' . request('search') . '%')
                ->orWhere('description', 'ILIKE', '%' . request('search') . '%')
                ->orWhere('created_at', 'ILIKE', '%' . request('search') . '%')
                ->orWhere('date_due', 'ILIKE', '%' . request('search') . '%')
                ->orWhere('date_performed', 'ILIKE', '%' . request('search') . '%');
        }

        if (request('filter')) {
            $query->where('is_finished', request('filter'));
        } else
            $query->where('is_finished', false);

        if (request()->has(['field', 'direction'])) {
            $query->orderBy(request('field'), request('direction'));
        } else
            $query->orderBy('name');

        $services = $query->paginate()->withQueryString()
            ->through(fn ($inventoryItem) => [
                'id' => $inventoryItem->id,
                'name' => $inventoryItem->name,
                'description' => $inventoryItem->description,
                'created_at' => $inventoryItem->created_at,
                'date_due' => $inventoryItem->date_due,
                'date_performed' => $inventoryItem->date_performed,
                'notification' => $inventoryItem->notification,
                'is_finished' => $inventoryItem->is_finished,
                'assigned_user' => $inventoryItem->assignedUser ? $inventoryItem->assignedUser->name . ' ' . $inventoryItem->assignedUser->surname : null,
                'performed_by' => $inventoryItem->performedBy ? $inventoryItem->performedBy->name . ' ' . $inventoryItem->performedBy->surname : null,
                'inventory_item_id' => $inventoryItem->item->id,
                'inventory_item_name' => $inventoryItem->item->name
            ]);

        return inertia('Admin/InventoryServices', [
            'services' => $services,
            'filters' => request()->all(['search', 'field', 'direction', 'filter']),
        ]);
    }

    public function finish(Request $request)
    {
        $service = InventoryService::find($request->id);

        $service->update([
            'is_finished' => true,
            'performed_by' => Auth::user()->id,
            'date_performed' => Carbon::now(),
        ]);

        return redirect()->back();
    }
}
