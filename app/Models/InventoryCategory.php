<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;

class InventoryCategory extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $fillable = ['name', 'photo_path', 'inventory_category_id'];
    protected $cascadeDeletes = ['subcategories', 'items'];

    public function subcategories()
    {
        return $this->hasMany(InventoryCategory::class)->orderBy('name');
    }

    public function parentCategory()
    {
        return $this->belongsTo(InventoryCategory::class, 'inventory_category_id');
    }

    public function items()
    {
        return $this->hasMany(InventoryItem::class)->orderBy('name');
    }
}
