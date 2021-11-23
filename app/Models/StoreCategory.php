<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;

class StoreCategory extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $fillable = ['name', 'photo_path', 'store_category_id'];
    protected $cascadeDeletes = ['subcategories', 'items'];

    public function subcategories()
    {
        return $this->hasMany(StoreCategory::class);
    }

    public function parentCategory()
    {
        return $this->belongsTo(StoreCategory::class, 'store_category_id');
    }

    public function items()
    {
        return $this->hasMany(StoreItem::class);
    }
}
