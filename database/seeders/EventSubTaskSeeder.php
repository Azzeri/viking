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
        // EventSubTask::factory()->count(200)->create();
        EventSubTask::create([
            'name' => 'zadzwonić', 
            'event_task_id' => 1,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        EventSubTask::create([
            'name' => 'zatwierdzić', 
            'date_due' => '2020-09-01', 
            'event_task_id' => 1,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        EventSubTask::create([
            'name' => 'zadzwonić', 
            'event_task_id' => 4,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        EventSubTask::create([
            'name' => 'zatwierdzić', 
            'date_due' => '2020-09-01', 
            'event_task_id' => 4,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        EventSubTask::create([
            'name' => 'zadzwonić', 
            'event_task_id' => 7,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        EventSubTask::create([
            'name' => 'zatwierdzić', 
            'date_due' => '2020-09-01', 
            'event_task_id' => 7,
            'created_at' => '2022-01-09 00:00:00'
        ]);
    }
}
