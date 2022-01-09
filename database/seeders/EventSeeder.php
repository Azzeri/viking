<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Event::create([
            'name' => 'Wczesnośredniowieczne pole bitwy w Ryczynie',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure.',
            'addrStreet' => 'Lipowa',
            'addrNumber' => '1',
            'addrPostCode' => '48-300',
            'addrTown' => 'Ryczyna',
            'date_start' => '2022-10-10',
            'date_end' => '2022-10-11',
            'time_start' => '15:00:00',
            'time_end' => '22:00:00',
            'photo_path' => 'https://cdn.pixabay.com/photo/2017/10/06/13/58/old-village-2823175_960_720.jpg',
            'created_at' => '2022-01-09 00:00:00'
        ]);

        Event::create([
            'name' => 'Warsztaty wczesnośredniowieczne w grodzie Byczyńskim',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure.',
            'addrStreet' => 'Marii Skłodowskiej',
            'addrNumber' => '15',
            'addrPostCode' => '48-370',
            'addrTown' => 'Byczyna',
            'date_start' => '2022-05-23',
            'date_end' => '2022-05-26',
            'time_start' => '10:00:00',
            'time_end' => '00:00:00',
            'photo_path' => 'https://cdn.pixabay.com/photo/2021/05/22/15/53/castle-6273960_960_720.jpg',
            'created_at' => '2022-01-09 00:00:00'
        ]);

        Event::create([
            'name' => 'Rekonstrukcja najazdów na wyspie Wolin',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure.',
            'addrStreet' => 'Jana Pawła II',
            'addrNumber' => '88',
            'addrPostCode' => '48-356',
            'addrTown' => 'Wolin',
            'date_start' => '2022-06-01',
            'date_end' => '2022-06-08',
            'time_start' => '00:00:00',
            'time_end' => '00:00:00',
            'photo_path' => 'https://cdn.pixabay.com/photo/2018/08/12/15/29/hintersee-3601004_960_720.jpg',
            'created_at' => '2022-01-09 00:00:00'
        ]);

        // Event::factory()->count(15)->create();
    }
}
