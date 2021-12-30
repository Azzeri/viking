<?php

namespace App\Http\Controllers;

use App\Models\StoreCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class StoreCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', StoreCategory::class);

        request()->validate([
            'direction' => ['in:asc,desc'],
            'field' => ['in:id,name']
        ]);

        $query = StoreCategory::query();

        if (request('search')) {
            $query->where('name', 'ILIKE', '%' . request('search') . '%');
        }

        if (request()->has(['field', 'direction'])) {
            $query->orderBy(request('field'), request('direction'));
        } else
            $query->orderBy('id');

        $categories = $query->where('store_category_id', null)->paginate()->withQueryString()
            ->through(fn ($store_category) => [
                'id' => $store_category->id,
                'name' => $store_category->name,
                'photo_path' => $store_category->photo_path,
                'store_category_id' => $store_category->store_category_id,
                'category' => $store_category->parentCategory
                    ? array(
                        'id' => $store_category->parentCategory->id,
                        'name' => $store_category->parentCategory->name,
                    ) : [],
                'subcategories' => $store_category->subcategories ? $store_category->subcategories->map(fn ($subcategory) => [
                    'id' => $subcategory->id,
                    'name' => $subcategory->name,
                ]) : null
            ]);

        return inertia('Admin/Categories', [
            'categories' => $categories,
            'model' => 'store',
            'title' => 'Kategorie w sklepie',
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
        $this->authorize('create', StoreCategory::class);

        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:64', 'unique:store_categories'],
            'parent_category_id' => ['nullable', 'integer', 'exists:store_categories,id'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:2048']
        ]);

        $image_path = $request->hasFile('image') ? '/storage/' . $request->file('image')->store('image', 'public') : null;

        StoreCategory::create([
            'name' => $request->name,
            'store_category_id' => $request->parent_category_id,
            'photo_path' => $image_path ? $image_path : '/images/default.png'
        ]);

        return redirect()->back()->with('message', 'Pomyślnie dodano kategorię');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StoreCategory  $store_category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StoreCategory $category)
    {
        $this->authorize('update', $category, StoreCategory::class);

        $request->validate([
            'name' => [
                'required', 'string', 'min:3', 'max:64',
                Rule::unique('store_categories')->ignore(StoreCategory::find($category->id))
            ],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:2048'],
            'deleteImage' => ['boolean', 'required']
        ]);

        $image_path = null;
        if ($request->hasFile('image')) {
            $image_path =  '/storage/' . $request->file('image')->store('image', 'public');
            if ($category->photo_path != '/images/default.png')
                Storage::delete('public/' . ltrim($category->photo_path, '/storage'));
        }

        if ($request->deleteImage) {
            Storage::delete('public/' . ltrim($category->photo_path, '/storage'));
            $image_path = '/images/default.png';
        }

        $category->update([
            'name' => $request->name,
            'photo_path' => $image_path ? $image_path : $category->photo_path
        ]);

        return redirect()->back()->with('message', 'Pomyślnie zaktualizowano kategorię');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StoreCategory  $store_category
     * @return \Illuminate\Http\Response
     */
    public function destroy(StoreCategory $category)
    {
        $this->authorize('delete', $category, StoreCategory::class);

        $category->delete();
        Storage::delete('public/' . ltrim($category->photo_path, '/storage'));

        return redirect()->back()->with('message', 'Pomyślnie usunięto kategorię');
    }
}
