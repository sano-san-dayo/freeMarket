<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfileTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('profiles')->insert([
            [
                'user_id' => 1,
                'zipcode' => '111-2345',
                'address' => '東京都渋谷区神南',
                'building' => 'マンション',
                'image' => 'ひらめいた男性.jpg',
            ],
            [
                'user_id' => 2,
                'zipcode' => '222-6789',
                'address' => '神奈川県横浜市',
                'building' => '',
                'image' => '飲み物と男の子.jpg',
            ],
            [
                'user_id' => 3,
                'zipcode' => '333-0123',
                'address' => '福岡県福岡市',
                'building' => 'アパート',
                'image' => '素敵なおじいさま.jpg',
            ],
            [
                'user_id' => 4,
                'zipcode' => '123-4567',
                'address' => '北海道札幌市',
                'building' => 'メゾン',
                'image' => 'カエルさん.jpg',
            ],
        ]);
    }
}
