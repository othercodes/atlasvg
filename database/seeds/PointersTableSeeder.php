<?php

use Illuminate\Database\Seeder;

class PointersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('pointers')->insert([
            'id' => 1,
            'name' => 'Pin for Apple Heart',
            'meta' => 'Opening Hours: 6:30AM — 11:30PM Phone: (0) 66 5738902',
            'description' => 'Burdock celery - salsify, tomatillo. Bitter gourd horseradish'
                . 'leaves radicchio, celeriac miner\'s lettuce kurrat arugula fluted pumpkin'
                . 'turnip, arracacha water spinach nopal.',
            'top' => 60,
            'left' => 8,
            'level_id' => 1,
            'category_id' => 1,
            'created_at' => '2019-03-03 20:23:00',
            'updated_at' => '2019-03-03 20:23:00',
        ]);
    }
}
