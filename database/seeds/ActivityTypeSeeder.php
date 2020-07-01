<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('activity_types')->insert([
            "name" => 'Page Visit',
            "slug" => 'visit',
        ]);

        DB::table('activity_types')->insert([
            "name" => 'Login',
            "slug" => 'login',
        ]);
    }
}
