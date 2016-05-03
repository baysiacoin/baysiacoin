<?php namespace App\Http\Middleware;

use App\Libraries\Common;
use App\User;
use App\UserWallet;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Session\Store;
use App\UserLoginInfo;
use Illuminate\Support\Facades\Mail;

class SessionIPAuthenticate {
    protected $session;

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
        $user = Auth::user();
        if (is_null($user) && isset($request['email']))
        {
            $user = User::where('email', $request['email'])->first();
        }

        if (!is_null($user))
        {
            $ip = $_SERVER['REMOTE_ADDR'];
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $browser = Common::fetchBrowser();
            $user_login_info = UserLoginInfo::where('user_id', $user->id)->first();

            if ($this->session->getId() != $user_login_info['session_id'] && $status) {
                Auth::logout();
                return redirect('auth/login')->with(['session_id_warning' => trans('message.security.session_id_warning'), 'session_id_warning_recommend' => trans('message.security.session_id_warning_recommend')]);
            } else if ((!empty($user_login_info['connect_ip']) && $ip != $user_login_info['connect_ip']) || (!empty($user_login_info['browser']) && $browser != $user_login_info['browser'])) {
                $credentials = $request->only('email', 'password');
                $credentials['verified'] = 1;
                $credentials['del_flag'] = 0;
                if (Auth::validate($credentials))
                {
                    if ($status) {
                        Auth::logout();
                    }
                    //generate verify token
                    $verify_token = md5($this->session->getId());
                    $user_login_info['verify_token'] = $verify_token;
                    $user_login_info->save();

                    $url = url('/auth/verify-token/' . $verify_token);
                    $user_wallet = UserWallet::where('user_id', $user->id)->first();
                    $email = empty($user->email) ? '' : $user->email;
                    $mail_data = [
                        'firstname' => $user->firstname,
                        'lastname' => $user->lastname,
                        'time' => date('Y-m-d h:i:s A', time()),
                        'ip' => $ip,
                        'browser' => $browser,
                        'user_agent' => $user_agent,
                        'url' => $url,
                        'wallet_address' => $user_wallet['wallet_address']
                    ];
                    Mail::queue('emails.connect_info', $mail_data, function ($message) use ($email) {
                        $message->to($email)->subject(trans('message.mail.notify_connect_info_to_user'));
                    });
                    return redirect('auth/login')->with(['connect_info_warning' => trans('message.security.connect_info_warning'), 'connect_wish_email_confirm' => trans('message.security.connect_wish_email_confirm')]);
                }
            }
        }
        return $next($request);
    }
}