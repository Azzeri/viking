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
        // EventTask::factory()->count(50)->create();
        EventTask::create([
            'name' => 'Wynająć miejsce', 
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 
            'date_due' => '2020-09-01', 
            'user_id' => 1, 
            'event_id' => 1, 
            'event_task_state_id' => 1, 
            'assigned_user' => 1,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        EventTask::create([
            'name' => 'Zebrać ludzi', 
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 
            'date_due' => '2020-09-01', 
            'user_id' => 2, 
            'event_id' => 1, 
            'event_task_state_id' => 1, 
            'assigned_user' => 2,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        EventTask::create([
            'name' => 'Wynająć nagłośnienie', 
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 
            'date_due' => '2020-09-01', 
            'user_id' => 3, 
            'event_id' => 1, 
            'event_task_state_id' => 1, 
            'assigned_user' => 3,
            'created_at' => '2022-01-09 00:00:00'
        ]);


        EventTask::create([
            'name' => 'Wynająć miejsce', 
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 
            'date_due' => '2020-04-01', 
            'user_id' => 1, 
            'event_id' => 2, 
            'event_task_state_id' => 1, 
            'assigned_user' => 1,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        EventTask::create([
            'name' => 'Zebrać ludzi', 
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 
            'date_due' => '2020-04-01', 
            'user_id' => 2, 
            'event_id' => 2, 
            'event_task_state_id' => 1, 
            'assigned_user' => 2,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        EventTask::create([
            'name' => 'Wynająć nagłośnienie', 
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 
            'date_due' => '2020-04-01', 
            'user_id' => 3, 
            'event_id' => 2, 
            'event_task_state_id' => 1, 
            'assigned_user' => 3,
            'created_at' => '2022-01-09 00:00:00'
        ]);


        EventTask::create([
            'name' => 'Wynająć miejsce', 
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 
            'date_due' => '2020-05-01', 
            'user_id' => 1, 
            'event_id' => 3, 
            'event_task_state_id' => 1, 
            'assigned_user' => 1,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        EventTask::create([
            'name' => 'Zebrać ludzi', 
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 
            'date_due' => '2020-05-01', 
            'user_id' => 2, 
            'event_id' => 3, 
            'event_task_state_id' => 1, 
            'assigned_user' => 2,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        EventTask::create([
            'name' => 'Wynająć nagłośnienie', 
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 
            'date_due' => '2020-05-01', 
            'user_id' => 3, 
            'event_id' => 3, 
            'event_task_state_id' => 1, 
            'assigned_user' => 3,
            'created_at' => '2022-01-09 00:00:00'
        ]);
    }
}
