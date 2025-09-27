<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CategoriesTableSeeder::class,
            ConditionsTableSeeder::class,
            UsersTableSeeder::class,
            ProductsTableSeeder::class,
            Product_CategoryTableSeeder::class,
            ProfileTableSeeder::class,
            LikeTableSeeder::class,
            CommentTableSeeder::class,
        ]);
    }
}
