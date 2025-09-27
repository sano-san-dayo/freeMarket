<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'user01',
                'email' => 'user01@foo.bar',
                'email_verified_at' => '2025-01-01 01:23:45',
                'password' => '$2y$10$OoVNnViW/zihGBWgvfr1W.9ByL/VWI6omCQF6/ChfYSVR6uj9810G',
            ],
            [
                'name' => 'user02',
                'email' => 'user02@foo.bar',
                'email_verified_at' => '2025-01-01 01:23:45',
                'password' => '$2y$10$zEh2fRlKJqHsjBuYo36D6e3btueSC0.R8UqFdnFhCWXITozk8/rui',
            ],
            [
                'name' => 'user03',
                'email' => 'user03@foo.bar',
                'email_verified_at' => '2025-01-01 01:23:45',
                'password' => '$2y$10$N47Ui48fFrYnd.GPlZrbVO3pZ5MerVxigHG8a0FTXCwDp8XBw2kp2',
            ],
            [
                'name' => 'user04',
                'email' => 'user04@foo.bar',
                'email_verified_at' => '2025-01-01 01:23:45',
                'password' => '$2y$10$m8OO6pVvMmbrmEPzfwO71uDpq1MEbcOcmnPGctrtUXujSgRWtoHry',
            ],
        ]);
    }
}
