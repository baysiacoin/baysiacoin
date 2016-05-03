<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class AdminAuthenticate {
	
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
		if (!$user->hasRole(ROLE_ADMIN) && !$user->hasRole(ROLE_BRANCH1) && !$user->hasRole(ROLE_BRANCH2) && !$user->hasRole(ROLE_ADMIN2))
		{
			return redirect()->guest('auth/login');
		}

		$topRole = $user->getTopRole();
		View::share('topRole', $topRole);

		return $next($request);
	}
}