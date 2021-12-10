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

        $categories = $query->paginate()->withQueryString()
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

        InventoryCategory::create([
            $request->validate([
                'name' => ['required', 'string', 'min:3', 'max:32', 'unique:inventory_categories'],
                'inventory_category_id' => ['nullable', 'integer', 'exists:inventory_categories,id']
            ])
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

        $currentParent = $inventory_category->inventory_category_id;

        $inventory_category->update([
            $request->validate([
                'name' => [
                    'required', 'string', 'min:3', 'max:32',
                    Rule::unique('inventory_categories')->ignore(InventoryCategory::find($inventory_category->id))
                ],
                'parentCategoryId' => ['nullable', 'integer', Rule::in($currentParent)]
            ])
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

        // $photoName = ltrim($cat->photo_path, '/images/');
        // if ($photoName != 'default.png')
        //     unlink(public_path('images') . '/' . $photoName);

        $inventory_category->delete();

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
