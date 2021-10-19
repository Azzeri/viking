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
            'email' => 'admin@admin.admin',
            'date_birth' => '1999-10-10',
            'privilege_id' => Privilege::IS_ADMIN,
            'password' => Hash::make('qwerty'),
            'remember_token' => Str::random(10),
            'email_verified_at' => now(),
        ]);

        User::factory()->count(100)->create();
    }
}
