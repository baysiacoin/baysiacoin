<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Config;
use League\Flysystem\Exception;

class VerifyCsrfToken extends BaseVerifier {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		try {
			$exceptUrls = Config::get('conf.no_csrf');
			foreach ($exceptUrls as $url) {
				if (strpos($request->path(), $url) === 0) {
					return parent::addCookieToResponse($request, $next($request));
				}
			}
			return parent::handle($request, $next);
		} catch(Exception $e){
			return view('errors.token_error');
		}
	}

}
