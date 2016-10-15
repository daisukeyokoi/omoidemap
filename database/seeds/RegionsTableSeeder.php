<?php

use Illuminate\Database\Seeder;

class RegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $regions = [
            '北海道・東北',
            '甲信越・北陸',
            '関東',
            '東海',
            '関西',
            '中国・四国',
            '九州・沖縄'
        ];
        for ($i = 0; $i < count($regions); $i++) {
            DB::table('regions')->insert([
                'name' => $regions[$i]
            ]);
        }
    }
}
