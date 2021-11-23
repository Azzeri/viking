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
        StoreItem::factory()->count(30)->create();
    }
}
