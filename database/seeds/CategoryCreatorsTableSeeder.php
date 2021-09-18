<?php

use Illuminate\Database\Seeder;

class CategoryCreatorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('category_creator')->insert([
            [
                'creator_id' => 1,
                'category_id' => 1,
                'created_at' => date('Y-m-d H:m:s'),
                'updated_at' => date('Y-m-d H:m:s'),
            ],
            [
                'creator_id' => 1,
                'category_id' => 2,
                'created_at' => date('Y-m-d H:m:s'),
                'updated_at' => date('Y-m-d H:m:s'),
            ],
            [
                'creator_id' => 1,
                'category_id' => 3,
                'created_at' => date('Y-m-d H:m:s'),
                'updated_at' => date('Y-m-d H:m:s'),
            ]
        ]);
    }
}
