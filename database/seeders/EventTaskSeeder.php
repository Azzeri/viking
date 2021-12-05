<?php

namespace Database\Seeders;

use App\Models\EventTask;
use Illuminate\Database\Seeder;

class EventTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EventTask::factory()->count(50)->create();
    }
}
