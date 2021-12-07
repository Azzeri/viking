<?php

namespace App\Http\Controllers;

use App\Models\PhotoCategory;
use Illuminate\Http\Request;

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

        $categories = $query->paginate()->withQueryString()
            ->through(fn ($category) => [
                'id' => $category->id,
                'name' => $category->name,
                'photo_path' => $category->photo_path,
                'parent_category' => array(
                    'id' => $category->parentCategory->id,
                    'name' => $category->parentCategory->name,
                ),
                'subcategories' => $category->subcategories ? $category->subcategories->sortBy('id')->map(fn ($subcategory) => [
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PhotoCategory  $photoCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(PhotoCategory $photoCategory)
    {
        //
    }
}
