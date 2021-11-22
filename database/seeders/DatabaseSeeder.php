<?php

namespace Database\Seeders;

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
            InventoryItemSeeder::class,
            InventoryServiceSeeder::class,
            StoreCategorySeeder::class,

        ]);
    }
}
