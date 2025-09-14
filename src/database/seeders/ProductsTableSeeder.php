<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            [
                'user_id' => 1,
                'name' => '腕時計',
                'price' => 15000,
                'brand' => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'image' => 'Armani+Mens+Clock.jpg',
                'condition' => 1,
            ],
            [
                'user_id' => 3,
                'name' => 'HDD',
                'price' => 5000,
                'brand' => '西芝',
                'description' => '高速で信頼の高いハードディスク',
                'image' => 'HDD+Hard+Disk.jpg',
                'condition' => 2,
            ],
            [
                'user_id' => 2,
                'name' => '玉ねぎ3束',
                'price' => 300,
                'brand' => 'なし',
                'description' => '新鮮な玉ねぎ3束のセット',
                'image' => 'iLoveIMG+d.jpg',
                'condition' => 3,
            ],
            [
                'user_id' => 3,
                'name' => '革靴',
                'price' => 4000,
                'brand' => '',
                'description' => 'クラシックなデザインの革靴',
                'image' => 'Leather+Shoes+Product+Photo.jpg',
                'condition' => 4,
            ],
            [
                'user_id' => 1,
                'name' => 'ノートPC',
                'price' => 45000,
                'brand' => '',
                'description' => '高性能なノートパソコン',
                'image' => 'Living+Room+Laptop.jpg',
                'condition' => 1,
            ],
            [
                'user_id' => 1,
                'name' => 'マイク',
                'price' => 8000,
                'brand' => 'なし',
                'description' => '高音質のレコーディング用マイク',
                'image' => 'Music+Mic+4632231.jpg',
                'condition' => 2,
            ],
            [
                'user_id' => 3,
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'brand' => '',
                'description' => 'おしゃれなショルダーバッグ',
                'image' => 'Purse+fashion+pocket.jpg',
                'condition' => 3,
            ],
            [
                'user_id' => 4,
                'name' => 'タンブラー',
                'price' => 500,
                'brand' => 'なし',
                'description' => '使いやすいタンブラー',
                'image' => 'Tumbler+souvenir.jpg',
                'condition' => 4,
            ],
            [
                'user_id' => 4,
                'name' => 'コーヒーミル',
                'price' => 4000,
                'brand' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'image' => 'Waitress+with+Coffee+Grinder.jpg',
                'condition' => 1,
            ],
            [
                'user_id' => 2,
                'name' => 'メイクセット',
                'price' => 2500,
                'brand' => '',
                'description' => '便利なメイクアップセット',
                'image' => '外出メイクアップセット.jpg',
                'condition' => 2,
            ],
        ]);
    }
}
