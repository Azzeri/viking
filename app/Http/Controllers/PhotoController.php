<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\PhotoCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', Photo::class);
        //walidacja filtra
        $categories = PhotoCategory::orderBy('name')->get()->map(fn ($category) => [
            'id' => $category->id,
            'name' => $category->name,
            'photos' => $category->photos ? $category->photos : null,
            'category' => $category->parentCategory ?? null,
            'subcategories' => $category->subcategories->map(fn ($subcategory) => [
                'id' => $subcategory->id,
                'name' => $subcategory->name
            ]) ?? []
        ]);

        $subcategories = PhotoCategory::where('photo_category_id', '!=', null)->orderBy('name')->get()->map(fn ($category) => [
            'id' => $category->id,
            'name' => $category->name,
        ]);

        $query = Photo::query();

        if (request('filter'))
            $query->where('photo_category_id', request('filter'));


        $photos = $query->paginate(12)->withQueryString()
            ->through(fn ($category) => [
                'id' => $category->id,
                'path' => $category->path,
                'photo_category_id' => $category->photo_category_id,
                'category' => $category->parentCategory
                    ? array(
                        'id' => $category->parentCategory->id,
                        'name' => $category->parentCategory->name,
                    ) : [],
            ]);

        return inertia('Admin/Photos', [
            'photos' => $photos,
            'categories' => $categories,
            'subcategories' => $subcategories,
            'filters' => request()->all(['filter']),
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
        $this->authorize('create', Photo::class);

        $request->validate([
            'photo_category_id' => ['required', 'integer', Rule::exists('photo_categories', 'id')->where(function ($query) {
                return $query->where('photo_category_id', '!=', null);
            })],
            'images' => ['required'],
            'images.*' => ['image', 'mimes:jpeg,jpg,png,gif,svg', 'max:2048']

        ]);

        foreach ($request->file('images') as $img) {
            $image_path = '/storage/' . $img->store('image', 'public');

            Photo::create([
                'photo_category_id' => $request->photo_category_id,
                'path' => $image_path
            ]);
        }

        return redirect()->back()->with('message', 'Pomyślnie dodano zdjęcie');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Photo $photo)
    {
        $this->authorize('delete', $photo, Photo::class);

        if (Storage::exists('public/' . ltrim($photo->path, '/storage'))) {
            $photo->delete();
            Storage::delete('public/' . ltrim($photo->path, '/storage'));
        }

        return redirect()->back()->with('message', 'Pomyślnie usunięto zdjęcie');
    }
}
