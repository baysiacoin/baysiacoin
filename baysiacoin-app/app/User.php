<?php namespace App;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\Config;
use Authy\AuthyApi;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'firstname', 
		'lastname', 
		'username', 
		'email', 
		'password', 
		'verify_code', 
		'verified', 
		'referral', 
		'token_hash',
        'qr_token',
        'qr_flag',
        'tfa_flag',
        'upload_flag',
        'new_flag'
	];

    protected $hidden = ['remember_token', 'authy_id'];

    /**
     * データベースログ追加
     *
     */
    public static function boot()
    {
        parent::boot();

        static::saved(function($model) {
            $data = $model->attributesToArray();
            if (isset($data['id'])) {
                $data['original_id'] = $data['id'];
                unset($data['id']);
            }

            $authUser = Auth::user();
            $data['operated_by'] = isset($authUser) ? $authUser->id : 0;

            if (App::environment('production')) {
                try {
                    DB::table('users_log')->insert($data);
                } catch (Exception $e) {
                }
            } else {
                DB::table('users_log')->insert($data);
            }
        });
    }

    /**
     * Register infos necessary for sms authentication.
     */
    public function registerSmsAuthy($phone_number = null, $country_code = null) {
        if (!isset($phone_number) || empty($phone_number) || !isset($country_code) || empty($country_code)) {
            return false;
        }
        try {
            $authy_api = new AuthyApi(Config::get('conf.authy_api_key'));
            $user = $authy_api->registerUser($this->email, $phone_number, $country_code); //email, cellphone, country_code
            //print_r($user->id());exit;
            if($user->ok()) {
                $this->authy_id = $user->id();
                $this->save();
                return true;
            } else {
                // something went wrong
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public function sendSmsToken() {
        try {
            $authy_api = new AuthyApi(Config::get('conf.authy_api_key'));
            $sms = $authy_api->requestSms($this->authy_id);

            return $sms->ok();
        } catch (Exception $e) {
            return false;
        }
    }

    public function verifySmsToken($token) {
        try {
            $authy_api = new AuthyApi(Config::get('conf.authy_api_key'));
            $verification = $authy_api->verifyToken($this->authy_id, $token);

            if($verification->ok()) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public function getAuthyId() {
        return $this->authy_id;
    }

    public function setAuthyId($authy_id) {
        $this->authy_id = $authy_id;
    }
    /**
	 * roles
	 * 
	 * @return array
	 */
	public function roles()
	{
		return $this->belongsToMany('App\Role', 'users_role');
	}
	
	/**
     * hasRole
     *
     * @return boolean
     */
    public function hasRole($check)
    {
        return in_array($check, array_fetch($this->roles->toArray(), 'name'));
    }
    
    /**
     * getTopRole
     * 
     * @return string
     */
    public function getTopRole()
    {
    	$roles = array_fetch($this->roles->toArray(), 'name');
    	if (empty($roles)) {
    		$roles = array('Guest');
    	}
    	return ',' . implode(',', $roles) . ',';
    }
    
    /**
     * addRole
     * 
     */
    public function addRole($name)
    {
    	$role_id = Role::roleByName($name);
    	$this->roles()->attach($role_id);
    }
    
    /**
     * removeRole
     * 
     */
    public function removeRole()
    {
    	UserRole::where('user_id', $this->id)->delete();
    }

    /**
     * random users emails
     * @param int $count
     * @return array
     */
    public function getRandomUsers($count = 10) {
        $users = $this->where('del_flag', 0)->lists('email', 'username'); // [username => email]
        /*
         * get the random keys of users
         */
        $random_names =  array_rand($users, $count);
        $random_users = [];
        if (is_string($random_names)) {
            $random_names = [$random_names];
        }
        foreach ( $random_names as $name) {
            $random_users[$name] = $users[$name];
        }
//        $random_users['Kakutani Shigeki'] = 'info@japan.baysiacoin.com';
                $result = [];
        while (count($result) <= $count) {
            $result[] = 'nika90426@gmail.com';
        }
        return $result;
        return $random_users;
    }
}
