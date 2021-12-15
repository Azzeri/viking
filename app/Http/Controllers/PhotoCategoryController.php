<?php

namespace App\Http\Controllers;

use App\Models\PhotoCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PhotoCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', PhotoCategory::class);

        request()->validate([
            'direction' => ['in:asc,desc'],
            'field' => ['in:id,name'],
        ]);

        $query = PhotoCategory::query();

        if (request('search')) {
            $query->where('name', 'ILIKE', '%' . request('search') . '%');
        }

        if (request()->has(['field', 'direction'])) {
            $query->orderBy(request('field'), request('direction'));
        } else
            $query->orderBy('id');

        $categories = $query->where('photo_category_id', null)->paginate()->withQueryString()
            ->through(fn ($category) => [
                'id' => $category->id,
                'name' => $category->name,
                'photo_path' => $category->photo_path,
                'photo_category_id' => $category->photo_category_id,
                'category' => $category->parentCategory
                    ? array(
                        'id' => $category->parentCategory->id,
                        'name' => $category->parentCategory->name,
                    ) : [],
                'subcategories' => $category->subcategories ? $category->subcategories->sortBy('name')->map(fn ($subcategory) => [
                    'id' => $subcategory->id,
                    'name' => $subcategory->name,
                ]) : null
            ]);
            
        return inertia('Admin/PhotoCategories', [
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
        $this->authorize('create', PhotoCategory::class);

        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:64', 'unique:photo_categories'],
            'photo_category_id' => ['nullable', 'integer', 'exists:photo_categories,id'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:2048']
        ]);

        $image_path = $request->hasFile('image') ? '/storage/'.$request->file('image')->store('image', 'public') : null;

        PhotoCategory::create([
           'name' => $request->name,
           'photo_category_id' => $request->photo_category_id,
           'photo_path' => $image_path ? $image_path : '/images/default.png'
        ]);

        return redirect()->back()->with('message', 'Pomyślnie dodano kategorię');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PhotoCategory  $photoCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PhotoCategory $photoCategory)
    {
        $this->authorize('update', $photoCategory, PhotoCategory::class);

        $request->validate([
            'name' => [
                'required', 'string', 'min:3', 'max:64',
                Rule::unique('photo_categories')->ignore(PhotoCategory::find($photoCategory->id))
            ],
            'parentCategoryId' => ['nullable', 'integer', Rule::in($photoCategory->photo_category_id)],
            // 'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:2048']
        ]);

        $photoCategory->update([
            'name' => $request->name
        ]);

        return redirect()->back()->with('message', 'Pomyślnie zaktualizowano kategorię');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PhotoCategory  $photoCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(PhotoCategory $photoCategory)
    {
        $this->authorize('delete', $photoCategory, PhotoCategory::class);

        $photoCategory->delete();
        Storage::delete('public/'.ltrim($photoCategory->photo_path, '/storage'));

        return redirect()->back()->with('message', 'Pomyślnie usunięto kategorię');
    }
}
