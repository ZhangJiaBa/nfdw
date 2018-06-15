<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user =new \App\User();
        $user->username ='张三';
        $user->password = bcrypt('123456');
        $user->save();
    }
}
