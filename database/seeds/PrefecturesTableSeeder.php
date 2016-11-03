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
            '青森県' => 1,
            '岩手県' => 1,
            '宮城県' => 1,
            '秋田県' => 1,
            '山形県' => 1,
            '福島県' => 1,
            '山梨県' => 2,
            '長野県' => 2,
            '新潟県' => 2,
            '富山県' => 2,
            '石川県' => 2,
            '福井県' => 2,
            '東京都' => 3,
            '神奈川県' => 3,
            '埼玉県' => 3,
            '千葉県' => 3,
            '茨城県' => 3,
            '栃木県' => 3,
            '群馬県' => 3,
            '愛知県' => 4,
            '静岡県' => 4,
            '岐阜県' => 4,
            '三重県' => 4,
            '大阪府' => 5,
            '兵庫県' => 5,
            '京都府' => 5,
            '滋賀県' => 5,
            '奈良県' => 5,
            '和歌山県' => 5,
            '岡山県' => 6,
            '広島県' => 6,
            '鳥取県' => 6,
            '島根県' => 6,
            '山口県' => 6,
            '徳島県' => 6,
            '香川県' => 6,
            '愛媛県' => 6,
            '高知県' => 6,
            '福岡県' => 7,
            '佐賀県' => 7,
            '長崎県' => 7,
            '熊本県' => 7,
            '大分県' => 7,
            '宮崎県' => 7,
            '鹿児島県' => 7,
            '沖縄県' => 7
        ];
        foreach ($prefectures as $key => $value) {
            DB::table('prefectures')->insert([
                'region_id' => $value,
                'name' => $key
            ]);
        }
    }
}
