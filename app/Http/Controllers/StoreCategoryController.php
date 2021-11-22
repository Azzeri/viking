<?php

namespace App\Http\Controllers;

use App\Models\StoreCategory;
use Illuminate\Http\Request;
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
            'field' => ['in:name']
        ]);

        $query = StoreCategory::query();

        if (request('search')) {
            $query->where('name', 'ILIKE', '%' . request('search') . '%');
        }

        if (request()->has(['field', 'direction'])) {
            $query->orderBy(request('field'), request('direction'));
        } else
            $query->orderBy('name');

        $categories = $query->paginate()->withQueryString()
            ->through(fn ($storeCategory) => [
                'id' => $storeCategory->id,
                'name' => $storeCategory->name,
                'photo_path' => $storeCategory->photo_path,
                'store_category_id' => $storeCategory->store_category_id,
                'subcategories' => array_column($storeCategory->subcategories->toArray(), 'name'),
                'subcategoriesIds' => array_column($storeCategory->subcategories->toArray(), 'id'),
                'parentCategoryName' => $storeCategory->parentCategory ? $storeCategory->parentCategory->name : null,
                'parentCategoryId' => $storeCategory->parentCategory ? $storeCategory->parentCategory->id : null
            ]);

        return inertia('Admin/StoreCategories', [
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
        $this->authorize('create', StoreCategory::class);

        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:64', 'unique:store_categories'],
            'parentCategoryId' => ['nullable', 'integer']
        ]);

        $parentCategory = $request->parentCategoryId == -1 ? null : $request->parentCategoryId;

        StoreCategory::create([
            'name' => $request->name,
            'store_category_id' => $parentCategory
        ]);

        return redirect()->back()->with('message', 'Pomyślnie dodano kategorię');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StoreCategory  $storeCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StoreCategory $storeCategory)
    {
        $this->authorize('update', $storeCategory, StoreCategory::class);

        $storeCategory = StoreCategory::find($request->id);
        $subcats = array_column( $storeCategory->subcategories->toArray(), 'id');

        $request->validate([
            'name' => [
                'required', 'string', 'min:3', 'max:64', 'alpha_dash',
                Rule::unique('store_categories')->ignore(StoreCategory::find($storeCategory->id))
            ],
            'parentCategoryId' => ['nullable', 'integer', Rule::notIn([$request->id]), Rule::notIn($subcats)]
        ]);

        $parentCategory = $request->parentCategoryId == -1 ? null : $request->parentCategoryId;

        $storeCategory->update([
            'name' => $request->name,
            'store_category_id' => $parentCategory
        ]);

        return redirect()->back()->with('message', 'Pomyślnie zaktualizowano kategorię');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StoreCategory  $storeCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($storeCategory)
    {
        $cat = StoreCategory::find($storeCategory);
        $this->authorize('delete', $cat, StoreCategory::class);

        $photoName = ltrim($cat->photo_path, '/images/');
        if ($photoName != 'default.png') 
            unlink(public_path('images') . '/' . $photoName);     
        
        $cat->delete();

        return redirect()->back()->with('message', 'Pomyślnie usunięto kategorię');
    }

    public function storePhoto(Request $request, $id) 
    {
        $category = StoreCategory::find($id);

        $this->authorize('update', $category, StoreCategory::class);

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
        $category = StoreCategory::find($id);
        
        $this->authorize('update', $category, StoreCategory::class);

        $photoName = ltrim($category->photo_path, '/images/');

        if ($photoName != 'default.png') {
            unlink(public_path('images') . '/' . $photoName);
            $category->photo_path = '/images/default.png';
            $category->save();
        }

        return redirect()->back()->with('message', 'Pomyślnie usunięto zdjęcie');

    }
}
