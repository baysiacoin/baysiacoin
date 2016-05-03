<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use App\UserInfo;
use App\UserWallet;
use CountryState;

class UserController extends Controller {

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
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return redirect('/user/profile');
	}
	
	/**
	 * User Profile Get
	 * 
	 */
	public function getProfile($page="profile")
	{
		$user = Auth::user();
        $userInfo = UserInfo::where('user_id', $user->id)->first();
        $userWallet = UserWallet::where('user_id', $user->id)->first();
        $data = array_merge($user['attributes'], $userInfo['attributes'], isset($userWallet['attributes']) ? $userWallet['attributes'] : array());
		$data['birthday'] = str_replace('-', '/', $data['birthday']);
        $data['page'] = $page;
		$data['usersinfo'] = $userInfo;
        if(Session::has('from_facebook') && Session::get('from_facebook') == true && Session::get('fb_init_pwd') == ehash_decrypt($data['token_hash'], EHASH_ENCRYPTION_KEY)) {
            $data['from_facebook'] = true;
            $data['fbInitPwd'] = Session::get('fb_init_pwd');
        }
        if(empty($userInfo['licensed']) || $userInfo['licensed'] != USER_LICENSE_CHECKED) {
            Session::put('licensed', USER_LICENSE_UNCHECKED);
        } else {
            if (Session::has('licensed')) {
                Session::forget('licensed');
            }
        }

		return view('user.profile', $data);
	}
	/**
	 * request for sms
	 */
	public function postSmsRequest() {
		$user = Auth::user();
		$user_info = UserInfo::where('user_id', $user->id)->first();

		if (empty($user_info['telnum'])) {
			echo json_encode([
				'result' => 'Fail',
				'reason' => 'PHONE_NOT_SET'
			]);
		} else if (empty($user_info['country'])) {
			echo json_encode([
				'result' => 'Fail',
				'reason' => 'COUNTRY_NOT_SET'
			]);
		} else if (empty($user->getAuthyId())) {
			if ($user->registerSmsAuthy($user_info['telnum'], $user_info['country'])) {
				if ($user->sendSmsToken()) {
					echo json_encode(['result' => 'Success']);
				} else {
					echo json_encode([
						'result' => 'Fail',
						'reason' => 'FAIL_TO_SEND'
					]);
				}
			} else {
				echo json_encode([
					'result' => 'Fail',
					'reason' => 'FAIL_TO_REGISTER'
				]);
			}
		} else {
			if ($user->sendSmsToken()) {
				echo json_encode(['result' => 'Success']);
			} else {
				echo json_encode([
					'result' => 'Fail',
					'reason' => 'FAIL_TO_SEND'
				]);
			}
		}
		exit;
	}
	/**
	 * verify sms, return secret key
	 */
	public function postVerifySms(Request $request) {
		if (!Auth::check()) {
			echo json_encode(['result' => 'Fail']);
			exit;
		}
		$user = Auth::user();
		$data = $request->only('code');

		if ($user->verifySmsToken($data['code'])) {
			$wallet_secret = UserWallet::where('user_id', $user->id)->first()['wallet_secret'];
			echo json_encode(['result' => 'Success', 'secret' => $wallet_secret]);
			exit;
		}
		echo json_encode(['result' => 'Fail']);
		exit;
	}

	/**
	 * User Profile Post
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function postProfile(Request $request)
	{
		$user = Auth::user();
		$userInfo = UserInfo::where('user_id', $user->id)->first();
		$userWallet = UserWallet::where('user_id', $user->id)->first();
	    $userData = array_merge($user['attributes'], $userInfo['attributes']);
		$data = $request->all();
        if(Session::has('from_facebook')) {
           $data['from_facebook'] = true;
        }
        if(Session::has('fb_init_pwd')) {
            $data['fbInitPwd'] = Session::get('fb_init_pwd');
        }
        /*
        ** Convert telnum from zen-kaku to han-kaku if exists.
        */
		if (isset($data['telnum'])) {
			$data['telnum'] = mb_convert_kana($data['telnum'], 'a');
			$data['telnum'] = str_replace('ー', '-', $data['telnum']);
		}
    	/*
        ** Convert zipcode from zen-kaku to han-kaku if exists.
        */
		if (isset($data['zipcode'])) {
			$data['zipcode'] = mb_convert_kana($data['zipcode'], 'a');
			$data['zipcode'] = str_replace('ー', '-', $data['zipcode']);
		}
    	
		if ($data['page'] == 'profile')
		{
			//remember original tfa_status
			$org_tfa_flag = $user->tfa_flag;

			$birthdayBefore = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d"), date("Y") - 18));
			if (($locale = Session::get('locale', 'ja')) == 'ja') {
				$validator = Validator::make($data, [
					// 'firstname' => 'required',
					// 'lastname' => 'required',
					// 'firstname1' => 'required',
					// 'lastname1' => 'required',
					'birthday' => 'before:' . $birthdayBefore,
					'telnum' => ['regex:' . REGEX_TELNUM, 'min:8'],
					'zipcode' => 'regex:' . REGEX_ZIPCODE,
					// 'country' => 'required',
					// 'state' => 'required',
					// 'city' => 'required',
					// 'address' => 'required',
				]);
			} else if ($locale == 'en' || $locale == 'cn') {
				$validator = Validator::make($data, [
					// 'firstname' => 'required',
					// 'lastname' => 'required',
					'birthday' => 'before:' . $birthdayBefore,
					'telnum' => ['regex:' . REGEX_TELNUM],
					'zipcode' => 'regex:' . REGEX_ZIPCODE,
					// 'country' => 'required',
					// 'state' => 'required',
					// 'city' => 'required',
					// 'address' => 'required'
				]);
			}
			$validator->sometimes(['telnum', 'country'], 'required', function($input) {
				// when using sms_auth
				return $input['tfa_auth'] == 2;
			});
			/*$validator->sometimes(['avatar', 'avatar2'], 'required', function() {
				$user = Auth::user();
				$userInfo = UserInfo::where('user_id', $user->id)->first();
				return empty($userInfo['identity_front']) || empty($userInfo['identity_front']);
			});*/
			if ($validator->fails()){
				$errors = $validator->errors();
				return redirect()->back()->withErrors($errors);
			}
			//$identity_front = '';
			//$identity_end = '';
			if (Input::hasFile('avatar')) {
				$avatar = Input::file('avatar');
				$ext = $avatar->getClientOriginalExtension();
				$destinationPath = $userInfo->licensed == 1 ? 'upload/identity/' : 'upload/identity/tmp/';
				$fileName = time() . '.' . $ext;
				if (in_array($ext, Config::get('conf.cert_file_kinds'))) {
					if ($avatar->move($destinationPath, $fileName)) {
						$identity_front = $fileName;
						$user->upload_flag = FIRST_TIME_UPLOADED;
						$userInfo->licensed = 0;
					}
				}
			}
			if (Input::hasFile('avatar2')) {
				$avatar2 = Input::file('avatar2');
				$ext = $avatar2->getClientOriginalExtension();
				$destinationPath = $userInfo->licensed == 1 ? 'upload/identity/' : 'upload/identity/tmp/';
				$fileName = time() . '2.' . $ext;
				if (in_array($ext, Config::get('conf.cert_file_kinds'))) {
					if ($avatar2->move($destinationPath, $fileName)) {
						$identity_end = $fileName;
						$user->upload_flag = FIRST_TIME_UPLOADED;
						$userInfo->licensed = 0;
					}
				}
			}
			/*
			 * tfa settings
			 */
			$qr_auth = $sms_auth = 0;

			if (isset($data['tfa_use'], $data['tfa_auth']) && $data['tfa_use'] == 1) {
				switch ($data['tfa_auth']) {
					case 1:
						$qr_auth = 1;
						break;
					case 2:
						$sms_auth = 1;
						break;
					default:
						$qr_auth = 1;
						break;
				}
			}
			/*
			**register auth_id if not registered
			*/
			if ($sms_auth == 1) {
				 $sms_auth = $user->registerSmsAuthy($data['telnum'], $data['country']);
				//$sms_auth = $user->registerSmsAuthy();
				if (!$sms_auth) {																
					return redirect()->back()->withErrors(['fail_recommend' => trans('message.tfa_auth.sms_fail_recommend')]);
				}
			} else {
				$user->setAuthyId("");
			}
			$user->qr_flag = $qr_auth;
			$user->tfa_flag = $sms_auth;
			$user->firstname = $data['firstname'];
			$user->lastname = $data['lastname'];
			$user->save();
			/*
			 * suppose he passed the verification when newly change the auth settings
			 */
			if ($user->qr_flag == 1 || $user->tfa_flag == 1) {
				Session::put('user_authed', true);
			} else {
				Session::forget('user_authed');
			}

			if ($locale == 'ja') {
				$userInfo->firstname1 = $data['firstname1'];
				$userInfo->lastname1 = $data['lastname1'];
			}
			$userInfo->gender = $data['gender'];
			$userInfo->birthday = $data['birthday'];
			$userInfo->telnum = $data['telnum'];
			$userInfo->company = $data['company'];
			$userInfo->buildingname = $data['buildingname'];
			$userInfo->zipcode = $data['zipcode'];
			$userInfo->country = $data['country'];
			$userInfo->state = isset($data['state']) ? $data['state'] : '';
			$userInfo->city = $data['city'];
			$userInfo->address = $data['address'];
			$userInfo->fax_or_post = isset($data['fax_or_post']) ? $data['fax_or_post'] : 0;
			if (isset($identity_front) && !empty($identity_front))
				$userInfo->identity_front = $identity_front;
			if (isset($identity_end) && !empty($identity_end))
				$userInfo->identity_end = $identity_end;
			$userInfo->save();
			/*
			 * send notification to admin's email address when user uploads a pair of files
			 */
//			if (!empty($userInfo->identity_front) && !empty($userInfo->identity_end)) {
			if ($user->upload_flag == FIRST_TIME_UPLOADED) {
				$admin_email = Config::get('mail.username');  //info@japan.baysiacoin.com
				// $admin_email = 'kihm0426@hotmail.com';
				$mailData = [
					'type' => UPLOAD_NOTIFICATION,
					'firstname' => $user->firstname,
					'lastname' => $user->lastname,
					'email' => $user->email,
					'identity_front' => $userInfo->identity_front,
					'identity_end' => $userInfo->identity_end,
				];
				Mail::queue('emails.notify2admin', $mailData, function($message) use ($admin_email) {
					$message->to($admin_email)->subject(trans('message.mail.notify_upload_to_admin'));
				});
				//change upload status
				$user->upload_flag = NEW_UPLOADED;
				$user->save();
			}
			/*
			 * send notification to admin's email when user sets sms_auth
			 */
			if ($org_tfa_flag != $user->tfa_flag && $user->tfa_flag == 1) {
				$admin_email = Config::get('mail.username');  //info@japan.baysiacoin.com
//				$admin_email = 'kihm0426@hotmail.com';
				$mailData = [
						'type' => SMS_AUTH_NOTIFICATION,
						'firstname' => $user->firstname,
						'lastname' => $user->lastname,
						'email' => $user->email
				];
				Mail::queue('emails.notify2admin', $mailData, function($message) use ($admin_email) {
					$message->to($admin_email)->subject(trans('message.mail.notify_sms_use_to_admin'));
				});
			}
			return redirect('/user/profile')->with('profile_save_success', trans('message.save_success'));
		}
		else if ($data['page'] == 'bank')
		{
			$validator = Validator::make($data, [
				'bankname' => 'required',
				'branchname' => 'required',
				'accounttype' => 'required',
				'accountnumber' => 'required|max:9999999|regex:' . REGEX_ACCOUNTNUMBER,
				'accountname' => 'required',
			]);
			
			if ($validator->fails()){
				$data['username'] = $userData['username'];
				$data['company'] = $userData['company'];
				$data['firstname'] = $userData['firstname'];
				$data['lastname'] = $userData['lastname'];
				$data['firstname1'] = $userData['firstname1'];
				$data['lastname1'] = $userData['lastname1'];
				$data['gender'] = $userData['gender'];
				// $data['birthday'] = $userData['birthday'];
			    $data['birthday'] = str_replace('-', '/', $userData['birthday']);
				$data['telnum'] = $userData['telnum'];
				$data['zipcode'] = $userData['zipcode'];
				$data['state'] = $userData['state'];
				$data['city'] = $userData['city'];
				$data['address'] = $userData['address'];
				$data['buildingname'] = $userData['buildingname'];
                $data['email'] = $userData['email'];
                $data['usersinfo'] = $userInfo;
			    $data['page'] = 'bank';
			   	$data['errors'] = $validator->errors();
				
//				return view('user.profile', $data);
				return redirect('/user/profile/bank')->withInput()->withErrors($validator);
			}
			
			$userInfo->bankname = $data['bankname'];
			$userInfo->branchname = $data['branchname'];
			$userInfo->accounttype = $data['accounttype'];
			$userInfo->accountnumber = $data['accountnumber'];
			$userInfo->accountname = $data['accountname'];
			$userInfo->save();
			return redirect('/user/profile/bank')->with('profile_save_success', trans('message.save_success'));
		}
		else if ($data['page'] == 'password')
		{
			$validator = Validator::make($data, [
				'old_password' => 'required',
				'new_password' => 'required|confirmed|min:8',
			]);
			if ($validator->fails()){
				$data['username'] = $userData['username'];
				$data['company'] = $userData['company'];
				$data['firstname'] = $userData['firstname'];
				$data['lastname'] = $userData['lastname'];
				$data['firstname1'] = $userData['firstname1'];
				$data['lastname1'] = $userData['lastname1'];
				$data['gender'] = $userData['gender'];
				$data['birthday'] = $userData['birthday'];
			    $data['birthday'] = str_replace('-', '/', $data['birthday']);
				$data['telnum'] = $userData['telnum'];
				$data['zipcode'] = $userData['zipcode'];
				$data['state'] = $userData['state'];
				$data['city'] = $userData['city'];
				$data['address'] = $userData['address'];
				$data['buildingname'] = $userData['buildingname'];
				$data['bankname'] = $userData['bankname'];
				$data['branchname'] = $userData['branchname'];
				$data['accounttype'] = $userData['accounttype'];
				$data['accountnumber'] = $userData['accountnumber'];
				$data['accountname'] = $userData['accountname'];
                $data['email'] = $userData['email'];
			    $data['usersinfo'] = $userInfo;
			    $data['page'] = 'password';			    
				$data['errors'] = $validator->errors();
				//return view('user.profile', $data);
				return redirect('/user/profile/password')->withErrors($validator);
				//return redirect('/user/profile/password')->withInput()->withErrors($validator);
			}
			if (!Hash::check($data['old_password'], $user->password))
			//if (bcrypt($data['old_password']) != $user->password)
			{
				$validator->messages()->add("old_password", trans('message.profile.old_password_error'));
				return redirect('/user/profile/password')->withInput()->withErrors($validator);
			}
			$user->password = bcrypt($data['new_password']);
			$user->verify_code = md5($user->password);

			// ----------------- added by PGC 2015/06/09 start -----------------
			require_once app_path().'/Libraries/EHash.php';
			$user->token_hash = ehash_encrypt($data['new_password'], EHASH_ENCRYPTION_KEY);
			// ----------------- added by PGC 2015/06/09 end -----------------
				
			$user->save();

            if(Session::has('from_facebook')) {
                Session::forget('from_facebook');
            }
            if(Session::has('fb_init_pwd')) {
                Session::forget('fb_init_pwd');
            }
            return redirect('/user/profile/password')->with('profile_save_success', trans('message.save_success'));
		}
		
		App::abort(404, trans('message:404_error'));
	}
	/**
	 * get states of the country given on ISO3661_2 syntax, two characters
	 */
	public function getCountryStates($code_3661_2) {
		$states = Config::get('countrystate_lang.states');
		foreach ($states[$code_3661_2] as $alpha => $name) {
			if (empty($name)) {
				$name = $alpha;
			}
			$result[$alpha] = $name;
		}
		echo json_encode($result);
		//echo json_encode(CountryState::getStates($code_3661_2));
		exit;
	}
}
