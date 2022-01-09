<?php

namespace Database\Seeders;

use App\Models\Privilege;
use Illuminate\Database\Seeder;

class PrivilegeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Privilege::create(['name' => 'Administrator', 'created_at' => '2022-01-09 00:00:00']);
        Privilege::create(['name' => 'Koordynator', 'created_at' => '2022-01-09 00:00:00']);
    }
}
