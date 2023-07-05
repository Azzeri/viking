<?php

namespace App\Actions\Post;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Post;

class UpdatePostAction
{
  public const DEFAULT_IMAGE_PATH = '/images/default.png';
  // KOMENTARZE, $post i $request do konstruktora, SCALENIE ZE STORE, wspólne funkcje gdzieś, testy, reszta pdfa, użyc eager loading (with, zabezpieczenie lazy loading), metody do klas, tj. poszanować OOP, filmy o bezpieczeństwie w końcu
  public function execute(Request $request, Post $post)
  {
    $image_path = $this->generateImagePath($request, $post);

    $post->update([
      'title' => $request->title,
      'body' => $request->body,
      'resource_link' => $request->resource_link,
      'photo_path' => $image_path ?? $post->photo_path
    ]);

    if ($image_path) {
      $this->deleteOldPhotoFromStorage($post);
    }
  }

  private function generateImagePath(Request $request)
  {
    return $request->hasFile('image')
      ? '/storage/' . $request->file('image')->store('image', 'public')
      : null;
  }

  public function deleteOldPhotoFromStorage(Post $post)
  {
    if ($post->photo_path != self::DEFAULT_IMAGE_PATH) {
      Storage::delete('public/' . ltrim($post->photo_path, '/storage'));
    }
  }
}
