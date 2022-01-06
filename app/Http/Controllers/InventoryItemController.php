<?php

namespace App\Http\Controllers;

use App\Models\InventoryCategory;
use App\Models\InventoryItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class InventoryItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', InventoryItem::class);

        request()->validate([
            'direction' => ['in:asc,desc'],
            'field' => ['in:id,name,inventory_category_id,quantity,is_functional,owner']
        ]);

        $query = InventoryItem::query();

        if (request('search')) {
            $query->where('name', 'ILIKE', '%' . request('search') . '%')
                ->orWhere('description', 'ILIKE', '%' . request('search') . '%');
        }

        if (request()->has(['field', 'direction'])) {
            if (request('field') == 'inventory_category_id')
                $query->orderBy(InventoryCategory::select('name')->whereColumn('inventory_categories.id', 'inventory_items.inventory_category_id'), request('direction'));
            else if (request('field') == 'owner')
                $query->orderBy(User::select('name')->whereColumn('users.id', 'inventory_items.owner'), request('direction'));
            else
                $query->orderBy(request('field'), request('direction'));
        } else
            $query->orderBy('id');

        $items = $query->paginate()->withQueryString()
            ->through(fn ($inventoryItem) => [
                'id' => $inventoryItem->id,
                'name' => $inventoryItem->name,
                'photo_path' => $inventoryItem->photo_path,
                'description' => $inventoryItem->description,
                'quantity' => $inventoryItem->quantity,
                'inventory_category_id' => $inventoryItem->inventory_category_id,
                'is_functional' => $inventoryItem->is_functional,
                'owner' => $inventoryItem->ownerUser,
                'owner' => $inventoryItem->owner ? array(
                    'id' => $inventoryItem->ownerUser->id,
                    'name' => $inventoryItem->ownerUser->getFullName(),
                ) : null,
                'category' => array(
                    'id' => $inventoryItem->category->id,
                    'name' => $inventoryItem->category->name,
                ),
            ]);

        $users = User::get()->map(fn ($user) => [
            'id' => $user->id,
            'name' => $user->getFullName(),
        ]);

        $categories = InventoryCategory::where('inventory_category_id', '!=', null)->orderBy('name')->get()->map(fn ($category) => [
            'id' => $category->id,
            'name' => $category->name,
        ]);

        return inertia('Admin/InventoryItems', [
            'items' => $items,
            'categories' => $categories,
            'users' => $users,
            'filters' => request()->all(['search', 'field', 'direction']),
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
        $this->authorize('create', InventoryItem::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:64', 'unique:inventory_items'],
            'inventory_category_id' => ['required', 'integer', Rule::exists('inventory_categories', 'id')->where(function ($query) {
                return $query->where('inventory_category_id', '!=', null);
            })],
            'quantity' => ['required', 'integer', 'min:0', 'max:9999'],
            'description' => ['nullable', 'min:3', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:2048'],
            'owner' => ['nullable', 'integer', 'exists:users,id']
        ]);

        $image_path = $request->hasFile('image') ? '/storage/' . $request->file('image')->store('image', 'public') : null;

        InventoryItem::create($validated + [
            'photo_path' => $image_path ? $image_path : '/images/default.png'
        ]);

        return redirect()->back()->with('message', 'Pomyślnie dodano przedmiot');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InventoryItem  $InventoryItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InventoryItem $inventory_item)
    {
        $this->authorize('update', $inventory_item, InventoryItem::class);

        $validated = $request->validate([
            'name' => [
                'required', 'string', 'min:3', 'max:64',
                Rule::unique('inventory_items')->ignore(InventoryItem::find($inventory_item->id))
            ],
            'inventory_category_id' => ['required', 'integer', Rule::exists('inventory_categories', 'id')->where(function ($query) {
                return $query->where('inventory_category_id', '!=', null);
            })],
            'description' => ['nullable', 'min:3', 'max:255'],
            'quantity' => ['required', 'integer', 'min:0', 'max:9999'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:2048'],
            'deleteImage' => ['boolean', 'required'],
            'is_functional' => ['boolean', 'required'],
            'owner' => ['nullable', 'integer', 'exists:users,id']
        ]);

        $image_path = null;
        if ($request->hasFile('image')) {
            $image_path =  '/storage/' . $request->file('image')->store('image', 'public');
            if ($inventory_item->photo_path != '/images/default.png')
                Storage::delete('public/' . ltrim($inventory_item->photo_path, '/storage'));
        }

        if ($request->deleteImage) {
            Storage::delete('public/' . ltrim($inventory_item->photo_path, '/storage'));
            $image_path = '/images/default.png';
        }

        $inventory_item->update($validated + [
            'photo_path' => $image_path ? $image_path : $inventory_item->photo_path,
        ]);

        return redirect()->back()->with('message', 'Pomyślnie zaktualizowano przedmiot');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InventoryItem  $InventoryItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(InventoryItem $inventory_item)
    {
        $this->authorize('delete', $inventory_item, InventoryItem::class);

        $inventory_item->delete();
        Storage::delete('public/' . ltrim($inventory_item->photo_path, '/storage'));

        return redirect()->back()->with('message', 'Pomyślnie usunięto przedmiot');
    }
}
