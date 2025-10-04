<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PurchasesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('purchases')->insert([
            [
                'product_id' => 2,
                'user_id' => 1,
                'payment_method' => 2,
                'zipcode' => '111-2345',
                'address' => '東京都港区',
                'building' => 'アパート',
            ],
        ]);
    }
}
