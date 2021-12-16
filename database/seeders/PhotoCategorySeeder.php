<?php

namespace Database\Seeders;

use App\Models\PhotoCategory;
use Illuminate\Database\Seeder;

class PhotoCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PhotoCategory::create([
            'name' => 'Kategoria 1'
        ]);

        PhotoCategory::create([
            'name' => 'Subkategoria 1',
            'photo_category_id' => 1
        ]);
        // PhotoCategory::factory()->count(15)->create();
    }
}
