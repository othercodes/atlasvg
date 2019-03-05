<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BuildingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {

        $resources = __DIR__ . '/../../resources/maps';
        $surrounding = trim(file_get_contents($resources . '/surroundings.svg'));

        DB::table('buildings')->insert([
            'id' => 1,
            'name' => 'Sample Mall',
            'description' => 'Some fake mall.',
            'surroundings' => $surrounding,
            'created_at' => '2019-03-03 20:23:00',
            'updated_at' => '2019-03-03 20:23:00',
        ]);
    }
}
