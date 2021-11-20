<?php

namespace App\Http\Controllers;

use App\Models\InventoryCategory;
use Illuminate\Http\Request;
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
            'field' => ['in:name']
        ]);

        $query = InventoryCategory::query();

        if (request('search')) {
            $query->where('name', 'ILIKE', '%' . request('search') . '%');
        }

        if (request()->has(['field', 'direction'])) {
            $query->orderBy(request('field'), request('direction'));
        } else
            $query->orderBy('name');

        $categories = $query->paginate()->withQueryString()
            ->through(fn ($inventoryCategory) => [
                'id' => $inventoryCategory->id,
                'name' => $inventoryCategory->name,
                'photo_path' => $inventoryCategory->photo_path,
                'inventory_category_id' => $inventoryCategory->inventory_category_id,
                'subcategories' => array_column($inventoryCategory->subcategories->toArray(), 'name'),
                'subcategoriesIds' => array_column($inventoryCategory->subcategories->toArray(), 'id'),
                'parentCategoryName' => $inventoryCategory->parentCategory ? $inventoryCategory->parentCategory->name : null,
                'parentCategoryId' => $inventoryCategory->parentCategory ? $inventoryCategory->parentCategory->id : null
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
            'parentCategoryId' => ['nullable', 'integer']
        ]);

        $parentCategory = $request->parentCategoryId == -1 ? null : $request->parentCategoryId;

        InventoryCategory::create([
            'name' => $request->name,
            'inventory_category_id' => $parentCategory
        ]);

        return redirect()->back()->with('message', 'Pomyślnie dodano kategorię');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InventoryCategory  $inventoryCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InventoryCategory $inventoryCategory)
    {
        $this->authorize('update', $inventoryCategory, InventoryCategory::class);

        $inventoryCategory = InventoryCategory::find($request->id);
        $subcats = array_column( $inventoryCategory->subcategories->toArray(), 'id');

        $request->validate([
            'name' => [
                'required', 'string', 'min:3', 'max:64', 'alpha_dash',
                Rule::unique('inventory_categories')->ignore(InventoryCategory::find($inventoryCategory->id))
            ],
            'parentCategoryId' => ['nullable', 'integer', Rule::notIn([$request->id]), Rule::notIn($subcats)]
        ]);

        $parentCategory = $request->parentCategoryId == -1 ? null : $request->parentCategoryId;

        $inventoryCategory->update([
            'name' => $request->name,
            'inventory_category_id' => $parentCategory
        ]);

        return redirect()->back()->with('message', 'Pomyślnie zaktualizowano kategorię');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InventoryCategory  $inventoryCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($inventoryCategory)
    {
        $cat = InventoryCategory::find($inventoryCategory);
        $this->authorize('delete', $cat, InventoryCategory::class);

        $photoName = ltrim($cat->photo_path, '/images/');
        if ($photoName != 'default.png') 
            unlink(public_path('images') . '/' . $photoName);     
        
        $cat->delete();

        return redirect()->back()->with('message', 'Pomyślnie usunięto kategorię');
    }

    public function storePhoto(Request $request, $id) 
    {
        $category = InventoryCategory::find($id);

        $this->authorize('update', $category, InventoryCategory::class);

        $request->validate([
            'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg'
        ]);

        $photoName = ltrim($category->photo_path, '/images/');
        if ($photoName != 'default.png')
            unlink(public_path('images') . '/' . $photoName);

        $imageName = time() . '.' . $request->avatar->extension();
        $request->avatar->move(public_path('images'), $imageName);

        $category->photo_path = '/images/' . $imageName;
        $category->save();

        return redirect()->back()->with('message', 'Pomyślnie zaktualizowano zdjęcie');
    }

    public function deletePhoto($id)
    {
        $category = InventoryCategory::find($id);
        
        $this->authorize('update', $category, InventoryCategory::class);

        $photoName = ltrim($category->photo_path, '/images/');

        if ($photoName != 'default.png') {
            unlink(public_path('images') . '/' . $photoName);
            $category->photo_path = '/images/default.png';
            $category->save();
        }

        return redirect()->back()->with('message', 'Pomyślnie usunięto zdjęcie');

    }
}
