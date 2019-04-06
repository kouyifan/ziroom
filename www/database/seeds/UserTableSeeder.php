<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(\App\User::class,50)->create();
        $user = new \App\User();
        $user->name = 'kouyifan';
        $user->email = '26745709@qq.com';
        $user->email_verified_at = now();
        $user->password = bcrypt('admin888');
        $user->remember_token = Str::random(10);
        $user->save();
    }
}
