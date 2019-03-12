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
        foreach (\AtlasVG\Models\Building::all() as $building) {
            foreach (glob(__DIR__ . '/../../resources/maps/b' . $building->id . '.l*.sample.svg') as $index => $map) {

                $level = new \AtlasVG\Models\Level([
                    'name' => 'L' . ($index + 1),
                    'level' => $index + 1,
                    'description' => 'Floor ' . ($index + 1),
                    'svg' => $map,
                ]);

                $level->building()->associate($building);
                $level->save();
            }
        }
    }
}
