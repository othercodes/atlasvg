<?php

use Illuminate\Database\Seeder;

class LevelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        $resources = __DIR__ . '/../../resources/maps';

        $l1 = trim(file_get_contents($resources . '/sample1.svg'));
        \Illuminate\Support\Facades\DB::table('levels')->insert([
            'id' => 1,
            'name' => 'L1',
            'description' => 'First floor.',
            'sign' => md5($l1),
            'svg' => $l1,
            'building_id' => 1,
            'created_at' => '2019-03-03 20:23:00',
            'updated_at' => '2019-03-03 20:23:00',
        ]);

        $l2 = trim(file_get_contents($resources . '/sample2.svg'));
        \Illuminate\Support\Facades\DB::table('levels')->insert([
            'id' => 2,
            'name' => 'L2',
            'description' => 'Second floor.',
            'sign' => md5($l2),
            'svg' => $l2,
            'building_id' => 1,
            'created_at' => '2019-03-03 20:23:00',
            'updated_at' => '2019-03-03 20:23:00',
        ]);
    }
}
