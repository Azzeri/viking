<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;

class StoreItem extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $fillable = ['name', 'photo_path', 'description', 'quantity', 'price', 'store_category_id'];
    protected $cascadeDeletes = ['requests'];

    public function category()
    {
        return $this->belongsTo(StoreCategory::class, 'store_category_id');
    }
    
    public function requests()
    {
        return $this->hasMany(StoreRequest::class);
    }

    public function craftspeople()
    {
        return $this->belongsToMany(User::class);
    }
}
