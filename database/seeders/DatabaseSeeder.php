<?php

namespace Database\Seeders;

use App\Models\PhotoCategory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PrivilegeSeeder::class,
            EventSeeder::class,
            UserSeeder::class,
            InventoryCategorySeeder::class,
            // InventoryItemSeeder::class,
            // InventoryServiceSeeder::class,
            StoreCategorySeeder::class,
            // StoreItemSeeder::class,
            // StoreRequestSeeder::class,
            EventTaskStateSeeder::class,
            EventTaskSeeder::class,
            EventSubTaskSeeder::class,
            PostSeeder::class,
            PhotoCategorySeeder::class
        ]);
    }
}
