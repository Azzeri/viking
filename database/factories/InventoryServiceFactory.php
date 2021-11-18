<?php

namespace Database\Factories;

use App\Models\InventoryService;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryServiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InventoryService::class;

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
            'notification' => $this->faker->boolean(),
            'description' => $this->faker->optional()->realText($maxNbChars = 255, $indexSize = 2),
            'assigned_user' => $this->faker->optional()->numberBetween(1, 5),
            'inventory_item_id' => $this->faker->numberBetween(1, 30)
        ];
    }
}