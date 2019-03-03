<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        $this->call('CategoriesTableSeeder');
        $this->call('BuildingsTableSeeder');
        $this->call('LevelsTableSeeder');
        $this->call('PointersTableSeeder');
    }
}
