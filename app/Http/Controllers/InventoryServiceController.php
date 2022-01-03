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
            'field' => ['in:date_due,name,inventory_item_id']
        ]);

        $query = InventoryService::query();

        if (request('search')) {
            $query->where('name', 'ILIKE', '%' . request('search') . '%')
                ->orWhere('description', 'ILIKE', '%' . request('search') . '%')
                ->orWhere('created_at', 'ILIKE', '%' . request('search') . '%')
                ->orWhere('date_due', 'ILIKE', '%' . request('search') . '%')
                ->orWhere('date_performed', 'ILIKE', '%' . request('search') . '%');
        }

        switch (request('filter')) {
            case 0:
                $query->where('assigned_user', null)->where('is_finished', false);
                break;
            case 1:
                $query->where('assigned_user', '!=', null)->where('is_finished', false);
                break;
            case 2:
                $query->where('assigned_user', Auth::user()->id)->where('is_finished', false);
                break;
            case 3:
                $query->where('is_finished', true);
                break;
            default:
                $query->where('assigned_user', null)->where('is_finished', false);
                break;
        }

        if (request()->has(['field', 'direction'])) {
            if (request('field') == 'inventory_item_id')
                $query->orderBy(InventoryItem::select('name')->whereColumn('inventory_items.id', 'inventory_services.inventory_item_id'), request('direction'));
            else
                $query->orderBy(request('field'), request('direction'));
        } else
            $query->orderBy('date_due', 'desc');

        $services = $query->paginate(10)->withQueryString()
            ->through(fn ($inventoryItem) => [
                'id' => $inventoryItem->id,
                'name' => $inventoryItem->name,
                'description' => $inventoryItem->description,

                'created_at_formatted' => Carbon::parse($inventoryItem->created_at)->toFormattedDateString(),
                'date_due' => $inventoryItem->date_due,
                'date_due_formatted' => Carbon::parse($inventoryItem->date_due)->toFormattedDateString(),
                'date_performed_formatted' => Carbon::parse($inventoryItem->date_performed)->toFormattedDateString(),

                'notification' => $inventoryItem->notification,
                'is_finished' => $inventoryItem->is_finished,

                'created_by' => array(
                    'id' => $inventoryItem->createdBy->id,
                    'name' => $inventoryItem->createdBy->name,
                    'nickname' => $inventoryItem->createdBy->nickname,
                    'surname' => $inventoryItem->createdBy->surname
                ),

                'assigned_user' => $inventoryItem->assigned_user
                    ? array(
                        'id' => $inventoryItem->assignedUser->id,
                        'name' => $inventoryItem->assignedUser->name,
                        'nickname' => $inventoryItem->assignedUser->nickname,
                        'surname' => $inventoryItem->assignedUser->surname
                    )
                    : null,

                'performed_by' => $inventoryItem->performed_by
                    ? array(
                        'id' => $inventoryItem->performedBy->id,
                        'name' => $inventoryItem->performedBy->name,
                        'nickname' => $inventoryItem->performedBy->nickname,
                        'surname' => $inventoryItem->performedBy->surname
                    )
                    : null,

                'inventory_item' => array(
                    'id' => $inventoryItem->item->id,
                    'name' => $inventoryItem->item->name,
                )
            ]);

        $items = InventoryItem::orderBy('name')->get()->map(fn ($item) => [
            'id' => $item->id,
            'name' => $item->name,
        ]);

        $users = User::orderBy('name')->get()->map(fn ($user) => [
            'id' => $user->id,
            'name' => $user->name . ' ' . $user->surname
        ]);

        if (!$items) return redirect()->route('admin.dashboard');

        return inertia('Admin/InventoryServices', [
            'services' => $services,
            'items' => $items,
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
            'notification' => ['boolean'],
            'inventory_item_id' => ['required', 'integer', 'exists:inventory_items,id'],
            'assigned_user' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        if (Auth::user()->privilege_id != Privilege::IS_ADMIN && $request->assigned_user != null && $request->assigned_user != Auth::user()->id)
            return redirect()->back();

        InventoryService::create([
            'name' => $request->name,
            'description' => $request->description,
            'date_due' => $request->date_due,
            'inventory_item_id' => $request->inventory_item_id,
            'assigned_user' => $request->assigned_user,
            'notification' => $request->notification,
            'created_by' => Auth::user()->id
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
    public function update(Request $request, InventoryService $inventory_service)
    {
        $this->authorize('update', $inventory_service, InventoryService::class);

        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:64'], Rule::unique('inventory_services')->ignore(InventoryService::find($inventory_service->id)),
            'description' => ['nullable', 'min:3', 'max:255'],
            'date_due' => ['nullable', 'date', 'after:today'],
            'notification' => ['boolean'],
            'inventory_item_id' => ['required', 'integer', 'exists:inventory_items,id'],
            'assigned_user' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        if (Auth::user()->privilege_id != Privilege::IS_ADMIN && $request->assigned_user != null && $request->assigned_user != Auth::user()->id)
            return redirect()->back();

        $inventory_service->update([
            'name' => $request->name,
            'description' => $request->description,
            'date_due' => $request->date_due,
            'notification' => $request->notification,
            'inventory_item_id' => $request->inventory_item_id,
            'assigned_user' => $request->assigned_user,
        ]);

        return redirect()->back()->with('message', 'Pomyślnie zaktualizowano serwis');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InventoryService  $InventoryService
     * @return \Illuminate\Http\Response
     */
    public function destroy(InventoryService $inventory_service)
    {
        $this->authorize('delete', $inventory_service, InventoryService::class);

        $inventory_service->delete();

        return redirect()->back()->with('message', 'Pomyślnie usunięto serwis');
    }

    /**
     * Mark the specified service as finished
     *
     * @param  \App\Models\InventoryService  $InventoryService
     * @return \Illuminate\Http\Response
     */
    public function finish(InventoryService $inventory_service)
    {
        $this->authorize('finish', $inventory_service, InventoryService::class);

        $inventory_service->update([
            'is_finished' => true,
            'performed_by' => Auth::user()->id,
            'date_performed' => Carbon::now(),
        ]);

        return redirect()->back()->with('message', 'Pomyślnie zakończono serwis');;
    }

    /**
     * Assign authenticated user to the specified service
     *
     * @param  \App\Models\InventoryService  $InventoryService
     * @return \Illuminate\Http\Response
     */
    public function assign_auth(InventoryService $inventory_service)
    {
        $this->authorize('assign', $inventory_service, InventoryService::class);

        $inventory_service->update([
            'assigned_user' => Auth::user()->id,
        ]);

        return redirect()->back()->with('message', 'Przypisano Cię do serwisu');;
    }
}
