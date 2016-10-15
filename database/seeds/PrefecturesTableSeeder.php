<?php

use Illuminate\Database\Seeder;

class PrefecturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prefectures = [
            '北海道' => 1,
            '青森' => 1,
            '岩手' => 1,
            '宮城' => 1,
            '秋田' => 1,
            '山形' => 1,
            '福島' => 1,
            '山梨' => 2,
            '長野' => 2,
            '新潟' => 2,
            '富山' => 2,
            '石川' => 2,
            '福井' => 2,
            '東京' => 3,
            '神奈川' => 3,
            '埼玉' => 3,
            '千葉' => 3,
            '茨城' => 3,
            '栃木' => 3,
            '群馬' => 3,
            '愛知' => 4,
            '静岡' => 4,
            '岐阜' => 4,
            '三重' => 4,
            '大阪' => 5,
            '兵庫' => 5,
            '京都' => 5,
            '滋賀' => 5,
            '奈良' => 5,
            '和歌山' => 5,
            '岡山' => 6,
            '広島' => 6,
            '鳥取' => 6,
            '島根' => 6,
            '山口' => 6,
            '徳島' => 6,
            '香川' => 6,
            '愛媛' => 6,
            '高知' => 6,
            '福岡' => 7,
            '佐賀' => 7,
            '長崎' => 7,
            '熊本' => 7,
            '大分' => 7,
            '宮崎' => 7,
            '鹿児島' => 7,
            '沖縄' => 7
        ];
        foreach ($prefectures as $key => $value) {
            DB::table('prefectures')->insert([
                'region_id' => $value,
                'name' => $key
            ]);
        }
    }
}
