<?php

use AtlasVG\Models\Category;
use AtlasVG\Models\Level;
use AtlasVG\Models\Space;
use Illuminate\Database\Seeder;

class PointersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        foreach (Level::all() as $level) {

            /** @var Category $category */
            $category = Category::inRandomOrder()->first();

            /** @var Space $space */
            $space = $level->spaces()->inRandomOrder()->first();
            $center = $level->calculateRelativeSpaceCenter($space);

            $pointer = new \AtlasVG\Models\Pointer([
                'name' => 'Lorem ipsum dolor',
                'meta' => 'Aliquam euismod leo justo, sit amet cursus justo aliquam et.',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam rhoncus '
                    . 'vestibulum mauris, et elementum mi commodo ut. Class aptent taciti sociosqu ad litora '
                    . 'torquent per conubia nostra, per inceptos himenaeos. Mauris vestibulum tortor vel facilisis.',
                'top' => $center['y'],
                'left' => $center['x'],
            ]);

            $pointer->space()->associate($space);
            $pointer->category()->associate($category);
            $pointer->save();
        }
    }
}
