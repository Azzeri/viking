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
        StoreCategory::create([
            'name' => 'Miecze', 'photo_path' => 'https://cdn.pixabay.com/photo/2013/07/13/11/45/sword-158585_960_720.png',
            'created_at' => '2022-01-09 00:00:00'
        ]);
        StoreCategory::create([
            'name' => 'Topory', 'photo_path' => 'https://cdn.pixabay.com/photo/2012/05/04/10/04/axe-47042_960_720.png',
            'created_at' => '2022-01-09 00:00:00'
        ]);
        StoreCategory::create([
            'name' => 'Hełmy', 'photo_path' => 'https://cdn.pixabay.com/photo/2014/04/03/00/42/helmet-309161_960_720.png',
            'created_at' => '2022-01-09 00:00:00'
        ]);
        StoreCategory::create([
            'name' => 'Koszule', 'photo_path' => 'https://cdn.pixabay.com/photo/2012/04/14/16/20/t-shirt-34481_960_720.png',
            'created_at' => '2022-01-09 00:00:00'
        ]);
        StoreCategory::create([
            'name' => 'Spodnie', 'photo_path' => 'https://cdn.pixabay.com/photo/2016/03/31/19/24/clothes-1294974_960_720.png',
            'created_at' => '2022-01-09 00:00:00'
        ]);
        StoreCategory::create([
            'name' => 'Obóz', 'photo_path' => 'https://cdn.pixabay.com/photo/2016/11/21/16/03/campfire-1846142_960_720.jpg',
            'created_at' => '2022-01-09 00:00:00'
        ]);


        StoreCategory::create([
            'name' => 'Miecze jednoręczne', 'store_category_id' => 1,
            'created_at' => '2022-01-09 00:00:00'
        ]);
        StoreCategory::create([
            'name' => 'Miecze dwuręczne', 'store_category_id' => 1,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        StoreCategory::create([
            'name' => 'Topory jednoręczne', 'store_category_id' => 2,
            'created_at' => '2022-01-09 00:00:00'
        ]);
        StoreCategory::create([
            'name' => 'Topory dwuręczne', 'store_category_id' => 2,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        StoreCategory::create([
            'name' => 'Nosale', 'store_category_id' => 3,
            'created_at' => '2022-01-09 00:00:00'
        ]);
        StoreCategory::create([
            'name' => 'Geirmundy', 'store_category_id' => 3,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        StoreCategory::create([
            'name' => 'Koszule płócienne', 'store_category_id' => 4,
            'created_at' => '2022-01-09 00:00:00'
        ]);
        StoreCategory::create([
            'name' => 'Koszule wełniane', 'store_category_id' => 4,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        StoreCategory::create([
            'name' => 'Spodnie płócienne', 'store_category_id' => 5,
            'created_at' => '2022-01-09 00:00:00'
        ]);
        StoreCategory::create([
            'name' => 'Spodnie wełniane', 'store_category_id' => 5,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        StoreCategory::create([
            'name' => 'Namioty', 'store_category_id' => 6,
            'created_at' => '2022-01-09 00:00:00'
        ]);
        StoreCategory::create([
            'name' => 'Miski', 'store_category_id' => 6,
            'created_at' => '2022-01-09 00:00:00'
        ]);
        StoreCategory::create([
            'name' => 'Łyżki', 'store_category_id' => 6,
            'created_at' => '2022-01-09 00:00:00'
        ]);
        StoreCategory::create([
            'name' => 'Paleniska', 'store_category_id' => 6,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        // StoreCategory::factory()->count(15)->create();
    }
}
