<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', Post::class);

        request()->validate([
            'direction' => ['in:asc,desc'],
            'field' => ['in:id,title,user_id']
        ]);

        $query = Post::query();

        if (request('search')) {
            $query->where('name', 'ILIKE', '%' . request('search') . '%')
                ->orWhere('description', 'ILIKE', '%' . request('search') . '%')
                ->orWhere('resource_link', 'ILIKE', '%' . request('search') . '%');
        }

        if (request()->has(['field', 'direction'])) {
            $query->orderBy(request('field'), request('direction'));
        } else
            $query->orderBy('id');

        $posts = $query->paginate()->withQueryString()
            ->through(fn ($post) => [
                'id' => $post->id,
                'title' => $post->title,
                'photo_path' => $post->photo_path,
                'body' => $post->body,
                'resource_link' => $post->resource_link,
                'time_created' => Carbon::parse($post->created_at)->format('H:i'),
                'date_created' => Carbon::parse($post->created_at)->format('Y-m-d'),
                'user' => array(
                    'id' => $post->user->id,
                    'name' => $post->user->name,
                    'surname' => $post->user->surname,
                    'nickname' => $post->user->nickname,
                )
            ]);
        return inertia('Admin/Posts', [
            'posts' => $posts,
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
        $this->authorize('create', Post::class);

        $request->validate([
            'title' => ['required', 'min:3', 'max:128', 'string'],
            'body' => ['required', 'min:3', 'max:255'],
            'resource_link' => ['nullable', 'string']
        ]);

        Post::create([
            'title' => request()->title,
            'body' => request()->body,
            'resource_link' => request()->resource_link,
            'user_id' => Auth::user()->id
        ]);

        return redirect()->back()->with('message', 'Pomyślnie dodano post');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }
}
