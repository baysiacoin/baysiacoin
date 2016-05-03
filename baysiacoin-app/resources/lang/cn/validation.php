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

	"accepted"             => ":attribute必须被接受。",
	"active_url"           => ":attribute不是有效网址。",
	"after"                => ":attribute必须成为:date后的日期。",
	"alpha"                => ":attribute可以包含字母。",
	"alpha_dash"           => ":attribute可以包含字母、数字和破折号。",
	"alpha_num"            => ":attribute可以包含字母和数字。",
	"array"                => ":attribute必须是一个组类型。",
	"before"               => ":attribute必须成为:date后的日期。",
	"between"              => [
		"numeric" => ":attribute必须在:min和:max之间的。",
		"file"    => ":attribute必须在:min和:max之间的千字节。",
		"string"  => ":attribute必须在:min和:max之间的字符串.",
		"array"   => ":attribute必须在:min和:max之间的字数。",
	],
	"boolean"              => ":attribute字段必须是正确或假。",
	"confirmed"            => ":attribute确认不配。",
	"date"                 => ":attribute不是有效的日期。",
	"date_format"          => ":attribute跟:format不配。",
	"different"            => ":attribute和:other必须不同。",
	"digits"               => ":attribute必须是:digits数字。",
	"digits_between"       => ":attribute必须在:min和:max之间数字。",
	"email"                => ":attribute必须成为有效的电子邮件地址。",
	"filled"               => ":attribute字段是必须的。",
	"exists"               => "选中的:attribute是无效。",
	"image"                => ":attribute必须是图片。",
	"in"                   => "选中的:attribute是无效。",
	"integer"              => ":attribute必须是整数。",
	"ip"                   => ":attribute必须是有效的IP地址。",
	"max"                  => [
		"numeric" => ":attribute可能不大于:max。",
		"file"    => ":attribute可能不大于:max千字节。",
		"string"  => ":attribute可能不大于:max字符串。",
		"array"   => ":attribute可能没有超过:max字数。",
	],
	"mimes"                => ":attribute必须是:values文字类型。",
	"min"                  => [
		"numeric" => ":attribute必须是至少:min。",
		"file"    => ":attribute必须是至少:min千字节。",
		"string"  => ":attribute必须是至少:min字符串。",
		"array"   => ":attribute必须是至少:min字数。",
	],
	"not_in"               => "选中的:attribute是无效。",
	"numeric"              => ":attribute必须数字。",
	"regex"                => ":attribute格式无效。",
	"required"             => ":attribute字段是必须的。",
	"required_if"          => ":attribute字段是在:other是:value的情况下必须的。",
	"required_with"        => ":attribute字段是在:values存在的情况下必须的。",
	"required_with_all"    => ":attribute字段是在:values存在的情况下必须的。",
	"required_without"     => ":attribute字段是在:values存在的情况下必须的。",
	"required_without_all" => ":attribute字段是在:values都不存在的情况下必须的。",
	"same"                 => ":attribute和:other必须配。",
	"size"                 => [
		"numeric" => ":attribute必须是:size。",
		"file"    => ":attribute必须是:size千字节。",
		"string"  => ":attribute必须是:size字符串。",
		"array"   => ":attribute必须包含:size字数。",
	],
	"unique"               => ":attribute已被取。",
	"url"                  => ":attribute格式无效。",
	"timezone"             => ":attribute必须是有效领域。",

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
		'email' => '邮件地址',
		'password' => '密码',
		'firstname' => '姓',
		'lastname' => '名字',
		'firstname1' => 'Hurigana名字',
		'lastname1' => 'Hurigana姓',
		'birthday' => '生日',
		'telnum' => '电话号码',
		'zipcode' => '邮政编码',
		'country' => '国籍',
		'state' => '国家',
		'city' => '城市',
		'address' => '地址',
		'bankname' => '银行名称',
		'branchname' => '分支名称',
		'accounttype' => '账户类型',
		'accountnumber' => '账户号码',
		'accountname' => '账户名称',
		'kiyaku' => '许可证协议',
		'old_password' => '旧密码',
		'new_password' => '新密码',
	        'balance' => '余额',
	        'referral' => '介绍',
	        'avatar' => '身份证明前',
	        'avatar2' => '身份证明后',
	        'applicant_name' => '申请人名称',
	        'fund_amount' => '基金数量',
	        'withdraw_amount' => '取款量',
		'transfer_amount' => '送金金額',
		'qr_token' => 'QR code',
		'sms_token' => 'SMS code',
		'tag' => 'Tag Number',
		'ext_addr' => 'External Address',
		'new_curr' => 'Currency Code',
		'new_gateway' => 'Gateway Name',
		'name' => 'Name',
		'subject' => 'Subject',
		'comment' => 'Message',
	],
];
