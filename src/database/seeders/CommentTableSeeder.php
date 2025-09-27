<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('comments')->insert([
            [
                'product_id' => 2,
                'user_id' => 1,
                'content' => '質問です'
            ],
            [
                'product_id' => 3,
                'user_id' => 3,
                'content' => 'コメントです'
            ],
            [
                'product_id' => 2,
                'user_id' => 3,
                'content' => '回答です'
            ],
            [
                'product_id' => 2,
                'user_id' => 1,
                'content' => '再質問です'
            ],
            [
                'product_id' => 2,
                'user_id' => 3,
                'content' => '回答です'
            ],
            [
                'product_id' => 3,
                'user_id' => 2,
                'content' => 'コメント(2)です'
            ],
        ]);

    }
}
