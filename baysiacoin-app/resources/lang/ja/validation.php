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

	"accepted"             => ":attribute に同意する必要があります。",
	"active_url"           => ":attributeをURL形式で入力してください。",
	"after"                => "The :attribute must be a date after :date.",
	"alpha"                => "The :attribute may only contain letters.",
	"alpha_dash"           => "The :attribute may only contain letters, numbers, and dashes.",
	"alpha_num"            => "The :attribute may only contain letters and numbers.",
	"array"                => "The :attribute must be an array.",
	"before"               => ":attributeが :dateより先んじなければなりません。",
	"between"              => [
		"numeric" => "The :attribute must be between :min and :max.",
		"file"    => "The :attribute must be between :min and :max kilobytes.",
		"string"  => "The :attribute must be between :min and :max characters.",
		"array"   => "The :attribute must have between :min and :max items.",
	],
	"boolean"              => "The :attribute field must be true or false.",
	"confirmed"            => ":attribute(確認用)を正確に入力してください。",
	"date"                 => "The :attribute is not a valid date.",
	"date_format"          => "The :attribute does not match the format :format.",
	"different"            => "The :attribute and :other must be different.",
	"digits"               => ":attributeは:digits文字まで入力してください。",
	"digits_between"       => "The :attribute must be between :min and :max digits.",
	"email"                => ":attributeをメールアドレス形式に合わせてください。",
	"filled"               => ":attribute field is required.",
	"exists"               => "The selected :attribute is invalid.",
	"image"                => "正確な画像ファイルを入力してください。",
	"in"                   => "The selected :attribute is invalid.",
	"integer"              => "The :attribute must be an integer.",
	"ip"                   => "The :attribute must be a valid IP address.",
	"max"                  => [
		"numeric" => ":attributeは:max.以下でなければなりません。",
		"file"    => "The :attribute may not be greater than :max kilobytes.",
		"string"  => "The :attribute may not be greater than :max characters.",
		"array"   => "The :attribute may not have more than :max items.",
	],
	"mimes"                => "The :attribute must be a file of type: :values.",
	"min"                  => [
		"numeric" => ":attribute は:minより大きくなければなりません。",
		"file"    => "The :attribute must be at least :min kilobytes.",
		"string"  => ":attributeは:min文字以上でなければなりません。",
		"array"   => "The :attribute must have at least :min items.",
	],
	"not_in"               => "The selected :attribute is invalid.",
	"numeric"              => ":attributeを数値で入力してください。",
	"regex"                => ":attributeの形式を合わせてください。",
	"required"             => ":attributeを入力してください。",
	"required_if"          => "The :attribute field is required when :other is :value.",
	"required_with"        => "The :attribute field is required when :values is present.",
	"required_with_all"    => "The :attribute field is required when :values is present.",
	"required_without"     => "The :attribute field is required when :values is not present.",
	"required_without_all" => "The :attribute field is required when none of :values are present.",
	"same"                 => "The :attribute and :other must match.",
	"size"                 => [
		"numeric" => "The :attribute must be :size.",
		"file"    => "The :attribute must be :size kilobytes.",
		"string"  => "The :attribute must be :size characters.",
		"array"   => "The :attribute must contain :size items.",
	],
	"unique"               => ":attributeはすでに存在しています。",
	"url"                  => ":attributeを正確に入力してください.",
	"timezone"             => "The :attribute must be a valid zone.",

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
		'email' => 'メールアドレス',
		'password' => 'パスワード',
		'firstname' => 'お名前(姓)',
		'lastname' => 'お名前(名)',
		'firstname1' => 'ふりがな(姓)',
		'lastname1' => 'ふりがな(名)',
		'birthday' => '生年月日',
		'telnum' => '電話番号',
		'zipcode' => '郵便番号',
		'country' => '国籍',
		'state' => '都道府県',
		'city' => '市区町村',
		'address' => '番地',
		'bankname' => '銀行名',
		'branchname' => '支店名',
		'accounttype' => '口座種別',
		'accountnumber' => '口座番号',
		'accountname' => '口座名義人',
		'kiyaku' => '利用規約',
		'old_password' => '現在のパスワード',
		'new_password' => '新しいパスワード',
		'balance' => '残高',
		'referral' => 'Referral',
		'avatar' => '身分証明書の表面',
		'avatar2' => '身分証明書の裏面',
        'applicant_name' => '依頼人名',
        'fund_amount' => '入金金額',
        'withdraw_amount' => '出金金額',
		'transfer_amount' => '送金金額',
		'qr_token' => 'QRコード',
		'sms_token' => 'SMSコード',
		'tag' => 'タグ番号',
		'ext_addr' => 'エキスターナルアドレス',
        'new_curr' => '通貨コード',
        'new_gateway' => 'ゲートウェイの名前',
        'name' => '本人の名前',
        'subject' => 'タイトル',
        'comment' => 'メッセージ',
	],
];
