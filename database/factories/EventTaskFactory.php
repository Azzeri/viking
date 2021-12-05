<?php

namespace Database\Factories;

use App\Models\EventTask;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventTaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EventTask::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->optional()->realText($maxNbChars = 255, $indexSize = 2),
            'date_due' => $this->faker->date(),
            'user_id' => $this->faker->numberBetween(1, 5),
            'event_id' => $this->faker->numberBetween(1, 30),
        ];
    }
}