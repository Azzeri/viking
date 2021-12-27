<?php

namespace App\Http\Controllers;

use App\Models\InventoryCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class InventoryCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', InventoryCategory::class);

        request()->validate([
            'direction' => ['in:asc,desc'],
            'field' => ['in:id,name']
        ]);

        $query = InventoryCategory::query();

        if (request('search')) {
            $query->where('name', 'ILIKE', '%' . request('search') . '%');
        }

        if (request()->has(['field', 'direction'])) {
            $query->orderBy(request('field'), request('direction'));
        } else
            $query->orderBy('id');

        $categories = $query->where('inventory_category_id', null)->paginate()->withQueryString()
            ->through(fn ($inventory_category) => [
                'id' => $inventory_category->id,
                'name' => $inventory_category->name,
                'photo_path' => $inventory_category->photo_path,
                'inventory_category_id' => $inventory_category->inventory_category_id,
                'category' => $inventory_category->parentCategory
                    ? array(
                        'id' => $inventory_category->parentCategory->id,
                        'name' => $inventory_category->parentCategory->name,
                    ) : [],
                'subcategories' => $inventory_category->subcategories ? $inventory_category->subcategories->sortBy('name')->map(fn ($subcategory) => [
                    'id' => $subcategory->id,
                    'name' => $subcategory->name,
                ]) : null
            ]);

        return inertia('Admin/InventoryCategories', [
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
        $this->authorize('create', InventoryCategory::class);

        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:64', 'unique:inventory_categories'],
            'inventory_category_id' => ['nullable', 'integer', 'exists:inventory_categories,id'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:2048']
        ]);

        $image_path = $request->hasFile('image') ? '/storage/' . $request->file('image')->store('image', 'public') : null;

        InventoryCategory::create([
            'name' => $request->name,
            'inventory_category_id' => $request->inventory_category_id,
            'photo_path' => $image_path ? $image_path : '/images/default.png'
        ]);

        return redirect()->back()->with('message', 'Pomyślnie dodano kategorię');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InventoryCategory  $inventory_category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InventoryCategory $inventory_category)
    {
        $this->authorize('update', $inventory_category, InventoryCategory::class);

        $request->validate([
            'name' => [
                'required', 'string', 'min:3', 'max:64',
                Rule::unique('inventory_categories')->ignore(InventoryCategory::find($inventory_category->id))
            ],
            'parentCategoryId' => ['nullable', 'integer', Rule::in($inventory_category->inventory_category_id)],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:2048'],
            'deleteImage' => ['boolean', 'required']
        ]);

        $image_path = null;
        if ($request->hasFile('image')) {
            $image_path =  '/storage/' . $request->file('image')->store('image', 'public');
            if ($inventory_category->photo_path != '/images/default.png')
                Storage::delete('public/' . ltrim($inventory_category->photo_path, '/storage'));
        }

        if ($request->deleteImage) {
            Storage::delete('public/' . ltrim($inventory_category->photo_path, '/storage'));
            $image_path = '/images/default.png';
        }

        $inventory_category->update([
            'name' => $request->name,
            'photo_path' => $image_path ? $image_path : $inventory_category->photo_path
        ]);

        return redirect()->back()->with('message', 'Pomyślnie zaktualizowano kategorię');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InventoryCategory  $inventory_category
     * @return \Illuminate\Http\Response
     */
    public function destroy(InventoryCategory $inventory_category)
    {
        $this->authorize('delete', $inventory_category, InventoryCategory::class);

        $inventory_category->delete();
        Storage::delete('public/' . ltrim($inventory_category->photo_path, '/storage'));

        return redirect()->back()->with('message', 'Pomyślnie usunięto kategorię');
    }
}
