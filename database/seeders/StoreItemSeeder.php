<?php

namespace Database\Seeders;

use App\Models\StoreItem;
use Illuminate\Database\Seeder;

class StoreItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // StoreItem::factory()->count(30)->create();
        StoreItem::create([
            'name' => 'Miecz Seax na sprzedaż',
            'quantity' => 1,
            'store_category_id' => 7,
            'description' => 'Taki miecz.'
        ]);
    }
}
