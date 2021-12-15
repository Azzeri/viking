<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['description', 'client_name', 'client_phone', 'client_email', 'is_accepted', 'is_finished', 'store_item_id', 'note', 'date_finished'];

    public function item()
    {
        return $this->belongsTo(StoreItem::class, 'store_item_id');
    }
}
