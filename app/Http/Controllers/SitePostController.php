<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SitePostController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // request()->validate([
        //     'direction' => ['in:asc,desc'],
        //     'field' => ['in:id,title,user_id,created_at']
        // ]);

        $query = Post::query();

        // if (request('search')) {
        //     $query->where('name', 'ILIKE', '%' . request('search') . '%')
        //         ->orWhere('description', 'ILIKE', '%' . request('search') . '%')
        //         ->orWhere('resource_link', 'ILIKE', '%' . request('search') . '%');
        // }

        // if (request()->has(['field', 'direction'])) {
        //     $query->orderBy(request('field'), request('direction'));
        // } else
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
        return inertia('Posts', [
            'posts' => $posts,
            'filters' => request()->all(['search', 'field', 'direction']),
        ]);
    }
}
