<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class AdminUserAuthenticate {

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
		$user = Auth::user();
		// if the role is admin or branch1 or branch2 pass the request
		if ($user->hasRole(ROLE_ADMIN) || $user->hasRole(ROLE_BRANCH1) || $user->hasRole(ROLE_BRANCH2)) {
			$topRole = $user->getTopRole();
			View::share('topRole', $topRole);
			return $next($request);
		// if role is admin2 redirect to only pending orders
		} else if ($user->hasRole(ROLE_ADMIN2)) {
			if (strpos($_SERVER['REQUEST_URI'], 'pending_orders')) {
				$topRole = $user->getTopRole();
				View::share('topRole', $topRole);
				return $next($request);
			} else {
				return view('errors.404');
			}
		}
		// if the role is user redirect to user page
		return redirect()->guest('auth/login');
	}
}
