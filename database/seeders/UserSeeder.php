<?php

namespace Database\Seeders;

use App\Models\Privilege;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'surname' => 'Admin',
            'nickname' => 'Ragnar',
            'role' => 'Wódz',
            'email' => 'admin@admin.admin',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
            'date_birth' => '1999-10-10',
            'privilege_id' => Privilege::IS_ADMIN,
            'password' => Hash::make('qwerty'),
            'remember_token' => Str::random(10),
            'email_verified_at' => '2022-01-09 00:00:00',
            'created_at' => '2022-01-09 00:00:00'
        ]);

        User::create([
            'name' => 'Jan',
            'surname' => 'Kowalski',
            'nickname' => 'Floki',
            'role' => 'Kowal',
            'email' => 'jk@viking.pl',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
            'date_birth' => '1975-11-20',
            'privilege_id' => Privilege::IS_COORDINATOR,
            'password' => Hash::make('qwerty'),
            'remember_token' => Str::random(10),
            'email_verified_at' => '2022-01-09 00:00:00',
            'created_at' => '2022-01-09 00:00:00'
        ]);

        User::create([
            'name' => 'Adam',
            'surname' => 'Nowak',
            'nickname' => 'Rollo',
            'role' => 'Wojownik',
            'email' => 'an@viking.pl',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
            'date_birth' => '1998-02-16',
            'privilege_id' => Privilege::IS_COORDINATOR,
            'password' => Hash::make('qwerty'),
            'remember_token' => Str::random(10),
            'email_verified_at' => '2022-01-09 00:00:00',
            'created_at' => '2022-01-09 00:00:00'
        ]);

        // User::create([
        //     'name' => '0EuuW7DJI51wKPX5zGdKdhwUdud1m2pV',
        //     'surname' => '92NOpAGuA3YmtV6h6EQnUwj7URPwum41',
        //     'nickname' => '92NOpAGuA3YmtV6h6EQnUwj7URPwum41',
        //     'role' =>'X5nAgulI2637xACCttztnwEmDN889CqaFoMbzWzHRuYN5KNqTlxO2K2gMNHEJ8PB',
        //     'email' => 'FRytbkc8qHn7@CNHCkRp9jy66ID6rjeWX8qntGBxQBGkOkXfwkBJGf9IopCs.3tB',
        //     'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.dddddddddddddddddddddddd',
        //     'date_birth' => '1998-02-16',
        //     'privilege_id' => Privilege::IS_COORDINATOR,
        //     'password' => Hash::make('qwerty'),
        //     'remember_token' => Str::random(10),
        //     'email_verified_at' => now(),
        // ]);

        // User::factory()->count(16)->create();
    }
}
