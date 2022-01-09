<?php

namespace Database\Seeders;

use App\Models\StoreItem;
use Illuminate\Database\Seeder;

class StoreItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // StoreItem::factory()->count(30)->create();
        storeItem::create([
            'name' => 'Miecz długi',
            'quantity' => 12,
            'store_category_id' => 7,
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure.',
            'photo_path' => 'https://cdn.pixabay.com/photo/2015/08/24/09/59/historical-odtworstwo-904716_960_720.jpg',
            'price' => 150.00,
            'craftspeople' => '[2,3]',
            'created_at' => '2022-01-09 00:00:00'
        ]);

        storeItem::create([
            'name' => 'Miecz zdobiony',
            'quantity' => 17,
            'store_category_id' => 7,
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure.',
            'photo_path' => 'https://cdn.pixabay.com/photo/2016/11/26/00/33/sabre-1859774_960_720.png',
            'price' => 350.00,
            'craftspeople' => '[2,3]',
            'created_at' => '2022-01-09 00:00:00'
        ]);

        storeItem::create([
            'name' => 'Topór zdobiony',
            'quantity' => 41,
            'store_category_id' => 9,
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure.',
            'photo_path' => 'https://cdn.pixabay.com/photo/2020/08/17/08/45/axe-5494732_960_720.jpg',
            'price' => 250.00,
            'craftspeople' => '[2]',
            'created_at' => '2022-01-09 00:00:00'
        ]);

        storeItem::create([
            'name' => 'Topór jednoręczny',
            'quantity' => 8,
            'store_category_id' => 9,
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure.',
            'photo_path' => 'https://cdn.pixabay.com/photo/2019/07/25/07/18/axe-4361880_960_720.jpg',
            'price' => 100.00,
            'craftspeople' => '[2]',
            'created_at' => '2022-01-09 00:00:00'
        ]);

        storeItem::create([
            'name' => 'Hełm nosal',
            'quantity' => 10,
            'store_category_id' => 11,
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure.',
            'photo_path' => 'https://cdn.pixabay.com/photo/2019/05/07/21/04/helm-4187076_960_720.jpg',
            'price' => 400.00,
            'craftspeople' => '[3]',
            'created_at' => '2022-01-09 00:00:00'
        ]);

        storeItem::create([
            'name' => 'Hełm geirmund bez kolczugi',
            'quantity' => 15,
            'store_category_id' => 12,
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure.',
            'photo_path' => 'https://cdn.pixabay.com/photo/2020/08/04/17/25/medieval-5463419_960_720.jpg',
            'price' => 650.00,
            'craftspeople' => '[3]',
            'created_at' => '2022-01-09 00:00:00'
        ]);

        storeItem::create([
            'name' => 'Miecz dwuręczny',
            'quantity' => 10,
            'store_category_id' => 8,
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure.',
            'photo_path' => 'https://cdn.pixabay.com/photo/2015/12/06/00/04/sword-1078968_960_720.jpg',
            'price' => 500.00,
            'craftspeople' => '[2,3]',
            'created_at' => '2022-01-09 00:00:00'
        ]);
    }
}
