<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\InventoryService;
use App\Models\Privilege;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
                'assigned_user_id' => $inventoryItem->assignedUser ? $inventoryItem->assignedUser->id : null,
                'performed_by' => $inventoryItem->performedBy ? $inventoryItem->performedBy->name . ' ' . $inventoryItem->performedBy->surname : null,
                'inventory_item_id' => $inventoryItem->item->id,
                'inventory_item_name' => $inventoryItem->item->name
            ]);

        $items = InventoryItem::all()->map(fn ($item) => [
            'id' => $item->id,
            'name' => $item->name,
        ]);

        $users = User::all()->map(fn ($user) => [
            'id' => $user->id,
            'name' => $user->name . ' ' . $user->surname,
        ]);

        $sortedItems = $items->sortBy('name');

        return inertia('Admin/InventoryServices', [
            'services' => $services,
            'items' => $sortedItems,
            'users' => $users,
            'filters' => request()->all(['search', 'field', 'direction', 'filter']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', InventoryService::class);

        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:64', 'unique:inventory_services'],
            'description' => ['nullable', 'min:3', 'max:255'],
            'date_due' => ['nullable', 'date', 'after:today'],
            'inventory_item_id' => ['required', 'integer'],
            'assigned_user' => ['required', 'integer'],
            'notification' => ['boolean'],
        ]);

        $assignedUser = Auth::user()->privilege_id == Privilege::IS_ADMIN ? $request->assigned_user : Auth::user()->id;

        InventoryService::create([
            'name' => $request->name,
            'description' => $request->description,
            'date_due' => $request->date_due,
            'inventory_item_id' => $request->inventory_item_id,
            'assigned_user' => $assignedUser,
            'notification' => $request->notification
        ]);

        return redirect()->back()->with('message', 'Pomyślnie dodano serwis');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InventoryService  $InventoryService
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InventoryService $inventoryService)
    {
        $this->authorize('update', $inventoryService, InventoryService::class);

        $inventoryService = InventoryService::find($request->id);

        $inventoryService->update($request->validate([
            'name' => ['required', 'string', 'min:3', 'max:64'], Rule::unique('inventory_services')->ignore(InventoryService::find($inventoryService->id)),
            'description' => ['nullable', 'min:3', 'max:255'],
            'date_due' => ['nullable', 'date', 'after:today'],
            'inventory_item_id' => ['required', 'integer'],
            'assigned_user' => ['required', 'integer'],
            'notification' => ['boolean'],
        ]));

        return redirect()->back()->with('message', 'Pomyślnie zaktualizowano serwis');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InventoryService  $InventoryService
     * @return \Illuminate\Http\Response
     */
    public function destroy($inventoryService)
    {
        $service = InventoryService::find($inventoryService);
        $this->authorize('delete', $service, InventoryService::class);
        
        $service->delete();

        return redirect()->back()->with('message', 'Pomyślnie usunięto serwis');
    }

    public function finish(Request $request)
    {
        $this->authorize('create', InventoryService::class); //tak wiem, pojde za to do piekła. Zmienić
        
        $service = InventoryService::find($request->id);

        $service->update([
            'is_finished' => true,
            'performed_by' => Auth::user()->id,
            'date_performed' => Carbon::now(),
        ]);

        return redirect()->back();
    }
}
