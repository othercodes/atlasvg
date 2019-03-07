<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        $categories = [
            new \AtlasVG\Models\Category([
                'name' => 'Uncategorized',
                'color' => '#e8e8e8',
            ]),
            new \AtlasVG\Models\Category([
                'name' => 'Food',
                'color' => '#dc4b7c',
            ]),
            new \AtlasVG\Models\Category([
                'name' => 'Tech',
                'color' => '#6584c7',
            ])
        ];

        foreach ($categories as $category) {
            $category->save();
        }
    }
}
