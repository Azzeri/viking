<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;

class InventoryItem extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $fillable = ['name', 'photo_path', 'description', 'quantity', 'inventory_category_id'];
    protected $cascadeDeletes = ['services'];

    public function category()
    {
        return $this->belongsTo(InventoryCategory::class, 'inventory_category_id');
    }
    
    public function services()
    {
        return $this->hasMany(InventoryService::class);
    }
}
