<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Session\Store;

class SessionTimeout {
    protected $session;
    protected $timeout = 1800;

    public function __construct(Store $session){
        $this->session = $session;
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
        $status = Auth::check();
        if ($status)
        {
            if (!$this->session->has('lastActivityTime'))
            {
                $this->session->put('lastActivityTime', time());
            }
            else if (time() - $this->session->get('lastActivityTime') > $this->timeout)
            {
                $this->session->forget('lastActivityTime');
                $this->session->forget('user_authed');
                Auth::logout();
                //App::setLocale($this->session->get('locale', 'ja'));
                return redirect('auth/login')->with(['session_timeout_warning' => trans('message.security.session_timeout_warning'), 'session_timeout_warning_recommend' => trans('message.security.session_timeout_warning_recommend')]);
            }            
        }
        $this->session->put('lastActivityTime', time());
        return $next($request);
    }
}