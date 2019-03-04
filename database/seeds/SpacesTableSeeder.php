<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpacesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $spaces = [
            [
                'id' => 1,
                'data' => '1.01',
                'level_id' => 1,
                'created_at' => '2019-03-03 20:23:00',
                'updated_at' => '2019-03-03 20:23:00',
            ],
            [
                'id' => 2,
                'data' => '1.02',
                'level_id' => 1,
                'created_at' => '2019-03-03 20:23:00',
                'updated_at' => '2019-03-03 20:23:00',
            ],
            [
                'id' => 3,
                'data' => '1.03',
                'level_id' => 1,
                'created_at' => '2019-03-03 20:23:00',
                'updated_at' => '2019-03-03 20:23:00',
            ],
            [
                'id' => 4,
                'data' => '1.04',
                'level_id' => 1,
                'created_at' => '2019-03-03 20:23:00',
                'updated_at' => '2019-03-03 20:23:00',
            ],
            [
                'id' => 5,
                'data' => '1.05',
                'level_id' => 1,
                'created_at' => '2019-03-03 20:23:00',
                'updated_at' => '2019-03-03 20:23:00',
            ],
            [
                'id' => 6,
                'data' => '1.06',
                'level_id' => 1,
                'created_at' => '2019-03-03 20:23:00',
                'updated_at' => '2019-03-03 20:23:00',
            ],
            [
                'id' => 7,
                'data' => '1.07',
                'level_id' => 1,
                'created_at' => '2019-03-03 20:23:00',
                'updated_at' => '2019-03-03 20:23:00',
            ],
        ];

        foreach ($spaces as $space) {
            DB::table('spaces')->insert($space);
        }

    }
}
