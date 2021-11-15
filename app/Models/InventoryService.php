<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryService extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'date_due', 'notification', 'is_finished', 'inventory_item_id'];

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }
}
