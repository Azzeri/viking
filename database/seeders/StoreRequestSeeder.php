<?php

namespace Database\Seeders;

use App\Models\StoreRequest;
use Illuminate\Database\Seeder;

class StoreRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StoreRequest::factory()->count(50)->create();
    }
}
