<?php

namespace App\Http\Controllers\FrontSite;

use App\Models\StoreCategory;
use App\Models\StoreItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class AboutController extends Controller
{
    public function index() {
        return inertia('About', [
            'users' => User::where('role', '!=', 'null')->orderBy('name')->limit(6)->get()->map(fn ($user) => [
                'name' => $user->name,
                'surname' => $user->surname,
                'nickname' => $user->nickname,
                'role' => $user->role,
                'photo_path' => $user->profile_photo_path
            ])
        ]);
    }
}