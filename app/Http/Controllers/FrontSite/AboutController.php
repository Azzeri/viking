<?php

namespace App\Http\Controllers\FrontSite;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use App\Models\Photo;
use App\Models\Post;
use App\Models\StoreItem;

class AboutController extends Controller
{
    public function index()
    {
        return inertia('About', [
            'users' => User::orderBy('name')->get()->map(fn ($user) => [
                'name' => $user->name,
                'surname' => $user->surname,
                'nickname' => $user->nickname,
                'role' => $user->role,
                'profile_photo_path' => $user->profile_photo_path
            ]),

            'events' => Event::orderBy('created_at', 'desc')->limit(3)->get()->map(fn ($event) => [
                'label' => $event->name,
                'photo' => $event->photo_path
            ]),

            'gallery' => Photo::orderBy('created_at', 'desc')->limit(3)->get()->map(fn ($photo) => [
                'photo' => $photo->path
            ]),

            'news' => Post::orderBy('created_at', 'desc')->limit(3)->get()->map(fn ($post) => [
                'label' => $post->title,
                'photo' => $post->photo_path
            ]),

            'store' => StoreItem::orderBy('created_at', 'desc')->limit(3)->get()->map(fn ($item) => [
                'label' => $item->name,
                'photo' => $item->photo_path
            ])
        ]);
    }
}
