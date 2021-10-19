<?php

namespace Database\Seeders;

use App\Models\Privilege;
use Illuminate\Database\Seeder;

class PrivilegeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Privilege::create(['name' => 'Administrator']);
        Privilege::create(['name' => 'Koordynator']);
        Privilege::create(['name' => 'Członek grupy']);
        Privilege::create(['name' => 'Użytkownik']);
    }
}