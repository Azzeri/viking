<?php

namespace Database\Seeders;

use App\Models\StoreCategory;
use Illuminate\Database\Seeder;

class StoreCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StoreCategory::create(['name'=>'Miecze']);
        StoreCategory::create(['name'=>'Topory']);
        StoreCategory::create(['name'=>'Hełmy']);
        StoreCategory::create(['name'=>'Koszule']);
        StoreCategory::create(['name'=>'Spodnie']);
        StoreCategory::create(['name'=>'Obóz']);


        StoreCategory::create(['name' => 'Miecze jednoręczne', 'store_category_id' => 1]);
        StoreCategory::create(['name' => 'Miecze dwuręczne', 'store_category_id' => 1]);

        StoreCategory::create(['name' => 'Topory jednoręczne', 'store_category_id' => 2]);
        StoreCategory::create(['name' => 'Topory dwuręczne', 'store_category_id' => 2]);

        StoreCategory::create(['name' => 'Nosale', 'store_category_id' => 3]);
        StoreCategory::create(['name' => 'Geirmundy', 'store_category_id' => 3]);

        StoreCategory::create(['name' => 'Koszule płócienne', 'store_category_id' => 4]);
        StoreCategory::create(['name' => 'Koszule wełniane', 'store_category_id' => 4]);

        StoreCategory::create(['name' => 'Spodnie płócienne', 'store_category_id' => 5]);
        StoreCategory::create(['name' => 'Spodnie wełniane', 'store_category_id' => 5]);

        StoreCategory::create(['name' => 'Namioty', 'store_category_id' => 6]);
        StoreCategory::create(['name' => 'Miski', 'store_category_id' => 6]);
        StoreCategory::create(['name' => 'Łyżki', 'store_category_id' => 6]);
        StoreCategory::create(['name' => 'Paleniska', 'store_category_id' => 6]);

        // StoreCategory::factory()->count(15)->create();
    }
}
