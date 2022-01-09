<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Post::factory()->count(100)->create();
        Post::create([
            'title' => 'Uruchomienie strony naszej grupy',
            'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure.',
            'photo_path' => 'https://cdn.pixabay.com/photo/2018/02/13/23/41/nature-3151869_960_720.jpg',
            'user_id' => 1,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        Post::create([
            'title' => 'Nadchodzące wydarzenie: Wczesnośredniowieczne pole bitwy w Ryczynie',
            'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure.',
            'photo_path' => 'https://cdn.pixabay.com/photo/2017/10/06/13/58/old-village-2823175_960_720.jpg',
            'user_id' => 1,
            'resource_link' => 'admin/events/1',
            'created_at' => '2022-01-09 00:01:00'
        ]);

        Post::create([
            'title' => 'Nowi członkowie grupy',
            'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure.',
            'photo_path' => 'https://cdn.pixabay.com/photo/2012/03/01/00/21/bridge-19513_960_720.jpg',
            'user_id' => 1,
            'created_at' => '2022-01-09 00:03:00'
        ]);

        Post::create([
            'title' => 'Nadchodzące wydarzenie: Rekonstrukcja najazdów na wyspie Wolin',
            'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure.',
            'photo_path' => 'https://cdn.pixabay.com/photo/2018/08/12/15/29/hintersee-3601004_960_720.jpg',
            'user_id' => 2,
            'resource_link' => 'admin/events/3',
            'created_at' => '2022-01-09 00:04:00'
        ]);

        Post::create([
            'title' => 'Nasza grupa a covid-19',
            'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure.',
            'photo_path' => 'https://cdn.pixabay.com/photo/2018/06/09/17/25/trees-3464777_960_720.jpg',
            'user_id' => 1,
            'created_at' => '2022-01-09 00:05:00'
        ]);

        Post::create([
            'title' => 'Aktualizacja wizualna naszej strony',
            'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure.',
            'photo_path' => 'https://cdn.pixabay.com/photo/2018/05/12/17/41/forest-3394066_960_720.jpg',
            'user_id' => 1,
            'created_at' => '2022-01-09 00:06:00'
        ]);

        Post::create([
            'title' => 'Nowe i ciekawe materiały edukacyjne',
            'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure.',
            'photo_path' => 'https://cdn.pixabay.com/photo/2017/05/09/03/46/alberta-2297204_960_720.jpg',
            'user_id' => 1,
            'created_at' => '2022-01-09 00:07:00'
        ]);
    }
}
