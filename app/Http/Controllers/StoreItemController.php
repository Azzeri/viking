<?php

namespace App\Http\Controllers;

use App\Models\StoreCategory;
use App\Models\StoreItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StoreItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', StoreItem::class);

        request()->validate([
            'direction' => ['in:asc,desc'],
            'field' => ['in:name,store_category_id,quantity,price']
        ]);

        $query = StoreItem::query();

        if (request('search')) {
            $query->where('name', 'ILIKE', '%' . request('search') . '%')
            ->orWhere('description', 'ILIKE', '%' . request('search') . '%')
            ->orWhere('price', 'ILIKE', '%' . request('search') . '%');
        }

        if (request()->has(['field', 'direction'])) {
            $query->orderBy(request('field'), request('direction'));
        } else
            $query->orderBy('name');

        $items = $query->paginate()->withQueryString()
            ->through(fn ($storeItem) => [
                'id' => $storeItem->id,
                'name' => $storeItem->name,
                'photo_path' => $storeItem->photo_path,
                'description' => $storeItem->description,
                'quantity' => $storeItem->quantity,
                'price' => $storeItem->price,
                'store_category_id' => $storeItem->store_category_id,
                'category_name' => $storeItem->category->name,
                'category_id' => $storeItem->category->id,
            ]);

        $categories = StoreCategory::orderBy('name')->get()->map(fn ($category) => [
            'id' => $category->id,
            'name' => $category->name,
        ]);

        return inertia('Admin/StoreItems', [
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
        $this->authorize('create', StoreItem::class);

        StoreItem::create(
            $request->validate([
                'name' => ['required', 'string', 'min:3', 'max:64', 'unique:inventory_items'],
                'store_category_id' => ['required', 'integer'],
                'quantity' => ['required', 'integer', 'min:0', 'max:9999'],
                'price' => ['required', 'numeric', 'min:0', 'max:999999'],
                'description' => ['nullable', 'min:3', 'max:255']
            ])
        );

        return redirect()->back()->with('message', 'Pomyślnie dodano przedmiot');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StoreItem  $storeItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StoreItem $storeItem)
    {
        $this->authorize('update', $storeItem, StoreItem::class);

        $storeItem = StoreItem::find($request->id);

        $storeItem->update($request->validate([
            'name' => [
                'required', 'string', 'min:3', 'max:64',
                Rule::unique('store_items')->ignore(StoreItem::find($storeItem->id))
            ],
            'store_category_id' => ['required', 'integer'],
            'description' => ['nullable', 'min:3', 'max:255'],
            'quantity' => ['required', 'integer', 'min:0', 'max:9999'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999'],
        ]));


        return redirect()->back()->with('message', 'Pomyślnie zaktualizowano przedmiot');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StoreItem  $storeItem
     * @return \Illuminate\Http\Response
     */
    public function destroy($storeItem)
    {
        $item = StoreItem::find($storeItem);
        $this->authorize('delete', $item, StoreItem::class);

        $photoName = ltrim($item->photo_path, '/images/');
        if ($photoName != 'default.png')
            unlink(public_path('images') . '/' . $photoName);

        $item->delete();

        return redirect()->back()->with('message', 'Pomyślnie usunięto przedmiot');
    }

    public function storePhoto(Request $request, $id)
    {
        $item = StoreItem::find($id);

        $this->authorize('update', $item, StoreItem::class);

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

    public function deletePhoto($id)
    {
        $item = StoreItem::find($id);

        $this->authorize('update', $item, StoreItem::class);

        $photoName = ltrim($item->photo_path, '/images/');

        if ($photoName != 'default.png') {
            unlink(public_path('images') . '/' . $photoName);
            $item->photo_path = '/images/default.png';
            $item->save();
        }

        return redirect()->back()->with('message', 'Pomyślnie usunięto zdjęcie');
    }
}
