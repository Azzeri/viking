<?php

namespace Database\Seeders;

use App\Models\EventSubTask;
use Illuminate\Database\Seeder;

class EventSubTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EventSubTask::factory()->count(200)->create();
    }
}
