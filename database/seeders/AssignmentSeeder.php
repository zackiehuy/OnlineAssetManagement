<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AssignmentSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        DB::table('assignment')->insert([
            ['user_id' => '2','asset_id' => '1','started_date' => '2020-10-10',
            'status_id' => '2']

        ]);
    }
}
