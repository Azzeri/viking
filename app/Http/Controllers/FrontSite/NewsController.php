<?php

namespace App\Http\Controllers\FrontSite;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Carbon\Carbon;

class NewsController extends Controller
{
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->paginate()
            ->through(fn ($post) => [
                'id' => $post->id,
                'title' => $post->title,
                'photo_path' => $post->photo_path,
                'body' => strlen($post->body) > 255 ? substr($post->body, 0, 255) . '...' : $post->body,
                'resource_link' => $post->resource_link,
                'time_created' => Carbon::parse($post->created_at)->format('H:i'),
                'date_created' => Carbon::parse($post->created_at)->format('Y-m-d'),
                'user' => array(
                    'name' => $post->user->name,
                    'surname' => $post->user->surname,
                    'nickname' => $post->user->nickname,
                )
            ]);

        return inertia('Posts', [
            'posts' => $posts
        ]);
    }

    public function show(Post $post)
    {
        return inertia('PostDetails', [
            'post' => array(
                'id' => $post->id,
                'title' => $post->title,
                'photo_path' => $post->photo_path,
                'body' => $post->body,
                'resource_link' => $post->resource_link,
                'time_created' => Carbon::parse($post->created_at)->format('H:i'),
                'date_created' => Carbon::parse($post->created_at)->format('Y-m-d'),
                'user' => array(
                    'name' => $post->user->name,
                    'surname' => $post->user->surname,
                    'nickname' => $post->user->nickname,
                )
            )
        ]);
    }
}
