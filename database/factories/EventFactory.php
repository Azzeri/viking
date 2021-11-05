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
            'addrPostcode' => $this->faker->postcode(),
            'addrTown' => $this->faker->city(),
            'date_start' => $this->faker->date(),
            'date_end' => $this->faker->date(),
            'time_start' => $this->faker->time(),
            'time_end' => $this->faker->optional()->time(),
        ];
    }
}
