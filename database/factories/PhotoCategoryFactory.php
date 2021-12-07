<?php

namespace Database\Factories;

use App\Models\PhotoCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class PhotoCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PhotoCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(3, true),
            'photo_category_id' => $this->faker->numberBetween(1, 15),
        ];
    }
}