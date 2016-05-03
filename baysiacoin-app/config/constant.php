<?php
// MySQL
define('MYSQL_DATE', 'Y-m-d H:i:s');

//ROOT PATH
define('ROOT_PATH' , dirname(dirname(dirname(__FILE__))));
define('IDENTITY_UPLOAD_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR . 'identity' . DIRECTORY_SEPARATOR);

// Role
define('ROLE_ADMIN', 'Admin');
define('ROLE_ADMIN2', 'Admin2');
define('ROLE_AFFILIATE', 'Affiliate');
define('ROLE_SUPER', 'Super');
define('ROLE_AFFILIATEADMIN', 'AffiliateAdmin');
define('ROLE_USER', 'User');
define('ROLE_BRANCH1', 'Branch1');
define('ROLE_BRANCH2', 'Branch2');
define('ROLE_GUEST', 'Guest');

// User
define('USER_LICENSE_CHECKED', 1);
define('USER_LICENSE_UNCHECKED', 0);
define('USER_VERIFIED', 1);

define('USER_REGISTER_FROM_FB', 1);
define('USER_REGISTER_FROM_NORMAL', 0);

// Page
define('X_100__PER_PAGE', 100);
define('X_15__PER_PAGE', 15);

// Rule
//define('REGEX_TELNUM', '/^\d{3}-\d{4}-\d{4}|\d{2}-\d{4}-\d{4}|\d{4}-\d{2}-\d{4}|\d{3}-\d{3}-\d{4}|\d{5}-\d{1}-\d{4}$/');
define('REGEX_TELNUM', '/^[0-9][0-9-]*[0-9]$/');
// define('REGEX_TELNUM', '/^[0-9\xFF01-\xFF5E][0-9\xFF01-\xFF5E-]*[0-9\xFF01-\xFF5E]$/'); // fullwidth check - zen-kaku
//define('REGEX_ZIPCODE', '/^\d{3}-\d{4}$/');
define('REGEX_ZIPCODE', '/^[0-9][0-9]*-?[0-9]*[0-9]$/');
define('REGEX_ACCOUNTNUMBER', '/^[0-9]+$/');

// Wallet Trust
define('WALLET_ACTIVE', 1);
define('WALLET_NOACTIVE', 0);
define('TRUST_AMOUNT', 0.015);

// Coin
define('COIN_STR', 'STR');
define('COIN_BSC', 'BSC');                      
define('COIN_JPY', 'JPY');                      // by FireDragon

// Wallet Id
define('WALLET_JPY', 1);
define('WALLET_STR', 2);
define('WALLET_BSC', 3);

// Withdraw Currency
define('WITHDRAW_CURRENCY', 'JPY');             // by FireDragon

// Withdraw
define('WITHDRAW_REQUESTED', 1);                // by FireDragon
define('WITHDRAW_CONFIRMED', 2);                // by FireDragon
define('WITHDRAW_FAILED', 11);                  // by FireDragon

// Deposit
define('DEPOSIT_REQUESTED', 1);                 // by FireDragon
define('DEPOSIT_CONFIRMED', 2);                 // by FireDragon
define('DEPOSIT_FINISHED', 3);                  // by FireDragon
define('DEPOSIT_FAILED', 11);                   // by FireDragon

// Transfer
define('TRANSFER_REQUESTED', 1);                 // by FireDragon
define('TRANSFER_CONFIRMED', 2);                 // by FireDragon
define('TRANSFER_FINISHED', 3);                  // by FireDragon
define('TRANSFER_FAILED', 11);                   // by FireDragon

define('SUCCESS', 'Success');                   // by FireDragon
define('FAIL', 'Fail');
define('LOW_BALANCE', 'LowBalance');
define('SAME_CURRENCY', 'SameCurrency');
define('FEE', 0.01);                            // by FireDragon

define('PER_PAGE', 10);                          // by FireDragon

//Stellar
define('RIPPLE_MAIN_CURRENCY', 'BSC');
define('RIPPLE_DEPOSIT_CURRENCY', 'JPY');
define('RIPPLE_INSUFFICIENT_FUND', 'Insufficient funds');
define('RIPPLE_INSUFFICIENT_RESERVE', 'Insufficient reserve');
define('RIPPLE_PATH_NOFOUND', 'Path no found');
define('RIPPLE_ADDRESS_INVALID', 'Invalid address');
define('RIPPLE_RESERVE_ACCOUNT', 20);
define('RIPPLE_RESERVE_OWNER', 5);
define('RIPPLE_SLIPPAGE_DEFAULT', '0.00000001');
define('RIPPLE_MAX_EXECUTION_TIME', 800); // 300s

# https://ripple.com/build/transactions/#lifecycle-of-an-offer
define('RIPPLE_OFFER_DEFAULT', 0);
define('RIPPLE_OFFER_SELL', 524288);
define('RIPPLE_OFFER_PASSIVE', 65536);
define('RIPPLE_OFFER_IOC', 131072);
define('RIPPLE_TIME_OFFSET', (30*365+7)*24*3600); // 30 year
define('RIPPLE_OFFER_EXPIRATION', 30*24*3600); // 30 days

//Ajax Time
define('AJAX_BALANCE_TIME', 5); //Minutes
define('AJAX_RATE_TIME', 10); //Minute

// DateTime
//define('MYSQL_DATE', 'Y-m-d');
define('MYSQL_DATETIME', 'Y-m-d H:i:s');
define('ZONE_DATETIME', 'Y-m-d\TH:i:sP');

//QRCode URL, Image Size
define('QRURL', "https://chart.googleapis.com/chart?cht=qr&chs=@@SIZE@@&chl=@@CODE@@&choe=@@ENCODING@@");
define('QRIMG_SIZE', 300);

//Currency Approval Flag
define('CURR_TOP10_APPROVED', 1);
define('CURR_TOP10_NOT_APPROVED', 0);

//Currency Usage Flag
define('BASE_CURRENCY', -1);
define('CURR_PERSONAL_USAGE', 0);
define('CURR_BUSINESS_USAGE', 1);

//Mail Type
define('SIGN_UP_NOTIFICATION', 0);
define('UPLOAD_NOTIFICATION', 1);
define('SMS_AUTH_NOTIFICATION', 2);

define('DEPOSIT_REQ_NOTIFICATION', 7);
define('WITHDRAW_REQ_NOTIFICATION', 8);

//User flag
define('NEW_USER', 1);
define('OLD_USER', 0);

define('NO_UPLOADED', 0);
define('FIRST_TIME_UPLOADED', 1);
define('NEW_UPLOADED', 2);
define('OLD_UPLOADED', 3);

// Coin Type
define('COIN_FUND', 1);
define('COIN_WITHDRAW', 2);

// Coin Status
define('COIN_INIT', 1);
define('COIN_COMPLETE', 2);
define('COIN_ERROR', 3);

// History update type
define('UPDATE_DEPOSIT', 'fund');