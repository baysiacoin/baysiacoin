<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('/', 'WelcomeController@index');
Route::get('/user', 'UserController@index');
Route::get('/user/country-state/{iso_3661_2}', 'UserController@getCountryStates');
Route::get('/{param}', 'WelcomeController@index');

Route::get('register', 'Auth\AuthController@getRegister');
Route::get('register/{branch1}', 'Auth\AuthController@getRegister')->where(['branch1' => '[0-9a-zA-Z]{1,10}']);
Route::get('register/{branch1}/{branch2}', 'Auth\AuthController@getRegister')->where(['branch1' => '[0-9a-zA-Z]{1,10}', 'branch2' => '[0-9a-zA-Z]{1,10}']);

Route::get('register/validfail', 'Auth\AuthController@postRegister');

//Added By Boss.2015/06/11
Route::get('/auth/fb', 'Auth\AuthController@fblogin');
Route::get('/auth/fb/callback', 'Auth\AuthController@fbcallback');
Route::get('/issue/agree/{token}', 'Auth\AuthController@approveCurrency');

Route::get('/user/lang/{code}', 'LanguageController@setLanguage');

Route::get('/money/history/fund', 'MoneyController@history_fund');
Route::post('/money/history/fund', 'MoneyController@history_fund_search');

Route::get('/money/history/withdraw', 'MoneyController@history_withdraw');
Route::post('/money/history/withdraw', 'MoneyController@history_withdraw_search');

Route::get('/money/history/transfer', 'MoneyController@history_transfer');
Route::post('/money/history/transfer', 'MoneyController@history_transfer_search');

Route::get('/money/history/trade/{type}', 'MoneyController@history_trade');
Route::get('/money/view/{account?}', 'MoneyController@viewAccount');

Route::get('/money/gateway-list/{curr}', 'MoneyController@getGatewayList');
Route::get('/money/gateway-list2/{curr}', 'MoneyController@getGatewayList2');

Route::get('/money/transfer/{to_account?}', 'MoneyController@getTransfer');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
	'user' => 'UserController',
	'money' => 'MoneyController',
]);
/*Route::any('/{page?}',function(){
	return View::make('errors.404');
})->where('page','.*');*/
