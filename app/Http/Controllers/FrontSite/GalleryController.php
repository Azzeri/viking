<?php

namespace App\Http\Controllers\FrontSite;

use App\Models\StoreCategory;
use App\Models\StoreItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Models\PhotoCategory;

class GalleryController extends Controller
{
    public function index()
    {
        request()->validate([
            'filter' => ['exists:photo_categories,id']
        ]);

        $categories = PhotoCategory::where('photo_category_id', null)->orderBy('name')->get()->map(fn ($category) => [
            'id' => $category->id,
            'name' => $category->name,
            'subcategories' => $category->subcategories ? $category->subcategories->map(fn ($subcategory) => [
                'id' => $subcategory->id,
                'name' => $subcategory->name
            ]) : null
        ]);

        $query = Photo::query();

        if (request('filter')) $query->where('photo_category_id', request('filter'));

        $photos = $query->get()->map(fn ($photo) => [
            'id' => $photo->id,
            'path' => $photo->path
        ]);

        return inertia('Gallery', [
            'photos' => $photos,
            'categories' => $categories,
            'filters' => request()->all(['filter']),
        ]);
    }
}
