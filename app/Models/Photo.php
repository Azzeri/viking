<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = ['path', 'photo_category_id'];

    public function parentCategory()
    {
        return $this->belongsTo(PhotoCategory::class, 'photo_category_id');
    }
}
