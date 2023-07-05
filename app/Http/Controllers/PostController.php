<?php

namespace App\Http\Controllers;

use App\Actions\Post\StorePostAction;
use App\Actions\Post\UpdatePostAction;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Traits\PostTrait;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    use PostTrait;
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
            'field' => ['in:id,title,user_id,created_at']
        ]);

        $query = Post::query();

        if (request('search')) {
            $query->where('title', 'ILIKE', '%' . request('search') . '%')
                ->orWhere('body', 'ILIKE', '%' . request('search') . '%')
                ->orWhere('resource_link', 'ILIKE', '%' . request('search') . '%');
        }

        if (request()->has(['field', 'direction'])) {
            if (request('field') == 'user_id')
                $query->orderBy(User::select('surname')->whereColumn('users.id', 'posts.user_id'), request('direction'));
            else
                $query->orderBy(request('field'), request('direction'));
        } else
            $query->orderBy('id');

        $posts = $query->paginate()->withQueryString()
            ->through(fn ($post) => [
                'id' => $post->id,
                'title' => strlen($post->title) > 43 ? substr($post->title, 0, 43) . '...' : $post->title,
                'photo_path' => $post->photo_path,
                'body' => $post->body,
                'time_created' => Carbon::parse($post->created_at)->format('H:i'),
                'date_created' => Carbon::parse($post->created_at)->format('Y-m-d'),
                'date_time_created_formatted' => Carbon::parse($post->created_at)->toDayDateTimeString(),
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
     * 
     * @author Mariusz Waloszczyk <artess2698@gmail.com>
     */
    public function store(
        StorePostRequest $request,
        StorePostAction $storePostAction
    ) {
        $storePostAction->execute($request);
        
        return redirect()
            ->route('admin.posts.index')
            ->with('message', 'Pomyślnie dodano post');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $this->authorize('view', $post, Post::class);

        $postMapped = array(
            'id' => $post->id,
            'title' => $post->title,
            'photo_path' => $post->photo_path,
            'body' => $post->body,
            'resource_link' => $post->resource_link,
            'resource_type' => $post->resource_link ? explode("/", $post->resource_link)[1] : null,
            'resource_id' => $post->resource_link ? explode("/", $post->resource_link)[2] : null,
            'time_created' => Carbon::parse($post->created_at)->format('H:i'),
            'date_created' => Carbon::parse($post->created_at)->format('Y-m-d'),
            'date_time_created_formatted' => Carbon::parse($post->created_at)->toDayDateTimeString(),
            'user' => array(
                'id' => $post->user->id,
                'name' => $post->user->name,
                'surname' => $post->user->surname,
                'nickname' => $post->user->nickname,
            )
        );

        return inertia('Admin/PostDetails', [
            'post' => $postMapped,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(
        UpdatePostRequest $request,
        UpdatePostAction $updatePostAction,
        Post $post
    ) {
        $updatePostAction->execute($request, $post);

        return redirect()
            ->back()
            ->with('message', 'Pomyślnie zaktualizowano post');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post, Post::class);

        $post->delete();
        Storage::delete('public/' . ltrim($post->path, '/storage'));

        return redirect()->route('admin.posts.index')->with('message', 'Pomyślnie usunięto post');
    }
}
