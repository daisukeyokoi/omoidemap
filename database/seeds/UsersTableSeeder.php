<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('users')->insert([
          'nickname' => 'aaa',
          'email' => 'a@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
					"confirmed_at" => Carbon::now(),
      ]);
    }
}
