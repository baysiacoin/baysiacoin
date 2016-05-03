<?php

return [
	'title' => 'Baysiacoin-Admin',
	 
	'message' => [
		'footer' => 'Copyright © 2014 baysiacoin.asia All Rights Reserved. Operating Hours:9:00 ~ 17:00',
	],
	
	'user' => [
		'title' => 'Manage User',
		'manage' => 'Manage',
		'menu_user' => '&nbsp;User',
		'confirm_delete' => 'Delete really？',
		'message' => [
			'name' => 'Name',
			'username' => 'Username',
			'email' => 'E-mail',
            'fb_register' => 'from FB',
            'fb_register_yes' => 'Yes',
            'fb_register_no' => 'No',
			'role' => 'Role',
			'identity_confirm' => 'Identification Status',
			'mail_confirm' => 'Mail Verification',
			'action' => 'Action',
			'confirmed' => 'Confirmed',
			'unconfirmed' => 'Unconfirmed',
			'verified' => 'Verified.',
			'notverified' => 'The e-mail is not verified.',
			'unverified' => 'Not Authenticated.',
			'branch1' => 'First Branch',
			'branch2' => 'Second Branch',
			'bonus' => 'Bonus',
			'baysiacoin_address' => 'Baysiacoin Address',
			'baysiacoin_balance' => 'Baysiacoin Balance',
			'baysiacoin_balance_jpy' => 'BaysiaJapan Balance',
			'temp_balance' => 'Vitual Balance'
		],
	],
	
	'edit' => [
		'title' => 'Edit User',
		'profile' => [
			'title' => 'Profile Settings',
			'sub_title' => 'Edit Profile',
			'necessary' => 'Please input information to change.',
			'identity' => 'Identification card',
			'company' => 'Comapny Name',
			'firstname' => 'First Name',
			'lastname' => 'Last Name',			
			'email' => 'E-mail',
			'password' => 'Password',
			'telnum' => 'Tel Number',
			'country' => 'Nationality',
			'zipcode' => 'Zip Code',
			'state' => 'State',
			'city' => 'City',
			'address' => 'Address',
			'buildingname' => 'Apartment',
			'referral' => 'Referral',
			'mail_confirm'	=> "Confirm Email",
			'identity_send' => 'Send identification card',
			'identity_send_desc' => 'Send identification card via mail or fax',
			'identity_confirm'	=> "Confirm Identification Card",
			'identity_identification'	=> "Identification Card",
			'identity_select_image'	=> "Select Pictures",
			'identity_change'	=> "Edit",
			'identity_delete'	=> "Delete",
			'identity_front' => "Front",
			'identity_end' => "Back",
			'birthday' => 'Birthday',
			'role' => 'Role',
			'selectfile' => 'FileSelect',
			'delete_pictures' => 'Delete',
		],
		'bank' => [
			'title' => 'Bank Information',
			'sub_title' => 'Edit Bank\'s Profile',
			'bankname' => 'Bank Name',
			'branchname' => 'Branch Name',
			'accounttype' => 'Account Type',
			'accountnumber' => 'Account Number',
			'accountname' => 'Person Registered ',
		],
		'wallet' => [
			'title' => 'Wallet',
			'gateway' => 'Gateway',
			'sub_title' => 'Wallet',
			'username' => 'Username',
			'password' => 'Password',
			'address' => 'Address',
			'secret' => 'Key',
		],
		'balance' => [
			'title' => 'Balance',
			'sub_title' => 'Edit Balance',
			'necessary' => 'Please edit below to change balance.',
			'balance' => 'Balance',
		],
	],
    'history' => [
        'fund' => [
            'title' => 'Deposit History',
			'pendding' => 'Suspend',
			'completed' => 'Completed',
			'failed' => 'Fail',
			'confirmed' => 'Confirmed',
			'transaction_id' => 'TX_ID',
			'process' => 'Process',
			'update' => 'Update',
			'send_update' => 'Send & Update',
			'ask_confirm' => 'Do you really want to send?',
			'edit' => 'Edit',
			'save' => 'Save',
			'success' => 'Successfully changed.',
			'fail' => 'Failed to change.'
        ],
        'withdraw' => [
            'title' => 'Withdraw History',
			'confirmed' => 'Confirmed',
			'requested' => 'Requested',
			'failed' => 'Failed',
			'fee_amount' => 'Fee Amount',
			'receive_amount' => 'Received Amount',
			'completed' => 'Completed',
			'' => '',
        ],
		'coins' => [
			'title' => 'Coin History',
			'market' => 'Market',
			'type' => 'Type',
			'fund' => 'Fund',
			'withdraw' => 'Withdraw',
			'address' => 'Address',
			'tag' => 'Tag',
			'currency' => 'Currency',
			'amount' => 'Amount',
		],
		'tx' => [
			'title' => 'Trade History',
			'no' => 'No',
			'datetime' => 'Datetime',
			'sell' => 'Sell&nbsp;&nbsp;(&nbsp;Amount&nbsp;|&nbsp;Currency&nbsp;|&nbsp;Issuer&nbsp;)',
			'buy' => 'Buy&nbsp;&nbsp;(&nbsp;Amount&nbsp;|&nbsp;Currency&nbsp;|&nbsp;Issuer&nbsp;)',
			'address' => 'Address'
		],
		'all' => '--All--',
		'status' => 'Status',
		'username' => 'Username',
		'search' => 'Search',
		'clear' => 'Clear',
		'coin' => 'Coin',
		'amount' => 'Amount',
		'currency' => 'Currency',
		'issuer_address' => 'Issuer Address',
		'tag' => 'Tag',
		'external_address' => 'External Address',
		'created_at' => 'Date',
    ],
	'offers' => [
		'title' => 'Waiting Order',
	],
];