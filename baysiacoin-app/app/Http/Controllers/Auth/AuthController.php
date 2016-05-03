<?php namespace App\Http\Controllers\Auth;

use App\Currency;
use App\UserWallet;
use Facebook;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\User;
use App\UserInfo;
use App\UserLoginInfo;
use App\Http\Controllers\Controller;
use App\Libraries\Common;
use GeoIP;
use BaysiaRPCHandler;

require_once app_path().'/Libraries/EHash.php';
require_once app_path().'/Libraries/Common.php';
require_once app_path() . '/Libraries/BaysiaRPCLib/autoload.php';

class AuthController extends Controller {
	/**
	 * Create a new authentication controller instance.
	 *
	 */
	public function __construct(Guard $auth, Registrar $registrar)
	{
		$this->auth = $auth;
		$this->registrar = $registrar;
		$this->middleware('guest', ['except' => ['getLogout', 'approveCurrency']]);
	}
	/**
	 * Show the application login form.
	 *
	 */
	public function getLogin1()
	{
		Session::put('fb_prev_url', '/auth/login');
		$message = Session::get('message');
		$mail_message = Session::pull('mail_message');
		$mail_resend_message = trans('message.register.mail_resend');
        if (Session::has('message') && !empty($message)) {
            $data['message'] = Session::get('message');
            Session::forget('message');
            return view('auth.login', $data);
        } else {
			$data['mail_message'] = $mail_message;
			$data['mail_resend_message'] = $mail_resend_message;
//            return view('auth.login', $data);
            return view('auth.newlogin', $data);
        }
	}
	/**
	 * Show the application login form
	 */
	public function getLogin()
	{
		Session::put('fb_prev_url', '/auth/login');
		$message = Session::get('message');
		$mail_message = Session::pull('mail_message');
		$mail_resend_message = trans('message.register.mail_resend');
		if (Session::has('message') && !empty($message)) {
			$data['message'] = Session::get('message');
			Session::forget('message');
			return view('auth.login', $data);
		} else {
			$data['mail_message'] = $mail_message;
			$data['mail_resend_message'] = $mail_resend_message;
            return view('auth.login', $data);
		}
	}

	/**
	 * Handle a login request to the application
	 * @param Request $request
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function postLogin(Request $request)
	{
		$this->validate($request, [
			// 'email' => 'required|email','password' => 'required',
			'email' => 'required',
			'password' => 'required',
		]);

		$credentials = $request->only('email', 'password');
		$credentials['verified'] = 1;
		$credentials['del_flag'] = 0;
	
		if ($this->auth->attempt($credentials, $request->has('remember')))
		{
			// leave connection log
			$this->logConnInfo();
			return redirect()->intended($this->redirectPath());
		}

		return redirect($this->loginPath())
			->withInput($request->only('email', 'remember'))
			->withErrors(['email' => $this->getFailedLoginMessage()]);
	}

	/**
	 * Handle a login request to the application
	 * @param Request $request
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function postLogin1(Request $request)
	{
		$this->validate($request, [
			// 'email' => 'required|email','password' => 'required',
				'email' => 'required',
				'password' => 'required',
		]);

		$credentials = $request->only('email', 'password');
		$credentials['verified'] = 1;
		$credentials['del_flag'] = 0;

		if ($this->auth->attempt($credentials, $request->has('remember')))
		{
			//leave connection log
			$this->logConnInfo();
			return redirect()->intended($this->redirectPath());
		}

		return redirect($this->loginPath1())
				->withInput($request->only('email', 'remember'))
				->withErrors([
						'email' => $this->getFailedLoginMessage(),
				]);
	}
	/**
	 * Show the two-factor authentication form.
	 *
	 */
	public function getTwoFactor()
	{
		if (!Auth::check()) {
			return redirect('auth/login');
		}
		$user = Auth::user();
		if (1 == $user->qr_flag) {
			$data['code'] = generate_random_code(6);//generate 6 random alphabet
			$user->qr_token = $data['code'];
			$user->save();
		}
		/*
		** send sms request
		*/
		if (!Session::get('first_req_sms') && 1 == $user->tfa_flag) {
			$user->sendSmsToken();
			Session::put('first_req_sms', true);
		}
		$data['qr_flag'] = $user->qr_flag;
		$data['tfa_flag'] = $user->tfa_flag;		
		return view('auth.twofactor', $data);
	}

	/**
	 * Handle a two-factor authentication.
	 *
	 */
	public function postTwoFactor(Request $request)
	{
		$user = Auth::user();
		$data = $request->all();
		$this->validate($request, [
			'qr_token' => 'sometimes|required',
			'sms_token' => 'sometimes|required|digits:7',
		]);

		Session::forget('user_authed');
		/*
		** verify qr_token
		*/
		if (1 == $user->qr_flag && isset($data['qr_token']) && $user->qr_token != $data['qr_token']){
			Session::put('user_authed', false);
			return redirect()->back()->withErrors(['qr_token_mismatch' => trans('message.login_tfa.qr_token_mismatch')]);
		}
		/* 
		**verify sms_token	
		*/		
		if (1 == $user->tfa_flag && isset($data['sms_token'])  && !$user->verifySmsToken($data['sms_token'])){
			Session::put('user_authed', false);
			return redirect()->back()->withErrors(['sms_token_mismatch' => trans('message.login_tfa.sms_token_mismatch')]);
		}

		Session::put('user_authed', true);

		return redirect($this->redirectPath());
	}
	/**
	 * request for sms
	 */
	public function postSmsRequest() {
		$user = Auth::user();
		if (1 == $user->tfa_flag) {
			if ($user->sendSmsToken()) {
				echo json_encode(['result' => 'Success']);
			} else {
				echo json_encode(['result' => 'Fail']);
			}
		} else {
			echo json_encode(['result' => 'Fail']);
		}
		exit;
	}
	/**
	 * Get the failed login message.
	 *
	 */
	protected function getFailedLoginMessage()
	{
		return trans('message.login_error_email');
	}
	
	/**
	 * Log the user out of the application.
	 *
	 */
	public function getLogout()
	{
		if ($this->auth->check()) {
			$this->initConnInfo();
			$this->auth->logout();
	        if(Session::has('from_facebook')) {
	            Session::forget('from_facebook');
	        }
	        if(Session::has('fb_init_pwd')) {
	            Session::forget('fb_init_pwd');
	        }
			Session::forget('user_authed');
			Session::forget('first_req_sms');
		}
		return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
	}
	
	/**
	 * Get the post register / login redirect path.
	 *
	 */
	public function redirectPath()
	{
		if (property_exists($this, 'redirectPath')) {
			return $this->redirectPath;
		}
		return property_exists($this, 'redirectTo') ? $this->redirectTo : '/money/balance';
	}
	
	/**
	 * Get the path to the login route.
	 *
	 */
	public function loginPath()
	{
		return property_exists($this, 'loginPath') ? $this->loginPath : '/auth/login';
	}

	/**
	 * Get the path to the login route.
	 *
	 */
	public function loginPath1()
	{
		return '/auth/login1';
	}
	/**
	 * Register Form
	 * 
	 */
	public function getRegister1($branch1 = '', $branch2 = '')
	{
		if (!empty($_SERVER['REQUEST_URI'])) {
			$matches = array();
			preg_match('/\/register([0-9]+)\//ui', $_SERVER['REQUEST_URI'], $matches);
			if (!empty($matches[1])) {
				$branch1 = $matches[1];
			}
		}
		/*
		 * put the branch data to session
		 */
		session(['branch1' => $branch1]);
		session(['branch2' => $branch2]);
		return view('auth.newregister');
	}

	/**
	 * Register Form
	 *
	 */
	public function getRegister($branch1 = '', $branch2 = '')
	{
		if (!empty($_SERVER['REQUEST_URI'])) {
			$matches = array();
			preg_match('/\/register([0-9]+)\//ui', $_SERVER['REQUEST_URI'], $matches);
			if (!empty($matches[1])) {
				$branch1 = $matches[1];
			}
		}
		/*
		 * put the branch data to session
		 */
		session(['branch1' => $branch1]);
		session(['branch2' => $branch2]);
		return view('auth.register');
	}
    /**
     * Register account from facebook
     * Added By Boss.2015/06/11
     */
    public function fblogin()
    {
        Session::forget('message');

        $facebook = new Facebook(config('facebook.app_secret'));
        $params = array(
            'redirect_uri' => url('/auth/fb/callback'),
            'scope' => 'public_profile, email, user_birthday, user_hometown, user_location',
        );

        return redirect($facebook->getLoginUrl($params));
    }

    /**
     * Register account from facebook
     * Added By Boss.2015/06/11
     */
    public function fbcallback(Request $request)
    {
		$code = $request->only('code');

        if (is_array($code) && count($code) == 0) {
            Session::put('message', trans('message.register.error_facebook_communication'));
            return redirect()->back();
            //return redirect('/auth/register');
        } else if(!is_array($code) && strlen($code) == 0) {
            Session::put('message', trans('message.register.error_facebook_communication'));
            return redirect()->back();
            //return redirect('/auth/register');
        }

        $facebook = new Facebook(config('facebook.app_secret'));
        $uid = $facebook->getUser();

        if ($uid == 0) {
            Session::put('message', trans('message.register.error_facebook_problem'));
            return redirect()->back();
            //return redirect('/auth/register');
        }

        $loginInfo = $facebook->api('/me');
        if (empty($loginInfo['email'])) {
            Session::put('message', trans('message.register.error_facebook_email_empty'));
            return redirect()->back();
            //return redirect('/auth/register');
        }

        $profile = User::where('email', $loginInfo['email'])->first();

        if (!empty($profile) && $profile->del_flag == 1) {
            $profile->delete();
            $profile = null;
        }

        $facebookInfo = array(
            'first_name' => '',
            'last_name' => '',
            'gender' => 'male',
            'birthday' => '',
            'hometown' => '',
            'location' => '',
        );

        if(isset($loginInfo['first_name'])) {
            $facebookInfo['first_name'] = $loginInfo['first_name'];
        }

        if(isset($loginInfo['last_name'])) {
            $facebookInfo['last_name'] = $loginInfo['last_name'];
        }

        if(isset($loginInfo['email'])) {
            $facebookInfo['email'] = $loginInfo['email'];
        }

        if(isset($loginInfo['gender'])) {
            $facebookInfo['gender'] = $loginInfo['gender'];
        }

        if(isset($loginInfo['birthday'])) {
            $facebookInfo['birthday'] = $loginInfo['birthday'];
        }

        if(isset($loginInfo['hometown'])) {
            $facebookInfo['hometown'] = $loginInfo['hometown']['name'];
        }

        if(isset($loginInfo['location'])) {
            $facebookInfo['location'] = $loginInfo['location']['name'];
        }

        if (empty($profile)) {
            /*$plainPwd = '';
            for($i = 0; $i < 6; $i ++) {
                $plainPwd = $plainPwd . rand(0, 10);
            }
            if(doubleval($plainPwd) == 0) {
                $plainPwd =  trans('message.register.facebook_account_default_password');
            }*/
			$plainPwd = generate_random_code();
			Session::put('fb_init_pwd', $plainPwd);
            $password = bcrypt($plainPwd);
			$token_hash = ehash_encrypt($plainPwd, EHASH_ENCRYPTION_KEY);

            //User Creating Step
            $user = User::create([
                'firstname' => $facebookInfo['first_name'],
                'lastname' => $facebookInfo['last_name'],
                'username' => $facebookInfo['first_name'] . $facebookInfo['last_name'],
                'email' => $facebookInfo['email'],
                'password' => $password,
                'verify_code' => md5($password),
				'token_hash' => $token_hash
            ]);

            $newBirthdayDate = '';
            if (!empty($facebookInfo['birthday'])) {
                $newBirthdayDate = date("Y-m-d", strtotime($facebookInfo['birthday']));
            }

            //UserInfo Creating Step
            $userInfo = UserInfo::create([
                'user_id' => $user->id,
                'firstname1' => $facebookInfo['first_name'],
                'lastname1' => $facebookInfo['last_name'],
                'gender' => $facebookInfo['gender'] == 'male' ? 0 : 1,
                'birthday' => $newBirthdayDate,
                'city' => $facebookInfo['location'],
                'address' => $facebookInfo['hometown'],
				'branch1' => Session::get('branch1'),
				'branch2' => Session::get('branch2'),
                'fb_register' => 1,
            ]);

            //Verify Step
            if ($user && $user->verify_code == md5($user->password))
            {
                $user->verified = 1;
                $user->save();
                Auth::loginUsingId($user->id);
				// leave connection log
				$this->logConnInfo();
				/*
				 * send notification to admin's email address
				 */
				$admin_email = Config::get('mail.username'); //info@japan.baysiacoin.com
				$mailData = [
					'type' => SIGN_UP_NOTIFICATION,
					'firstname' => $user->firstname,
					'lastname' => $user->lastname,
					'email' => $user->email,
				];
				Mail::queue('emails.notify2admin', $mailData, function($message) use($admin_email) {
					$message->to($admin_email)->subject(trans('message.mail.notify_new_to_admin'));
				});
            }
            //Password Reset URL
			$url = url('/auth/reset/' . $user->verify_code);
            //Send Mail step
            $mailData = [
				'isFBCallback' => true,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'url' => $url,
				'email' => $user->email,
                'username' => $user->username,
                'password' => $plainPwd,
            ];
			Session::put('mail_data', $mailData);
			Session::put('mail', $user->email);

			if (Session::get('locale', 'ja') == 'en') {
				Mail::send('emails.registeren', $mailData, function($message) use ($user)
				{
					$message->to($user->email)->subject(trans('message.mail.welcome'));
				});
			} else {
				Mail::send('emails.register', $mailData, function($message) use ($user)
				{
					$message->to($user->email)->subject(trans('message.mail.welcome'));
				});
			}

			Session::put('mail_message', trans('message.register.mail_sent'));

        } else {
            Session::put('message', trans('message.register.error_facebook_exist'));
			$fb_prev_url = '/';
			if(Session::has('fb_prev_url')) {
				$fb_prev_url = Session::get('fb_prev_url');
				Session::forget('fb_prev_url');
			}
			return redirect($fb_prev_url);
            //return redirect('/auth/register');
        }

        Session::put('from_facebook', true);
        return redirect('/user/profile')->withInput(array('email' => $facebookInfo['email'], 'password' => $password)); //Goto User Profile
        //return redirect('/');
    }

	public function postRegister(Request $request)
	{
		$request->flash();
		return view('auth.register');
	}

	public function postRegister1(Request $request)
	{
		$request->flash();
		return view('auth.newregister');
	}
	/**
	 * Account Register Modify
	 */
	public function postModify()
	{
		return redirect('/auth/register?from=modify')->withInput();
	}
	/**
	 *Account Register Confirm
	 */
	public function getConfirm() 
	{
		return redirect('auth/register');
	}
	/**
	 * Account Register Confirm*
	 */
	public function postConfirm(Request $request)
	{
		$data = $request->all();
//		$birthdayBefore = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d"), date("Y") - 18));
		/*
		* prevent google mail multi_dot.
		*/
		/*$mail_domain = strstr($data['email'], '@');
		if ( $mail_domain == '@gmail.com' || $mail_domain == '@googlemail.com' ) {
			$mail_domain = '@gmail.com';
			$mail_name = strstr($data['email'], '@', true);
			$mail_name = str_replace('.', '', $mail_name);
			$mail_name = str_replace('+', '', $mail_name);
			$data['email'] = $mail_name . $mail_domain;
		}*/
		/*
		 * validate input fields.
		 */
		$validator = Validator::make($data, [
			'firstname' => 'required',
			'lastname' => 'required',
			'email' => 'required|email|unique:users',
			'password' => 'required|confirmed|min:8',
			'kiyaku' => 'accepted',
		]);
		/*
		 * add hurikana check if the language is Japanese.
		 */
//		$validator->sometimes(['firstname1', 'lastname1'], 'required', function() {
//			return Session::get('locale', 'ja') == 'ja';
//		});
		if ($validator->fails()) {
			$data['errors'] = $validator->errors();
			$request->flash();
			return redirect()->back()->withErrors($validator->errors());
		}
		$data['kiyaku'] = '1';

		return view('auth.confirm', $data);
	}
	/**
	 * Account Register Confirm*
	 */
	public function postConfirm1(Request $request)
	{
		$data = $request->all();
//		$birthdayBefore = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d"), date("Y") - 18));
		/*
		* prevent google mail multi_dot.
		*/
		$mail_domain = strstr($data['email'], '@');
		if ( $mail_domain == '@gmail.com' || $mail_domain == '@googlemail.com' ) {
			$mail_domain = '@gmail.com';
			$mail_name = strstr($data['email'], '@', true);
			$mail_name = str_replace('.', '', $mail_name);
			$mail_name = str_replace('+', '', $mail_name);
			$data['email'] = $mail_name . $mail_domain;
		}
		/*
		 * validate input fields.
		 */
		$validator = Validator::make($data, [
				'firstname' => 'required',
				'lastname' => 'required',
				'email' => 'required|email|unique:users',
				'password' => 'required|confirmed|min:8',
		]);
		/*
		 * add hurikana check if the language is Japanese.
		 */
//		$validator->sometimes(['firstname1', 'lastname1'], 'required', function() {
//			return Session::get('locale', 'ja') == 'ja';
//		});
		if ($validator->fails()) {
			$data['errors'] = $validator->errors();
			$request->flash();
			return redirect()->back()->withErrors($validator->errors());
		}
		$data['kiyaku'] = '1';

		return view('auth.newconfirm', $data);
	}

	/**
	 * Account Register Create
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function postCreate(Request $request)
	{
		$data = $request->all();
		/*
		** Recover the ip region
		*/
		$ip = $_SERVER['REMOTE_ADDR'];
		$ip_info = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
		/*
		** Prevent google mail multi_dot
		*/
		/*$mail_domain = strstr($data['email'], '@');
		if ( $mail_domain == '@gmail.com' || $mail_domain == '@googlemail.com' ) {
			$mail_domain = '@gmail.com';
			$mail_name = strstr($data['email'], '@', true);
			$mail_name = str_replace('.', '', $mail_name);
			$mail_name = str_replace('+', '', $mail_name);
			$data['email'] = $mail_name . $mail_domain;
		}*/
		/*
		 * encrypt, hash password
		 */
		$password = bcrypt($data['password']);
		$token_hash = ehash_encrypt($data['password'], EHASH_ENCRYPTION_KEY);
		/*
		 * save user's information to db.
		 */
		$user = User::create([
			'firstname' => $data['firstname'],
			'lastname' => $data['lastname'],
			'username' => $data['firstname'] . $data['lastname'],
			'email' => $data['email'],
			'password' => $password,
			'verify_code' => md5($password),
			'token_hash' => $token_hash,
			'new_flag' => NEW_USER
		]);
		$userInfo = UserInfo::create([
			'user_id' => $user->id,
			'country' => isset($ip_info->country) ? $ip_info->country : '',
			//'birthday' => $data['birthday'],
			'branch1' => $data['branch1'],
			'branch2' => $data['branch2'],//
		]);
		/*
		 * add hurikana to db if the language is Japanese.
		 */
//		if (($locale = Session::get('locale', 'ja')) == 'ja') {
//			$userInfo->firstname1 = $data['firstname1'];
//			$userInfo->lastname1 = $data['lastname1'];
//			$userInfo->save();
//		}
        //Verify URL
        $url = url('auth/verify/' . $user->verify_code);
		/*
		* send verification email to user's email address *** language support ***
		 */
		$mailData = [
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'url' => $url,
            'email' => $user->email,
            'password' => $data['password'],
		];
		/*
		 * put mail data into session to resend email.
		 */
		Session::put('mail_data', $mailData);
		Session::put('mail', $user->email);

		$locale = Session::get('locale', 'ja');

		Mail::send($locale == 'en' ? 'emails.registeren' : 'emails.register', $mailData, function($message) use ($user)
		{
			$message->to($user->email)->subject(trans('message.mail.welcome'));
		});
		Session::put('mail_message', trans('message.register.mail_sent'));
		/*
		 * send notification to admin's email address
		 */
		$admin_email = Config::get('conf.admin_email'); //info@japan.baysiacoin.com
		$mailData = [
			'type' => SIGN_UP_NOTIFICATION,
			'firstname' => $user->firstname,
			'lastname' => $user->lastname,
			'email' => $user->email,
		];
		Mail::queue('emails.notify2admin', $mailData, function($message) use ($admin_email) {
			$message->to($admin_email)->subject(trans('message.mail.notify_new_to_admin'));
		});
		/*
		 * 代理店１にメール送信
		 */
		if (!empty($data['branch1']) && $data['branch1'] != '-1') {
			$agent1_info = User::leftJoin('users_role', 'users.id', '=', 'users_role.user_id')
				->leftJoin('users_info', 'users_info.user_id', '=', 'users.id')
				->leftJoin('roles', 'users_role.role_id', '=', 'roles.id')
				->select('users.id', 'users.email', 'users.firstname', 'users.lastname')
				->where('users_info.branch1', '=', $data['branch1'])
				->where('roles.name', '=', ROLE_BRANCH1)
				->first();

			if (!empty($agent1_info)) {
				$mailData = [
					'receiver_firstname' => $agent1_info->firstname,
					'receiver_lastname' => $agent1_info->lastname,
					'firstname' => $user->firstname,
					'lastname' => $user->lastname,
					'email' => $user->email,
				];
				Mail::queue('emails.notify2agent', $mailData, function($message) use ($agent1_info) {
					$message->to($agent1_info->email)->subject(trans('message.mail.notify2agent1'));
				});
			}
		}
		/*
		 * 代理店２にメール送信
		 */
		if (!empty($data['branch2']) && $data['branch2'] != '-1') {
			$agent2_info = User::leftJoin('users_role', 'users.id', '=', 'users_role.user_id')
				->leftJoin('users_info', 'users_info.user_id', '=', 'users.id')
				->leftJoin('roles', 'users_role.role_id', '=', 'roles.id')
				->select('users.id', 'users.email', 'users.firstname', 'users.lastname')
				->where('users_info.branch2', '=', $data['branch2'])
				->where('roles.name', '=', ROLE_BRANCH2)
				->first();
			if (!empty($agent2_info)) {
				$mailData = [
					'receiver_firstname' => $agent2_info->firstname,
					'receiver_lastname' => $agent2_info->lastname,
					'firstname' => $user->firstname,
					'lastname' => $user->lastname,
					'email' => $user->email,
				];
				Mail::queue('emails.notify2agent', $mailData, function($message) use ($agent2_info) {
					$message->to($agent2_info->email)->subject(trans('message.mail.notify2agent2'));
				});
			}
		}
		return redirect('/');
	}
	/**
	 * Account Register Create
	 *
	 */
	public function postCreate1(Request $request)
	{
		$data = $request->all();
		/*
		** Recover the ip region
		*/
		$ip = $_SERVER['REMOTE_ADDR'];
		$ip_info = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
		/*
		** Prevent google mail multi_dot
		*/
		$mail_domain = strstr($data['email'], '@');
		if ( $mail_domain == '@gmail.com' || $mail_domain == '@googlemail.com' ) {
			$mail_domain = '@gmail.com';
			$mail_name = strstr($data['email'], '@', true);
			$mail_name = str_replace('.', '', $mail_name);
			$mail_name = str_replace('+', '', $mail_name);
			$data['email'] = $mail_name . $mail_domain;
		}
		/*
		 * encrypt, hash password
		 */
		$password = bcrypt($data['password']);
		$token_hash = ehash_encrypt($data['password'], EHASH_ENCRYPTION_KEY);
		/*
		 * save user's information to db.
		 */
		$user = User::create([
				'firstname' => $data['firstname'],
				'lastname' => $data['lastname'],
				'username' => $data['firstname'] . $data['lastname'],
				'email' => $data['email'],
				'password' => $password,
				'verify_code' => md5($password),
				'token_hash' => $token_hash,
				'new_flag' => NEW_USER
		]);
		$userInfo = UserInfo::create([
				'user_id' => $user->id,
				'country' => isset($ip_info->country) ? $ip_info->country : '',
			//'birthday' => $data['birthday'],
				'branch1' => $data['branch1'],
				'branch2' => $data['branch2'],//
		]);
		/*
		 * add hurikana to db if the language is Japanese.
		 */
//		if (($locale = Session::get('locale', 'ja')) == 'ja') {
//			$userInfo->firstname1 = $data['firstname1'];
//			$userInfo->lastname1 = $data['lastname1'];
//			$userInfo->save();
//		}
		//Verify URL
		$url = url('auth/verify/' . $user->verify_code);
		/*
		* send verification email to user's email address *** language support ***
		 */
		$mailData = [
				'firstname' => $user->firstname,
				'lastname' => $user->lastname,
				'url' => $url,
				'email' => $user->email,
				'password' => $data['password'],
		];
		/*
		 * put mail data into session to resend email.
		 */
		Session::put('mail_data', $mailData);
		Session::put('mail', $user->email);

		$locale = Session::get('locale', 'ja');

		Mail::send($locale == 'en' ? 'emails.registeren' : 'emails.register', $mailData, function($message) use ($user)
		{
			$message->to($user->email)->subject(trans('message.mail.welcome'));
		});
		Session::put('mail_message', trans('message.register.mail_sent'));
		/*
		 * send notification to admin's email address
		 */
		$admin_email = Config::get('mail.username'); //info@japan.baysiacoin.com
		$mailData = [
				'type' => SIGN_UP_NOTIFICATION,
				'firstname' => $user->firstname,
				'lastname' => $user->lastname,
				'email' => $user->email,
		];
		Mail::queue('emails.notify2admin', $mailData, function($message) use ($admin_email) {
			$message->to($admin_email)->subject(trans('message.mail.notify_new_to_admin'));
		});
		/*
		 * 代理店１にメール送信
		 */
		if (!empty($branch1) && $branch1 != '-1') {
			$agent1_info = User::leftJoin('users_role', 'users.id', '=', 'users_role.user_id')
					->leftJoin('users_info', 'users_info.user_id', '=', 'users.id')
					->leftJoin('roles', 'users_role.role_id', '=', 'roles.id')
					->select('users.id', 'users.email', 'users.firstname', 'users.lastname')
					->where('users_info.branch1', '=', $branch1)
					->where('roles.name', '=', ROLE_BRANCH1)
					->first();
			if (!empty($agent1_info)) {
				$mailData = [
						'receiver_firstname' => $agent1_info->firstname,
						'receiver_lastname' => $agent1_info->lastname,
						'firstname' => $user->firstname,
						'lastname' => $user->lastname,
						'email' => $user->email,
				];
				Mail::queue('emails.notify2agent', $mailData, function($message) use ($agent1_info) {
					$message->to($agent1_info->email)->subject(trans('message.mail.notify_agent1'));
				});
			}
		}
		/*
		 * 代理店２にメール送信
		 */
		if (!empty($branch2) && $branch2 != '-1') {
			$agent2_info = User::leftJoin('users_role', 'users.id', '=', 'users_role.user_id')
					->leftJoin('users_info', 'users_info.user_id', '=', 'users.id')
					->leftJoin('roles', 'users_role.role_id', '=', 'roles.id')
					->select('users.id', 'users.email', 'users.firstname', 'users.lastname')
					->where('users_info.branch2', '=', $branch2)
					->where('roles.name', '=', ROLE_BRANCH2)
					->first();
			if (!empty($agent2_info)) {
				$mailData = [
						'receiver_firstname' => $agent2_info->firstname,
						'receiver_lastname' => $agent2_info->lastname,
						'firstname' => $user->firstname,
						'lastname' => $user->lastname,
						'email' => $user->email,
				];
				Mail::queue('emails.notify2agent', $mailData, function($message) use ($agent2_info) {
					$message->to($agent2_info->email)->subject(trans('message.mail.notify_agent2'));
				});
			}
		}
		return redirect('/auth/login1');
	}
	/*
	 * Account Register Verify
	 */
	public function getVerify($code)
	{
	    $user = User::where('verify_code', $code)->first();
	    if (!empty($user) && $code == md5($user->password)) {
			$userWallet = UserWallet::firstOrNew(['user_id' => $user->id]);
			if (empty($userWallet) || empty($userWallet['wallet_address'])) {
				/*
                 * generate baysia address, secret, ...
                 */
				$rpc_client = new BaysiaRPCHandler();
				$new_account = $rpc_client->doCreateKeys();
				$userWallet->wallet_username = '';
				$userWallet->wallet_password = '';
				$userWallet->wallet_address = $new_account['account_id'];
				$userWallet->wallet_secret = $new_account['master_seed'];
				$userWallet->wallet_secrethex = $new_account['master_seed_hex'];
				$userWallet->wallet_public = $new_account['public_key'];
				$userWallet->wallet_publichex = $new_account['public_key_hex'];
				$userWallet->active = WALLET_NOACTIVE;
				$userWallet->save();
			}
			$user->verified = 1;
			$user->save();

			// login automatically after verification
	        Auth::loginUsingId($user->id);
			// leave user's connection info
			$this->logConnInfo();
	        return redirect('/user/profile');
	    }
	    return view('auth.login')->with('verifyError', trans('message.verify_error'));
	}

	/**
	 * verify account from unknown login
	 * @param $verify_token
	 */
	public function getVerifyToken($verify_token)
	{
		$user_login_info = UserLoginInfo::where('verify_token', $verify_token)->first();
		if (isset($user_login_info['user_id']) && !empty($user_login_info['user_id']))
		{
			$user = User::where('id', $user_login_info['user_id'])->first();
			if (!empty($user))
			{
				Auth::login($user);
				$this->logConnInfo();
				return redirect('/');
			}
		}
		return view('errors.404');
	}
	/**
	 * Account Password Forgot
	 * 
	 */
	public function getForgot1()
	{
		if (Auth::check()) {
			Auth::logout();
		}
		return view('auth.newforgot');
	}

	/**
	 * Account Password Forgot
	 *
	 */
	public function getForgot()
	{
		if (Auth::check()) {
			Auth::logout();
		}
		return view('auth.forgot');
	}

	/**
	 * Account Password Forgot
	 *
	 */
	public function postForgot(Request $request)
	{
		$email = $request->only('email');
		$user = User::where('email', $email)->first();
		if ($user) {
			$mailData = [
				'token' => $user->verify_code,
				'username' => $user->firstname . $user->lastname,
				'url' => url('/auth/reset/' . $user->verify_code),
			];
			Mail::queue('emails.forgot', $mailData, function($message) use ($user) {
				$message->to($user->email)->subject(trans('message.mail.forgot'));
			});
			return view('auth.forgot')
				->withSuccess(trans('message.forgot.success'));
		}
		return redirect('/auth/forgot')
				->withErrors(['email' => trans('message.forgot.fail')]);
	}

	/**
	 * Account Password Forgot
	 *
	 */
	public function postForgot1(Request $request)
	{
		$email = $request->only('email');
		$user = User::where('email', $email)->first();
		if ($user) {
			$mailData = [
					'token' => $user->verify_code,
					'username' => $user->firstname . $user->lastname,
					'url' => url('/auth/reset/' . $user->verify_code),
			];
			Mail::queue('emails.forgot', $mailData, function($message) use ($user) {
				$message->to($user->email)->subject(trans('message.mail.forgot'));
			});
			return view('auth.newforgot')
					->withSuccess(trans('message.forgot.success'));
		}
		return redirect('/auth/forgot1')
				->withErrors(['email' => trans('message.forgot.fail')]);
	}
	/**
	 * Account Password Reset
	 * 
	 */
	public function getReset($token = null)
	{
//		print_r(bcrypt($token));exit;		
		return view('auth.reset')->with('token', $token);
	}
	
	/**
	 * Account Password Reset
	 * 
	 */
	public function postReset(Request $request)
	{
		$data = $request->all();
		$user = User::where('email', $data['email'])->first();
		if ($user && $data['token'] == md5($user->password)) {
			$user->password = bcrypt($data['password']);
			$user->verify_code = md5($user->password);
			$user->token_hash = ehash_encrypt($data['password'], EHASH_ENCRYPTION_KEY);
			$user->qr_flag = 0;
			$user->tfa_flag = 0;
			$user->save();
			return redirect('auth/login');
		}
		return view('auth.reset')
			  ->with('token', $data['token'])
			  ->with('fail', trans('message.reset.fail'));
	}
	/**
	 * Account Send Email Again
	 */
	public function getResendemail() {
		$mail_data = Session::get('mail_data', array());
		$mail = Session::get('mail', '');
		if (isset($mail_data) && isset($mail)) {
			try {
				Mail::send('emails.register', $mail_data, function ($message) use ($mail) {
					$message->to($mail)->subject(trans('message.mail.welcome'));
				});
				Session::put('mail_message', trans('message.register.mail_sent'));
			} catch(Exception $e) {
				return redirect()->back();
			}
            finally {
                return redirect('/');
            }
		}
	}

	/**
	 * Account Send Email Again
	 */
	public function getResendemail1() {
		$mail_data = Session::get('mail_data', array());
		$mail = Session::get('mail', '');
		if (isset($mail_data) && isset($mail)) {
			try {
				Mail::send('emails.register', $mail_data, function ($message) use ($mail) {
					$message->to($mail)->subject(trans('message.mail.welcome'));
				});
				Session::put('mail_message', trans('message.register.mail_sent'));
			} catch(Exception $e) {
				return redirect()->back();
			}
			finally {
				return redirect('/auth/login1');
			}
		}
	}

	/**
	 * approve business usage of currency
	 * @param Request $request
	 * @param $token
	 */
	public function approveCurrency(Request $request, $token) {
		$_approved = "0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29";
		$data = $request->only('curr', 'issuer');
		$currency = Currency::where(['name' => $data['curr'], 'issuer' => $data['issuer']])->first();

		if (empty($currency))
			return view('errors.404');
//		print_r($currency['token']);print_r('<br>');
//		print_r($token);exit;
		if ($currency['token'] == substr($token, 0, strlen($token) - 2)) {//compare the token
			$suffix = (string) (int) substr($token, strlen($token) - 2);//get the last two characters, it shows the orders of mail sent when the currency newly registered
			$approve_status = explode(',', $currency['approval_status']);

			if (strpos($_approved, $suffix) != -1 && !in_array($suffix, $approve_status)) {

				$approve_status[] = $suffix;
				$approve_status = array_filter($approve_status, function($element) {
					return $element != "" || !empty($element);
				});
				// if over the ratio of the random users approve the currency, consider the currency approved
				if (count($approve_status) >= ceil($currency['count'] / 100 * $currency['ratio'])) {
					$currency['approval'] = CURR_TOP10_APPROVED;
				}
				// sort the approval status and make it a string separated with comma
				if (sort($approve_status)) {
					$approve_status = implode(',', $approve_status);
				}
				/*if ($approve_status == $_approved) {
					$currency['approval'] = CURR_TOP10_APPROVED;
				}*/
				$currency['approval_status'] = $approve_status;
				$currency->save();
				return view('auth.agree4issue');
			}
			return view('errors.404');
		}
		return view('errors.404');
	}
	/*
	 * Get UserAgent, IP and SessionID, and insert them into UserLoginInfo DB (after Login).
	 */
	public function logConnInfo() {
		$user = Auth::user();
		$ip = $_SERVER['REMOTE_ADDR'];
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$user_login_info = UserLoginInfo::firstOrCreate(['user_id' => $user->id]);
		$user_login_info->session_id = Session::getId();
		$user_login_info->connect_ip = $ip;
		$user_login_info->user_agent = $user_agent;
		//detect browser from user agent info
		$browser = Common::fetchBrowser();
		$user_login_info->browser = $browser;
		//save connection info
		$user_login_info->save();
	}
	/*
	 * initialize IP and SessionID, and insert it into UserLoginInfo DB (before Logout).
	 */
	public function initConnInfo() {
		$user = Auth::user();
		$user_login_info = UserLoginInfo::firstOrCreate(['user_id' => $user->id]);
		$user_login_info->session_id = '';
//		$user_login_info->connect_ip = '';
		$user_login_info->save();
	}
}
