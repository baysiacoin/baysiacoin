<?php

return [
	'title' => 'Baysiacoin-Admin',
	 
	'message' => [
		'footer' => 'Copyright © 2014 baysiacoin.asia All Rights Reserved. 営業時間:9:00 ~ 17:00',
	],
	
	'user' => [
		'title' => 'ユーザー管理',
		'manage' => '管理',
		'menu_user' => 'ユーザー',
		'confirm_delete' => '本当に削除しますか？',
		'message' => [
			'name' => '名前',
			'username' => 'ユーザ名前',
			'email' => 'メールアドレス',
            'fb_register' => 'Facebookから',
            'fb_register_yes' => 'はい',
            'fb_register_no' => 'いいえ',
			'role' => '役割',
			'identity_confirm' => '身分証明書確認',
			'mail_confirm' => 'メール確認',
			'action' => 'アクション',
			'confirmed' => '確認済み',
			'unconfirmed' => '未確認',
			'verified' => '確認済み',
			'notverified' => 'メールが確認されていません。',
			'unverified' => '未確認',
			'branch1' => '一次代理店',
			'branch2' => '二次代理店',
			'bonus' => 'ボーナス',
			'baysiacoin_address' => 'Baysiacoin アドレス',
			'baysiacoin_balance' => 'Baysiacoin 残高',
			'baysiacoin_balance_jpy' => 'BaysiaJapan 残高',
			'temp_balance' => '仮想残高'
		],
	],
	
	'edit' => [
		'title' => 'ユーザー編集',
		'profile' => [
			'title' => 'プロフィール設定',
			'sub_title' => 'プロフィールの編集',
			'necessary' => '出金依頼をするには下記の情報をご入力ください。',
			'identity' => '身分証明書',
			'company' => '会社名',
			'firstname' => '姓',
			'lastname' => '名',
			'firstname1' => 'せい',
			'lastname1' => 'めい',
			'email' => 'メールアドレス',
			'password' => 'パスワード',
			'telnum' => '電話番号',
			'zipcode' => '郵便番号',
			'country' => '国籍',
			'state' => '都道府県',
			'city' => '市区町村名',
			'address' => '番地',
			'buildingname' => '建物名',
			'referral' => 'Referral',
			'mail_confirm'	=> "メール確認",
			'identity_send' => '身分証明書発送',
			'identity_send_desc' => '身分証明書を郵送かFAXで発送',
			'identity_confirm'	=> "身分証明書確認",
			'identity_identification'	=> "身分証明書",
			'identity_select_image'	=> "画像を選択",
			'identity_change'	=> "変更",
			'identity_delete'	=> "削除",
			'identity_front' => "表面",
			'identity_end' => "裏面",
			'birthday' => '生年月日',
			'role' => '権限',
			'selectfile' => 'ファイル選択',
			'delete_pictures' => '画像の削除',
		],
		'bank' => [
			'title' => '銀行情報',
			'sub_title' => '銀行情報設定',
			'bankname' => '銀行名',
			'branchname' => '支店名',
			'accounttype' => '口座種別',
			'accountnumber' => '口座番号',
			'accountname' => '口座名義人',
		],
		'wallet' => [
			'title' => 'ウォレット',
			'gateway' => 'ゲートウェイ',
			'sub_title' => 'ウォレット',
			'username' => 'ユーザー名',
			'password' => 'パスワード',
			'address' => 'アドレス',
			'secret' => 'キー',
		],
		'balance' => [
			'title' => '残高',
			'sub_title' => '残高設定',
			'necessary' => '残高を設定したい場合は下記から変更してください。',
			'balance' => '残高',
		],
	],
    'history' => [
        'fund' => [
            'title' => '入金履歴',
			'pendding' => 'ペンディング',
			'completed' => '完成',
			'failed' => '失敗',
			'confirmed' => '支払済み',
			'transaction_id' => '取引ID',
			'process' => '処理',
			'update' => '状態更新',
			'send_update' => '送金＆状態更新',
			'ask_confirm' => '本当に送金しますか?',
			'edit' => '編集',
			'save' => '保管',
			'success' => '正常に更新されました。',
			'fail' => '更新が失敗しました。'
        ],
        'withdraw' => [
            'title' => '出金履歴',
			'confirmed' => '払い戻し',
			'requested' => '出金申請済み',
			'failed' => '出金申請失敗',
			'fee_amount' => '手数料',
			'receive_amount' => '金額を受け取る',
			'completed' => '出金成功',
			'' => '',
        ],
		'coins' => [
			'title' => 'コイン履歴',
			'market' => 'マーケット',
			'type' => 'タイプ',
			'fund' => '入金',
			'withdraw' => '出金',
			'address' => 'アドレス',
			'tag' => 'タグ番号',
			'currency' => '通貨',
			'amount' => '金額',
		],
		'tx' => [
			'title' => '取引の履歴',
			'no' => 'No',
			'datetime' => '時間',
			'sell' => '売る&nbsp;&nbsp;(&nbsp;金額&nbsp;|&nbsp;通貨&nbsp;|&nbsp;発行者アドレス&nbsp;)',
			'buy' => '買う&nbsp;&nbsp;(&nbsp;金額&nbsp;|&nbsp;通貨&nbsp;|&nbsp;発行者アドレス&nbsp;)',
			'address' => 'アドレス'
		],
		'all' => '--すべて--',
		'status' => 'ステータス',
		'username' => 'ユーザー名',
		'search' => '検索',
		'clear' => 'クリア',
		'coin' => 'コイン',
		'amount' => '額',
		'currency' => '通貨',
		'issuer_address' => '発行者アドレス',
		'tag' => 'タグ番号',
		'external_address' => 'エキスターナルアドレス',
		'created_at' => '日付',
    ],
	'offers' => [
		'title' => '待機オーダー',
	],
];