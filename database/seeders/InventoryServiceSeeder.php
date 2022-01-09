<?php

namespace Database\Seeders;

use App\Models\InventoryService;
use Illuminate\Database\Seeder;

class InventoryServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // InventoryService::factory()->count(30)->create();
        InventoryService::create([
            'name' => 'Wzmonić jelec',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure.',
            'date_due' => '2022-02-10',
            'notification' => true,
            'inventory_item_id' => 1,
            'assigned_user' => 1,
            'created_by' => 1,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        InventoryService::create([
            'name' => 'Osadzić głownię',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure.',
            'inventory_item_id' => 1,
            'assigned_user' => 2,
            'created_by' => 1,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        InventoryService::create([
            'name' => 'Wyklepać wgniecenie',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure.',
            'inventory_item_id' => 5,
            'date_due' => '2022-02-15',
            'notification' => false,
            'created_by' => 3,
            'created_at' => '2022-01-09 00:00:00'
        ]);
    }
}
