<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'id' => 1,
                'name' => 'Uncategorized',
                'color' => '#e8e8e8',
                'created_at' => '2019-03-03 20:23:00',
                'updated_at' => '2019-03-03 20:23:00',
            ],
            [
                'id' => 2,
                'name' => 'Food',
                'color' => '#dc4b7c',
                'created_at' => '2019-03-03 20:23:00',
                'updated_at' => '2019-03-03 20:23:00',
            ],
            [
                'id' => 3,
                'name' => 'Tech',
                'color' => '#6584c7',
                'created_at' => '2019-03-03 20:23:00',
                'updated_at' => '2019-03-03 20:23:00',
            ]
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert($category);
        }
    }
}
