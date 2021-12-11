<?php

namespace Database\Seeders;

use App\Models\InventoryCategory;
use Illuminate\Database\Seeder;

class InventoryCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        InventoryCategory::create(['name'=>'Miecze']);
        InventoryCategory::create(['name'=>'Topory']);
        InventoryCategory::create(['name'=>'Hełmy']);
        InventoryCategory::create(['name'=>'Koszule']);
        InventoryCategory::create(['name'=>'Spodnie']);
        InventoryCategory::create(['name'=>'Obóz']);


        InventoryCategory::create(['name' => 'Miecze jednoręczne', 'inventory_category_id' => 1]);
        InventoryCategory::create(['name' => 'Miecze dwuręczne', 'inventory_category_id' => 1]);

        InventoryCategory::create(['name' => 'Topory jednoręczne', 'inventory_category_id' => 2]);
        InventoryCategory::create(['name' => 'Topory dwuręczne', 'inventory_category_id' => 2]);

        InventoryCategory::create(['name' => 'Nosale', 'inventory_category_id' => 3]);
        InventoryCategory::create(['name' => 'Geirmundy', 'inventory_category_id' => 3]);

        InventoryCategory::create(['name' => 'Koszule płócienne', 'inventory_category_id' => 4]);
        InventoryCategory::create(['name' => 'Koszule wełniane', 'inventory_category_id' => 4]);

        InventoryCategory::create(['name' => 'Spodnie płócienne', 'inventory_category_id' => 5]);
        InventoryCategory::create(['name' => 'Spodnie wełniane', 'inventory_category_id' => 5]);

        InventoryCategory::create(['name' => 'Namioty', 'inventory_category_id' => 6]);
        InventoryCategory::create(['name' => 'Miski', 'inventory_category_id' => 6]);
        InventoryCategory::create(['name' => 'Łyżki', 'inventory_category_id' => 6]);
        InventoryCategory::create(['name' => 'Paleniska', 'inventory_category_id' => 6]);


        // InventoryCategory::factory()->count(15)->create();
    }
}
