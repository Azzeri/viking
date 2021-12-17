<?php

namespace App\Http\Controllers\FrontSite;

use App\Models\StoreCategory;
use App\Models\StoreItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GalleryController extends Controller
{
    public function index()
    {
        return inertia('Gallery');
    }
}
