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
            'name' => 'Ryczyna',
            'description' => 'Wydarzenie w ryczenie lorem lorem xdddd',
            'addrStreet' => 'Cicha',
            'addrNumber' => '3',
            'addrPostCode' => '48-300',
            'addrTown' => 'Ryczyna',
            'date_start' => '2021-10-10',
            'date_end' => '2021-10-11',
            'time_start' => '03:53:10',
            'items' => '[1,2,3,4]'
        ]);

        Event::factory()->count(50)->create();
    }
}
