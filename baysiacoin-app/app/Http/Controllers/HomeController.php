<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\UserInfo;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('myprofile');
	}

	/**
	 * Balance
	 */
	public function balance()
	{
		$user = Auth::user();
	    $userInfo = UserInfo::where('user_id', $user->id)->first();

	    return view('balance', ['userInfo' => $userInfo, 'username' => $user->username]);
	}

	/**
	 * Trade
	 */
	public function trade()
	{
		$user = Auth::user();
		return view('trade', ['username' => $user->username]);
	}

	/**
	 * History
	 */
	public function history()
	{
		$user = Auth::user();
		return view('history', ['username' => $user->username]);
	}

	/**
	 * Send
	 */
	public function send()
	{
		$user = Auth::user();
		return view('send', ['username' => $user->username]);
	}

	/**
	 * Deposit
	 */
	public function deposit()
	{
		$user = Auth::user();
		return view('deposit', ['username' => $user->username]);
	}

	/**
	 * Fund
	 */
	public function fund()
	{
		$user = Auth::user();
		return view('fund', ['username' => $user->username]);
	}

	/**
	 * Withdraw
	 */
	public function withdraw()
	{
		$user = Auth::user();
		return view('withdraw', ['username' => $user->username]);
	}
}
