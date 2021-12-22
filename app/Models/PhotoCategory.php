<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;

class PhotoCategory extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $fillable = ['name', 'photo_path', 'photo_category_id'];

    protected $cascadeDeletes = ['subcategories', 'photos'];

    public function subcategories()
    {
        return $this->hasMany(PhotoCategory::class)->orderBy('name');
    }

    public function parentCategory()
    {
        return $this->belongsTo(PhotoCategory::class, 'photo_category_id');
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
}
