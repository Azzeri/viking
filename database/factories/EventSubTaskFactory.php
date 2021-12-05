<?php

namespace Database\Factories;

use App\Models\EventSubTask;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventSubTaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EventSubTask::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(3, true),
            'date_due' => $this->faker->date(),
            'event_task_id' => $this->faker->numberBetween(1, 50),
        ];
    }
}
