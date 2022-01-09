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
        // StoreRequest::factory()->count(50)->create(); 1-7
        StoreRequest::create([
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure.',
            'client_name' => 'Mariusz',
            'client_phone' => '857 324 651',
            'client_email' => 'mariusz@gmail.com',
            'is_accepted' => false,
            'store_item_id' => 1,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        StoreRequest::create([
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure.',
            'client_name' => 'Kasia',
            'client_phone' => '345 764 214',
            'client_email' => 'kasia@gmail.com',
            'is_accepted' => false,
            'store_item_id' => 1,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        StoreRequest::create([
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure.',
            'client_name' => 'Radek',
            'client_email' => 'radek@gmail.com',
            'is_accepted' => false,
            'store_item_id' => 4,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        StoreRequest::create([
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure.',
            'client_name' => 'Jan',
            'client_phone' => '768 987 234',
            'client_email' => 'jan@gmail.com',
            'is_accepted' => false,
            'store_item_id' => 7,
            'created_at' => '2022-01-09 00:00:00'
        ]);

        StoreRequest::create([
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure.',
            'client_name' => 'Ania',
            'client_email' => 'ania@gmail.com',
            'is_accepted' => true,
            'store_item_id' => 4,
            'note' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure.',
            'created_at' => '2022-01-09 00:00:00'
        ]);

        StoreRequest::create([
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure.',
            'client_name' => 'Tadek',
            'client_phone' => '879 234 123',
            'client_email' => 'tadek@gmail.com',
            'is_accepted' => true,
            'store_item_id' => 1,
            'note' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure.',
            'created_at' => '2022-01-09 00:00:00'
        ]);
    }
}
