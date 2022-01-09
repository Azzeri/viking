<?php

namespace Database\Seeders;

use App\Models\EventTaskState;
use Illuminate\Database\Seeder;

class EventTaskStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EventTaskState::create(['name' => 'niezatwierdzone', 'created_at' => '2022-01-09 00:00:00']);
        EventTaskState::create(['name' => 'niewykonane', 'created_at' => '2022-01-09 00:00:00']);
        EventTaskState::create(['name' => 'w trakcie', 'created_at' => '2022-01-09 00:00:00']);
        EventTaskState::create(['name' => 'wykonane', 'created_at' => '2022-01-09 00:00:00']);
        EventTaskState::create(['name' => 'odrzucone', 'created_at' => '2022-01-09 00:00:00']);
    }
}
