<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryService extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'date_due', 'date_performed', 'notification', 'is_finished', 'inventory_item_id', 'assigned_user', 'performed_by'];

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user');
        
    }

    public function performedBy()
    {
        return $this->belongsTo(User::class, 'performed_by');
        
    }
}
