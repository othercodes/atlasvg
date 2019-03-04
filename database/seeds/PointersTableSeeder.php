<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PointersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        $pointers = [
            [
                'id' => 1,
                'name' => 'Pin for Apple Heart',
                'meta' => 'Opening Hours: 6:30AM — 11:30PM Phone: (0) 66 5738902',
                'description' => 'Burdock celery - salsify, tomatillo. Bitter gourd horseradish'
                    . 'leaves radicchio, celeriac miner\'s lettuce kurrat arugula fluted pumpkin'
                    . 'turnip, arracacha water spinach nopal.',
                'top' => 60,
                'left' => 8,
                'space_id' => 1,
                'category_id' => 1,
                'created_at' => '2019-03-03 20:23:00',
                'updated_at' => '2019-03-03 20:23:00',
            ],
            [
                'id' => 2,
                'name' => 'Which Bug?',
                'meta' => 'Opening Hours: 7:30AM — 10:30PM Phone: (0) 66 8865001',
                'description' => 'Sorrel garlic pigeon pea fava bean radish scorzonera lentil. Black-eyed '
                    . 'pea samphire sorrel; lotus root arracacha lima bean celeriac chinese artichoke okra.',
                'top' => 21,
                'left' => 84,
                'space_id' => 10,
                'category_id' => 3,
                'created_at' => '2019-03-03 20:23:00',
                'updated_at' => '2019-03-03 20:23:00',
            ]
        ];

        foreach ($pointers as $pointer) {
            DB::table('pointers')->insert($pointer);
        }
    }
}
