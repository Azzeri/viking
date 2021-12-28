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
            'description' => 'Dawny gród na Dolnym Śląsku, nad Odrą, znajduje się w odległości ok. 37 km na południowy wschód od Wrocławia, w podoławskich lasach[1]. Ryczyn jest wymieniany w bulli papieskiej w 1093 roku oraz w Kronice Czechów Kosmasa z Pragi.',
            'addrStreet' => 'Lipowa',
            'addrNumber' => '1',
            'addrPostCode' => '48-300',
            'addrTown' => 'Ryczyna',
            'date_start' => '2022-10-10',
            'date_end' => '2022-10-11',
            'time_start' => '15:00:00',
        ]);

        // Event::factory()->count(15)->create();
    }
}
