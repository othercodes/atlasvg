<?php

use Illuminate\Database\Seeder;

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

        $building = new \AtlasVG\Models\Building([
            'name' => 'Sample Mall',
            'description' => 'Some fake mall.',
            'svg' => $surrounding,
        ]);
        $building->save();
    }
}
