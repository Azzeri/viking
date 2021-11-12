<?php

namespace Database\Seeders;

use App\Models\InventoryItem;
use Illuminate\Database\Seeder;

class InventoryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        InventoryItem::factory()->count(30)->create();
    }
}
