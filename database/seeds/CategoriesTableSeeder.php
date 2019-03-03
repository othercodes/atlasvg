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
        \Illuminate\Support\Facades\DB::table('categories')->insert([
            'name' => 'Uncategorized',
            'created_at' => '2019-03-03 20:23:00',
            'updated_at' => '2019-03-03 20:23:00',
        ]);
    }
}
