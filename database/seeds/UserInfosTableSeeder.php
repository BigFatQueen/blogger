<?php

use Illuminate\Database\Seeder;
use App\UserInfo;

class UserInfosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserInfo::create([
            'user_id' => 2,
            'dob' => '1997-01-20',
            'created_at' => date('Y-m-d H:m:s'),
            'updated_at' => date('Y-m-d H:m:s'),
        ]);
        UserInfo::create([
            'user_id' => 3,
            'dob' => '1997-01-20',
            'created_at' => date('Y-m-d H:m:s'),
            'updated_at' => date('Y-m-d H:m:s'),
        ]);
    }
}
