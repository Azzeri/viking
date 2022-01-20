<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('group_info')->insert([
            'short_name' => 'Barbarian',
            'full_name' => 'Kuźnia Barbarian',
            'motto' => 'Wykuj z nami swoje marzenia',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',

            'addr_street' => 'Sezamkowa',
            'addr_number' => '56/3',
            'addr_postCode' => '48-330',
            'addr_town' => 'Nysa',

            'phone' => '661 661 661',
            'email' => 'jkowalski@gmail.com',

            'facebook' => 'https://facebook.com',
            'youtube' => 'https://youtube.com',
            'instagram' => 'https://instagram.com',

            'link1_label' => 'Wilki',
            'link1_url' => 'https://facebook.com',
            'link2_label' => 'Zające',
            'link2_url' => 'https://facebook.com',
            'link3_label' => 'Bobry',
            'link3_url' => 'https://facebook.com',
            'link4_label' => 'Łososie',
            'link4_url' => 'https://facebook.com',
        ]);
    }
}