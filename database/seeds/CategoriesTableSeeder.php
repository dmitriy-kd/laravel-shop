<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            ['name' => 'NewCategory', 'code' => 'newcategory', 'description' => 'NewCategory'],
            ['name' => 'NewCategory1', 'code' => 'newcategory1', 'description' => 'NewCategory1'],
            ['name' => 'NewCategory2', 'code' => 'newcategory2', 'description' => 'NewCategory2']
        ]);
    }
}
