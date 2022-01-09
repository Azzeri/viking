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
        InventoryCategory::create([
            'name' => 'Miecze', 'photo_path' => 'https://cdn.pixabay.com/photo/2013/07/13/11/45/sword-158585_960_720.png',
            'created_at' => '2022-01-09 00:00:00'
        ]);
        InventoryCategory::create([
            'name' => 'Topory', 'photo_path' => 'https://cdn.pixabay.com/photo/2012/05/04/10/04/axe-47042_960_720.png',
            'created_at' => '2022-01-09 00:00:00'
        ]);
        InventoryCategory::create([
            'name' => 'Hełmy', 'photo_path' => 'https://cdn.pixabay.com/photo/2014/04/03/00/42/helmet-309161_960_720.png',
            'created_at' => '2022-01-09 00:00:00'
        ]);
        InventoryCategory::create([
            'name' => 'Koszule', 'photo_path' => 'https://cdn.pixabay.com/photo/2012/04/14/16/20/t-shirt-34481_960_720.png',
            'created_at' => '2022-01-09 00:00:00'
        ]);
        InventoryCategory::create([
            'name' => 'Spodnie', 'photo_path' => 'https://cdn.pixabay.com/photo/2016/03/31/19/24/clothes-1294974_960_720.png',
            'created_at' => '2022-01-09 00:00:00'
        ]);
        InventoryCategory::create([
            'name' => 'Obóz', 'photo_path' => 'https://cdn.pixabay.com/photo/2016/11/21/16/03/campfire-1846142_960_720.jpg',
            'created_at' => '2022-01-09 00:00:00'
        ]);


        InventoryCategory::create([
            'name' => 'Miecze jednoręczne', 'inventory_category_id' => 1,
            'created_at' => '2022-01-09 00:00:00'
        ]); //7
        InventoryCategory::create([
            'name' => 'Miecze dwuręczne', 'inventory_category_id' => 1,
            'created_at' => '2022-01-09 00:00:00'
        ]); //8

        InventoryCategory::create([
            'name' => 'Topory jednoręczne', 'inventory_category_id' => 2,
            'created_at' => '2022-01-09 00:00:00'
        ]); //9
        InventoryCategory::create([
            'name' => 'Topory dwuręczne', 'inventory_category_id' => 2,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        InventoryCategory::create([
            'name' => 'Nosale', 'inventory_category_id' => 3,
            'created_at' => '2022-01-09 00:00:00'
        ]); //11
        InventoryCategory::create([
            'name' => 'Geirmundy', 'inventory_category_id' => 3,
            'created_at' => '2022-01-09 00:00:00'
        ]); //12

        InventoryCategory::create([
            'name' => 'Koszule płócienne', 'inventory_category_id' => 4,
            'created_at' => '2022-01-09 00:00:00'
        ]);
        InventoryCategory::create([
            'name' => 'Koszule wełniane', 'inventory_category_id' => 4,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        InventoryCategory::create([
            'name' => 'Spodnie płócienne', 'inventory_category_id' => 5,
            'created_at' => '2022-01-09 00:00:00'
        ]);
        InventoryCategory::create([
            'name' => 'Spodnie wełniane', 'inventory_category_id' => 5,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        InventoryCategory::create([
            'name' => 'Namioty', 'inventory_category_id' => 6,
            'created_at' => '2022-01-09 00:00:00'
        ]);
        InventoryCategory::create([
            'name' => 'Miski', 'inventory_category_id' => 6,
            'created_at' => '2022-01-09 00:00:00'
        ]);
        InventoryCategory::create([
            'name' => 'Łyżki', 'inventory_category_id' => 6,
            'created_at' => '2022-01-09 00:00:00'
        ]);
        InventoryCategory::create([
            'name' => 'Paleniska', 'inventory_category_id' => 6,
            'created_at' => '2022-01-09 00:00:00'
        ]);


        // InventoryCategory::factory()->count(15)->create();
    }
}
