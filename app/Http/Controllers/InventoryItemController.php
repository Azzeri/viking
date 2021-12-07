<?php

namespace App\Http\Controllers;

use App\Models\InventoryCategory;
use App\Models\InventoryItem;
use Illuminate\Http\Request;
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
            'field' => ['in:id,name,inventory_category_id,quantity']
        ]);

        $query = InventoryItem::query();

        if (request('search')) {
            $query->where('name', 'ILIKE', '%' . request('search') . '%');
        }

        if (request()->has(['field', 'direction'])) {
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
                'category' => array(
                    'id' => $inventoryItem->category->id,
                    'name' => $inventoryItem->category->name,
                ),
            ]);

        $categories = InventoryCategory::orderBy('name')->get()->map(fn ($category) => [
            'id' => $category->id,
            'name' => $category->name,
        ]);

        return inertia('Admin/InventoryItems', [
            'items' => $items,
            'categories' => $categories,
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

        InventoryItem::create(
            $request->validate([
                'name' => ['required', 'string', 'min:3', 'max:64', 'unique:inventory_items'],
                'inventory_category_id' => ['required', 'integer'],
                'quantity' => ['required', 'integer', 'min:0', 'max:9999'],
                'description' => ['nullable', 'min:3', 'max:255']
            ])
        );

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

        $inventory_item->update($request->validate([
            'name' => [
                'required', 'string', 'min:3', 'max:64',
                Rule::unique('inventory_items')->ignore(InventoryItem::find($inventory_item->id))
            ],
            'inventory_category_id' => ['required', 'integer'],
            'description' => ['nullable', 'min:3', 'max:255'],
            'quantity' => ['required', 'integer', 'min:0', 'max:9999']
        ]));


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
        // $photoName = ltrim($item->photo_path, '/images/');
        // if ($photoName != 'default.png')
        //     unlink(public_path('images') . '/' . $photoName);

        $inventory_item->delete();

        return redirect()->back()->with('message', 'Pomyślnie usunięto przedmiot');
    }

    public function storePhoto(Request $request, InventoryItem $item)
    {
        $this->authorize('update', $item, InventoryItem::class);

        $request->validate([
            'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg'
        ]);

        $photoName = ltrim($item->photo_path, '/images/');
        if ($photoName != 'default.png')
            unlink(public_path('images') . '/' . $photoName);

        $imageName = time() . '.' . $request->avatar->extension();
        $request->avatar->move(public_path('images'), $imageName);

        $item->photo_path = '/images/' . $imageName;
        $item->save();

        return redirect()->back()->with('message', 'Pomyślnie zaktualizowano zdjęcie');
    }

    public function deletePhoto(InventoryItem $item)
    {
        $this->authorize('update', $item, InventoryItem::class);

        $photoName = ltrim($item->photo_path, '/images/');

        if ($photoName != 'default.png') {
            unlink(public_path('images') . '/' . $photoName);
            $item->photo_path = '/images/default.png';
            $item->save();
        }

        return redirect()->back()->with('message', 'Pomyślnie usunięto zdjęcie');
    }
}
