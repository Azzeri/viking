<?php

namespace App\Actions\Post;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Post;

class StorePostAction
{
  public const DEFAULT_IMAGE_PATH = '/images/default.png';

  public function execute(Request $request)
  {
    $image_path = $this->generateImagePath($request);

    Post::create([
      'title' => $request->title,
      'body' => $request->body,
      'resource_link' => $request->resource_link,
      'user_id' => Auth::user()->id,
      'photo_path' => $image_path
        ? $image_path
        : self::DEFAULT_IMAGE_PATH
    ]);
  }

  private function generateImagePath(Request $request)
  {
    return $request->hasFile('image')
      ? '/storage/' . $request->file('image')->store('image', 'public')
      : null;
  }
}
