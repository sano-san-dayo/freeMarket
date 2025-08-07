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
                'name' => 'dummy01',
                'email' => 'dummy01@foo.bar',
                'email_verified_at' => '2025-01-01 01:23:45',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'dummy02',
                'email' => 'dummy02@foo.bar',
                'email_verified_at' => '2025-01-01 01:23:45',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'dummy03',
                'email' => 'dummy03@foo.bar',
                'email_verified_at' => '2025-01-01 01:23:45',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'dummy04',
                'email' => 'dummy04@foo.bar',
                'email_verified_at' => '2025-01-01 01:23:45',
                'password' => bcrypt('password'),
            ],
        ]);
    }
}
