<?php

use AtlasVG\Models\Level;
use Illuminate\Database\Seeder;

class SpacesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        foreach (Level::all() as $level) {
            $level->discover();
        }
    }
}
