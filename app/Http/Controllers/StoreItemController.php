<?php

namespace App\Http\Controllers;

use App\Models\StoreCategory;
use App\Models\StoreItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'field' => ['in:id,name,store_category_id,quantity,price']
        ]);

        $query = StoreItem::query();

        if (request('search')) {
            $query->where('name', 'ILIKE', '%' . request('search') . '%')
                ->orWhere('description', 'ILIKE', '%' . request('search') . '%')
                ->orWhere('price', 'ILIKE', '%' . request('search') . '%');
        }

        if (request()->has(['field', 'direction'])) {
            if (request('field') == 'store_category_id')
                $query->orderBy(StoreCategory::select('name')->whereColumn('store_categories.id', 'store_items.store_category_id'), request('direction'));
            else
                $query->orderBy(request('field'), request('direction'));
        } else
            $query->orderBy('id');

        $items = $query->paginate()->withQueryString()
            ->through(fn ($store_item) => [
                'id' => $store_item->id,
                'name' => $store_item->name,
                'photo_path' => $store_item->photo_path,
                'description' => $store_item->description,
                'quantity' => $store_item->quantity,
                'price' => $store_item->price,
                'store_category_id' => $store_item->store_category_id,
                'category' => array(
                    'id' => $store_item->category->id,
                    'name' => $store_item->category->name,
                ),
                'craftspeople' => User::whereIn('id', json_decode($store_item->craftspeople))->withTrashed()->orderBy('name')->get()->map(fn ($user) => [
                    'id' => $user->id,
                    'name' => $user->getFullName(),
                ]),
                'craftspeople_array' => $store_item->craftspeople,
            ]);

        $categories = StoreCategory::where('store_category_id', '!=', null)->orderBy('name')->get()->map(fn ($category) => [
            'id' => $category->id,
            'name' => $category->name,
        ]);

        $users = User::get()->map(fn ($user) => [
            'id' => $user->id,
            'name' => $user->getFullName(),
        ]);

        return inertia('Admin/StoreItems', [
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
        $this->authorize('create', StoreItem::class);

        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:64', 'unique:store_items'],
            'store_category_id' => ['required', 'integer', Rule::exists('store_categories', 'id')->where(function ($query) {
                return $query->where('store_category_id', '!=', null);
            })],
            'quantity' => ['required', 'integer', 'min:0', 'max:9999'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999'],
            'description' => ['nullable', 'min:3', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:2048'],
            'craftspeople' => ['array', 'required'],
            'craftspeople.*' => ['exists:users,id']
        ]);

        $image_path = $request->hasFile('image') ? '/storage/' . $request->file('image')->store('image', 'public') : null;

        StoreItem::create([
            'name' => $request->name,
            'store_category_id' => $request->store_category_id,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'description' => $request->description,
            'photo_path' => $image_path ? $image_path : '/images/default.png',
            'craftspeople' => json_encode($request->craftspeople)
        ]);

        return redirect()->back()->with('message', 'Pomyślnie dodano przedmiot');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StoreItem  $store_item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StoreItem $store_item)
    {
        $this->authorize('update', $store_item, StoreItem::class);

        $request->validate([
            'name' => [
                'required', 'string', 'min:3', 'max:64',
                Rule::unique('inventory_items')->ignore(StoreItem::find($store_item->id))
            ],
            'store_category_id' => ['required', 'integer', Rule::exists('store_categories', 'id')->where(function ($query) {
                return $query->where('store_category_id', '!=', null);
            })],
            'description' => ['nullable', 'min:3', 'max:255'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999'],
            'quantity' => ['required', 'integer', 'min:0', 'max:9999'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:2048'],
            'deleteImage' => ['boolean', 'required'],
            'craftspeople' => ['array', 'required'],
            'craftspeople.*' => ['exists:users,id']
        ]);

        $image_path = null;
        if ($request->hasFile('image')) {
            $image_path =  '/storage/' . $request->file('image')->store('image', 'public');
            if ($store_item->photo_path != '/images/default.png')
                Storage::delete('public/' . ltrim($store_item->photo_path, '/storage'));
        }

        if ($request->deleteImage) {
            Storage::delete('public/' . ltrim($store_item->photo_path, '/storage'));
            $image_path = '/images/default.png';
        }

        $store_item->update([
            'name' => $request->name,
            'store_category_id' => $request->store_category_id,
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'photo_path' => $image_path ? $image_path : $store_item->photo_path,
            'craftspeople' => json_encode($request->craftspeople)
        ]);

        return redirect()->back()->with('message', 'Pomyślnie zaktualizowano przedmiot');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StoreItem  $store_item
     * @return \Illuminate\Http\Response
     */
    public function destroy(StoreItem $store_item)
    {
        $this->authorize('delete', $store_item, StoreItem::class);

        $store_item->delete();
        Storage::delete('public/' . ltrim($store_item->photo_path, '/storage'));

        return redirect()->back()->with('message', 'Pomyślnie usunięto przedmiot');
    }
}
