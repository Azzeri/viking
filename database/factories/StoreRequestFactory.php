<?php

namespace Database\Factories;

use App\Models\StoreRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoreRequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StoreRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'description' => $this->faker->realText($maxNbChars = 255, $indexSize = 2),
            'client_name' => $this->faker->name(),
            'client_phone' => $this->faker->optional()->phoneNumber(),
            'client_email' => $this->faker->safeEmail(),
            'store_item_id' => $this->faker->numberBetween(1, 1),
        ];
    }
}
