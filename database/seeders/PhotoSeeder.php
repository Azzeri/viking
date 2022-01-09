<?php

namespace Database\Seeders;

use App\Models\Photo;
use Illuminate\Database\Seeder;

class PhotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Photo::factory()->count(100)->create();

        Photo::create([
            'path' => 'https://cdn.pixabay.com/photo/2021/02/18/13/28/man-6027220_960_720.jpg',
            'photo_category_id' => 5,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        Photo::create([
            'path' => 'https://cdn.pixabay.com/photo/2016/07/02/19/45/viking-1493685_960_720.jpg',
            'photo_category_id' => 5,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        Photo::create([
            'path' => 'https://cdn.pixabay.com/photo/2020/05/22/20/44/warrior-5207128_960_720.jpg',
            'photo_category_id' => 5,
            'created_at' => '2022-01-09 00:00:00'
        ]);


        Photo::create([
            'path' => 'https://cdn.pixabay.com/photo/2017/10/06/13/58/old-village-2823175_960_720.jpg',
            'photo_category_id' => 6,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        Photo::create([
            'path' => 'https://cdn.pixabay.com/photo/2021/05/22/15/53/castle-6273960_960_720.jpg',
            'photo_category_id' => 7,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        Photo::create([
            'path' => 'https://cdn.pixabay.com/photo/2018/08/12/15/29/hintersee-3601004_960_720.jpg',
            'photo_category_id' => 8,
            'created_at' => '2022-01-09 00:00:00'
        ]);


        Photo::create([
            'path' => 'https://cdn.pixabay.com/photo/2020/08/17/08/45/axe-5494732_960_720.jpg',
            'photo_category_id' => 9,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        Photo::create([
            'path' => 'https://cdn.pixabay.com/photo/2019/07/25/07/18/axe-4361880_960_720.jpg',
            'photo_category_id' => 9,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        Photo::create([
            'path' => 'https://cdn.pixabay.com/photo/2019/05/07/21/04/helm-4187076_960_720.jpg',
            'photo_category_id' => 10,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        Photo::create([
            'path' => 'https://cdn.pixabay.com/photo/2020/08/04/17/25/medieval-5463419_960_720.jpg',
            'photo_category_id' => 10,
            'created_at' => '2022-01-09 00:00:00'
        ]);
    }
}
