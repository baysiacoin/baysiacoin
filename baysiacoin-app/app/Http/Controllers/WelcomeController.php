<?php namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Request;

class WelcomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//		print_r("dd");exit;
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index($request = null)
	{
		if ($request != 'user') {
			return redirect('/auth/login');
		}
		$message = Session::get('message');

		if (Session::has('message') && !empty($message)) {
            $data['message'] = Session::get('message');
            Session::forget('message');
            return view('welcome', $data);
        } else {
            return view('welcome');
        }
	}

}
