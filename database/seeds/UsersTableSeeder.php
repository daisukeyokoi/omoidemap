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
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'bbb',
          'email' => 'b@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'ccc',
          'email' => 'c@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'ddd',
          'email' => 'd@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'MIAS',
          'email' => 'kanri@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 1,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'e',
          'email' => 'e@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'f',
          'email' => 'f@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'g',
          'email' => 'g@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'h',
          'email' => 'h@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'i',
          'email' => 'i@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'j',
          'email' => 'j@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'k',
          'email' => 'k@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'l',
          'email' => 'l@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'm',
          'email' => 'm@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'n',
          'email' => 'n@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'o',
          'email' => 'o@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'p',
          'email' => 'p@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'q',
          'email' => 'q@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'r',
          'email' => 'r@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 's',
          'email' => 's@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 't',
          'email' => 't@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'u',
          'email' => 'u@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'v',
          'email' => 'v@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'w',
          'email' => 'w@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'x',
          'email' => 'x@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'y',
          'email' => 'y@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'z',
          'email' => 'z@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'aa',
          'email' => 'aa@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'bb',
          'email' => 'bb@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'cc',
          'email' => 'cc@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'dd',
          'email' => 'dd@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'ee',
          'email' => 'ee@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'ff',
          'email' => 'ff@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'gg',
          'email' => 'gg@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'hh',
          'email' => 'hh@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'ii',
          'email' => 'ii@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'jj',
          'email' => 'jj@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'kk',
          'email' => 'kk@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'll',
          'email' => 'll@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'mm',
          'email' => 'mm@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'nn',
          'email' => 'nn@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'oo',
          'email' => 'oo@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'pp',
          'email' => 'pp@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'qq',
          'email' => 'qq@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'rr',
          'email' => 'rr@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'ss',
          'email' => 'ss@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'tt',
          'email' => 'tt@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'uu',
          'email' => 'uu@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'vv',
          'email' => 'vv@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'ww',
          'email' => 'ww@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'xx',
          'email' => 'xx@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'yy',
          'email' => 'yy@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

      DB::table('users')->insert([
          'nickname' => 'zz',
          'email' => 'zz@gmail.com',
          'password' => bcrypt('aaaaaa'),
          "confirmation_token" => "",
          'identification' => 0,
          "confirmed_at" => Carbon::now(),
      ]);

    }
}
