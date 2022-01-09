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
        PhotoCategory::create([//1
            'name' => 'Członkowie grupy', 'photo_path' => 'https://cdn.pixabay.com/photo/2018/11/13/21/43/instagram-3814049_960_720.png',
            'created_at' => '2022-01-09 00:00:00'
        ]);

        PhotoCategory::create([//2
            'name' => 'Wydarzenia', 'photo_path' => 'https://cdn.pixabay.com/photo/2017/06/10/06/39/calender-2389150_960_720.png',
            'created_at' => '2022-01-09 00:00:00'
        ]);

        PhotoCategory::create([//3
            'name' => 'Nasze produkty', 'photo_path' => 'https://cdn.pixabay.com/photo/2014/04/02/17/09/sword-308087_960_720.png',
            'created_at' => '2022-01-09 00:00:00'
        ]);

        PhotoCategory::create([//4
            'name' => 'Nasz sprzęt', 'photo_path' => 'https://cdn.pixabay.com/photo/2013/07/12/14/33/hatchet-148474_960_720.png',
            'created_at' => '2022-01-09 00:00:00'
        ]);


        PhotoCategory::create([//5
            'name' => 'Wojownicy', 'photo_category_id' => 1,
            'created_at' => '2022-01-09 00:00:00'
        ]);


        PhotoCategory::create([//6
            'name' => 'Ryczyna 2022', 'photo_category_id' => 2,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        PhotoCategory::create([//7
            'name' => 'Byczyna 2022', 'photo_category_id' => 2,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        PhotoCategory::create([//8
            'name' => 'Wolin 2022', 'photo_category_id' => 2,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        PhotoCategory::create([//9
            'name' => 'Topory', 'photo_category_id' => 3,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        PhotoCategory::create([//10
            'name' => 'Hełmy', 'photo_category_id' => 4,
            'created_at' => '2022-01-09 00:00:00'
        ]);


        // PhotoCategory::factory()->count(15)->create();
    }
}
