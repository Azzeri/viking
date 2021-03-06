<?php

namespace App\Http\Controllers\FrontSite;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Carbon\Carbon;

class NewsController extends Controller
{
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->paginate(30)
            ->through(fn ($post) => [
                'id' => $post->id,
                'title' => $post->title,
                'photo_path' => $post->photo_path,
                'body' => strlen($post->body) > 255 ? substr($post->body, 0, 255) . '...' : $post->body,
                'resource_link' => $post->resource_link,
                'time_created' => Carbon::parse($post->created_at)->format('H:i'),
                'date_created' => Carbon::parse($post->created_at)->locale('pl')->isoFormat('Do MMM YYYY'),
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
                'resource_type' => $post->resource_link ? explode("/", $post->resource_link)[1] : null,
                'resource_id' => $post->resource_link ? explode("/", $post->resource_link)[2] : null,
                'time_created' => Carbon::parse($post->created_at)->format('H:i'),
                'date_created' => Carbon::parse($post->created_at)->locale('pl')->isoFormat('Do MMM YYYY'),
                'user' => array(
                    'name' => $post->user->name,
                    'surname' => $post->user->surname,
                    'nickname' => $post->user->nickname,
                )
            )
        ]);
    }
}
