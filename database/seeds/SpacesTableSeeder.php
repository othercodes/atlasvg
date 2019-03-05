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
                'data' => '1.01',
                'level_id' => 1,
                'created_at' => '2019-03-03 20:23:00',
                'updated_at' => '2019-03-03 20:23:00',
            ],
            [
                'data' => '1.02',
                'level_id' => 1,
                'created_at' => '2019-03-03 20:23:00',
                'updated_at' => '2019-03-03 20:23:00',
            ],
            [
                'data' => '1.03',
                'level_id' => 1,
                'created_at' => '2019-03-03 20:23:00',
                'updated_at' => '2019-03-03 20:23:00',
            ],
            [
                'data' => '1.04',
                'level_id' => 1,
                'created_at' => '2019-03-03 20:23:00',
                'updated_at' => '2019-03-03 20:23:00',
            ],
            [
                'data' => '1.05',
                'level_id' => 1,
                'created_at' => '2019-03-03 20:23:00',
                'updated_at' => '2019-03-03 20:23:00',
            ],
            [
                'data' => '1.06',
                'level_id' => 1,
                'created_at' => '2019-03-03 20:23:00',
                'updated_at' => '2019-03-03 20:23:00',
            ],
            [
                'data' => '1.07',
                'level_id' => 1,
                'created_at' => '2019-03-03 20:23:00',
                'updated_at' => '2019-03-03 20:23:00',
            ],
            [
                'data' => '2.01',
                'level_id' => 2,
                'created_at' => '2019-03-03 20:23:00',
                'updated_at' => '2019-03-03 20:23:00',
            ],
            [
                'data' => '2.02',
                'level_id' => 2,
                'created_at' => '2019-03-03 20:23:00',
                'updated_at' => '2019-03-03 20:23:00',
            ],
            [
                'data' => '2.03',
                'level_id' => 2,
                'created_at' => '2019-03-03 20:23:00',
                'updated_at' => '2019-03-03 20:23:00',
            ],
            [
                'data' => '2.04',
                'level_id' => 2,
                'created_at' => '2019-03-03 20:23:00',
                'updated_at' => '2019-03-03 20:23:00',
            ],
            [
                'data' => '2.05',
                'level_id' => 2,
                'created_at' => '2019-03-03 20:23:00',
                'updated_at' => '2019-03-03 20:23:00',
            ],
            [
                'data' => '2.06',
                'level_id' => 2,
                'created_at' => '2019-03-03 20:23:00',
                'updated_at' => '2019-03-03 20:23:00',
            ],
            [
                'data' => '2.07',
                'level_id' => 2,
                'created_at' => '2019-03-03 20:23:00',
                'updated_at' => '2019-03-03 20:23:00',
            ],
            [
                'data' => '2.08',
                'level_id' => 2,
                'created_at' => '2019-03-03 20:23:00',
                'updated_at' => '2019-03-03 20:23:00',
            ],
        ];

        foreach ($spaces as $space) {
            DB::table('spaces')->insert($space);
        }

    }
}
