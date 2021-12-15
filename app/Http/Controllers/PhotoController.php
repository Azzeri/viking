<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
// FILTRY
        $categories = Photo::paginate()->withQueryString()
            ->through(fn ($category) => [
                'id' => $category->id,
                'path' => $category->photo_path,
                'photo_category_id' => $category->photo_category_id,
                'category' => $category->parentCategory
                    ? array(
                        'id' => $category->parentCategory->id,
                        'name' => $category->parentCategory->name,
                    ) : [],
            ]);
            
        return inertia('Admin/Photos', [
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
        $this->authorize('create', Photo::class);

        $request->validate([
            'photo_category_id' => ['required', 'integer', 'exists:photo_categories,id'],
            'path' => ['required', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:2048']
        ]);

        $image_path = $request->hasFile('image') ? '/storage/'.$request->file('image')->store('image', 'public') : null;

        Photo::create([
           'photo_category_id' => $request->photo_category_id,
           'path' => $image_path ? $image_path : '/images/default.png'
        ]);

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

        $photo->delete();
        Storage::delete('public/'.ltrim($photo->photo_path, '/storage'));

        return redirect()->back()->with('message', 'Pomyślnie usunięto zdjęcie');
    }
}
