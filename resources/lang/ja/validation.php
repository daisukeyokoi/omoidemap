<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':attribute に同意してください。',
    'active_url'           => ':attribute を有効なURLで入力してください。',
    'after'                => ':attribute を正しく入力してください。', //:date
    'alpha'                => ':attribute はアルファベットで入力してください。',
    'alpha_dash'           => ':attribute はアルファベットかダッシュ(-)、下線(_)で入力してください。',
    'alpha_num'            => ':attribute はアルファベットか数字で入力してください。',
    'array'                => ':attribute は配列タイプで入力してください。',
    'before'               => ':attribute を正しく入力してください。', //:date
    'between'              => [
        'numeric' => ':attribute を正しく入力してください。', //:min :max
        'file'    => ':attribute は :min KBから :max KBの間で入力してください。',
        'string'  => ':attribute は :min 文字から :max 文字の間で入力してください。',
        'array'   => ':attribute は :min 個から :max 個のアイテムで送信してください。',
    ],
    'boolean'              => ':attribute を正しく入力してください。',
    'confirmed'            => ':attribute の再入力フォームは同じ値を入力してください。',
    'date'                 => ':attribute を日付の形で入力してください。',
    'date_format'          => 'The :attribute does not match the format :format.',
    'different'            => ':attribute の再入力フォームは違う値を入力してください。',
    'digits'               => ':attribute は :digits 桁の数値で入力してください。.',
    'digits_between'       => ':attribute を正しく入力してください。', // :min :max
    'email'                => ':attribute を正しいメールアドレスの形式で入力してください。',
    'filled'               => 'The :attribute field is required.',
    'exists'               => 'The selected :attribute is invalid.',
    'image'                => ':attribute は画像ファイルを添付してください。',
    'in'                   => 'The selected :attribute is invalid.',
    'integer'              => ':attribute を正しく入力してください。',
    'ip'                   => 'The :attribute must be a valid IP address.',
    'max'                  => [
        'numeric' => ':attribute は :max 桁以内で入力してください。',
        'file'    => ':attribute は :max KB以内で送信してください。',
        'string'  => ':attribute は :max 文字以内で入力してください。',
        'array'   => ':attribute は :max アイテム以内で入力してください。',
    ],
    'mimes'                => 'The :attribute must be a file of type: :values.',
    'min'                  => [
        'numeric' => ':attribute は :min 桁以上で入力してください。',
        'file'    => ':attribute は :min KB以上で送信してください。',
        'string'  => ':attribute は :min 文字以上で入力してください。',
        'array'   => ':attribute は :min はアイテム以上で送信してください。',
    ],
    'not_in'               => 'The selected :attribute is invalid.',
    'numeric'              => ':attribute は数字を入力してください。',
    'regex'                => ':attribute を正しく入力してください。',
    'required'             => ':attribute を入力してください。',
    'required_if'          => ':attribute を入力してください。', //:otherが値
    'required_with'        => 'The :attribute field is required when :values is present.',
    'required_with_all'    => 'The :attribute field is required when :values is present.',
    'required_without'     => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same'                 => 'The :attribute and :other must match.',
    'size'                 => [
        'numeric' => ':attribute は :size 桁で送信してください。',
        'file'    => ':attribute は :size KBで送信してください。',
        'string'  => ':attribute は :size 文字で入力してください。',
        'array'   => ':attribute は :size アイテムで送信してください。',
    ],
    'string'               => ':attribute は文字列で入力してください。',
    'timezone'             => 'The :attribute must be a valid zone.',
    'unique'               => ':attribute を正しく入力してください。',
    'url'                  => ':attribute を正しいURLで入力してください。',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

	'attributes' => [
		// 管理画面イベント
		'image_file'  => 'イメージ画像',
		'title'       => 'タイトル',
		'description' => '説明',
        'start'       => '開催期間',
        'end'         => '開催期間',
	],

];
