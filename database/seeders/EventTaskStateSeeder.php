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
        EventTaskState::create(['name' => 'niezatwierdzone']);
        EventTaskState::create(['name' => 'niewykonane']);
        EventTaskState::create(['name' => 'w trakcie']);
        EventTaskState::create(['name' => 'wykonane']);
        EventTaskState::create(['name' => 'odrzucone']);
    }
}
