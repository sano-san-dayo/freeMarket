<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LikeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('likes')->insert([
            [
                'product_id' => 2,
                'user_id' => 1,
            ],
            [
                'product_id' => 3,
                'user_id' => 3,
            ],
        ]);
    }
}
