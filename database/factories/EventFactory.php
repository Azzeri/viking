<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->realText($maxNbChars = 255, $indexSize = 2),
            'addrStreet' => $this->faker->streetName(),
            'addrNumber' => $this->faker->buildingNumber(),
            'addrHouseNumber' => $this->faker->optional()->buildingNumber(),
            'addrPostCode' => $this->faker->postcode(),
            'addrTown' => $this->faker->city(),
            'date_start' => $this->faker->date(),
            'is_finished' => $this->faker->boolean(),
            'date_end' => $this->faker->date(),
            'time_start' => $this->faker->time(),
            'time_end' => $this->faker->optional()->time(),
            'items' => json_encode($this->faker->randomElements([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30], 15)),
            'participants' => json_encode($this->faker->randomElements([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16], 7)),
        ];
    }
}