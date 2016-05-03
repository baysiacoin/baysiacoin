<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use View;

class Authenticate {

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 * @return void
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if ($this->auth->guest())
		{
			if ($request->ajax())
			{
				return response('Unauthorized.', 401);
			}
			else
			{
				return redirect()->guest('auth/login');
			}
		}
		
		/*
		 * OTP, 2FA Verification
		 */
		if ($this->auth->check() && ($this->auth->user()->qr_flag == 1 || 
			$this->auth->user()->tfa_flag == 1) && Session::get('user_authed') == false) 
		{
			return redirect(url('/auth/two-factor'));
		}

		$loginUser = $this->auth->user();

		if ($locale = Session::get('locale', 'ja') == 'en') {
			View::share('login_username', $loginUser['lastname'] . ' ' . $loginUser['firstname']);//firstname is the real lastname unfortunaltely.
		} else
			View::share('login_username', $loginUser['firstname'] . ' ' . $loginUser['lastname']);
		return $next($request);
	}

}
