<?php

return [
    /**
     * システム運営者のRipple情報
     */
    'ripple' => array(
        //'address' => "rPasfsfsfasf", // m-umezawa
        //'secret'=> "rPasfsfsfasf",
        'address' => "rPasfsfsfasf", // el-live
        'secret'=> "rPasfsfsfasf",
        'activate_amount' => 50,                           // 50 XRP
        'activate_currency' => 'XRP',
    ),

    // システム運営者の口座情報
    'bank' => [
        'bank_name' => "三菱東京UFJ銀行",
        'branch_name' => "上野支店",
        'holder_name' => "ｶ)ｴﾀｰﾅﾙﾘﾝｸ",
        'account_kind' => "普通口座",
        "account_no" => "0043432",
    ],
    
    // システム運営者のPAYPAL口座情報
    'paypal' => 'paypal@baysiacoin.jp',
    
    // サーバ情報
    'server' => array(
        'socket' => 'https://baysiacoin:8938',
    ),
    
    
    // 身分証明書ファイルのExtension
    'cert_file_kinds' => array('jpg', 'jpeg', 'bmp', 'png', 'gif', 'JPG', 'JPEG', 'BMP', 'PNG', 'GIF', 'pdf', 'PDF'),
    
    // 運営会社情報
    'contact_post' => '〒103-0025',
    'contact_address' => '東京都中央区日本橋茅場町2丁目7-6/6F',
    'contact_phone' => '0120-02-3252',
    'contact_companyphone' => '03-5645-3252',
    'contact_fax' => '03-5645-3253',
    'contact_email' => 'info@baysiacoin.jp',
    'contact_web' => 'https://www.baysiacoin.jp',
    'contact_workhour' => '9：00～17：00',
		
	// no csrf on post
	'no_csrf' => [
		'manage/root/stellar/dogenerate',
	],
    //basic currencies
    'base_currencies' => [],
    //basic gateway
    'base_gateway' => 'Baysiacoin.asia',
	// wallet
	'stellar' => [
        'issuer_wallet_address' => '#################################',
		'issuer_wallet_secret' => '#################################',
		'activate_currency' => 'BSC',
		'activate_amount' => 0.0151,
	],
    'authy_api_key' => '#################################',

    // languages application using
    'language' => [
        'ja' => 'Japan',
        'en' => ['United States', 'England', 'India', 'HongKong'],
        'cn' => 'China',
    ],
	'admin_email' => 'info@japan.baysiacoin.com',
];