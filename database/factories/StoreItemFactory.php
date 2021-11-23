<?php

namespace Database\Factories;

use App\Models\StoreItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoreItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StoreItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(2, true),
            'quantity' => $this->faker->numberBetween(1, 50),
            'store_category_id' => $this->faker->numberBetween(1, 15),
            'description' => $this->faker->realText($maxNbChars = 255, $indexSize = 2),
            'price' => $this->faker->randomFloat(2)
        ];
    }
}
