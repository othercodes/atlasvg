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
        DB::table('categories')->insert([
            'id' => 1,
            'name' => 'Uncategorized',
            'color' => '#9a9a9a',
            'created_at' => '2019-03-03 20:23:00',
            'updated_at' => '2019-03-03 20:23:00',
        ]);

        DB::table('categories')->insert([
            'id' => 2,
            'name' => 'Food',
            'color' => '#7cbf7f',
            'created_at' => '2019-03-03 20:23:00',
            'updated_at' => '2019-03-03 20:23:00',
        ]);
    }
}
