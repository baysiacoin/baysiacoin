<?php namespace App;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;




class UserInfo extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users_info';
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
	   'user_id',
       'firstname1', 
       'lastname1', 
       'company', 
       'gender', 
       'birthday', 
       'telnum', 
       'zipcode',
       'country',
       'state',        
	   'city', 
	   'address', 
	   'buildingname', 
	   'bankname', 
	   'branchname', 
	   'accounttype', 
	   'accountnumber', 
	   'accountname',
	   'fax_or_post',
	   'balance',
	   'branch1',
	   'branch2',
       'bonus1',
       'bonus2',
	   'identity_front',
	   'identity_end',       
       'fb_register',
       'licensed'
    ];
    
    
    /**
     * Get a validator for an incoming registration request for a user.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public static function validator(array $data, array $keys2remove=array(), array $rules2add=array())
     {
        $rules = [
            'applicant_name' => 'required|max:255',
            'fund_amount' => 'required|numeric',
            'withdraw_amount' => 'required|numeric|min:0',
            // 'withdraw_amount' => 'required|numeric|min:0|max:1000',
//            'ripple_address' => 'required|max:255',
//            'ripple_secret' => 'required|max:255',
        ];
            
        $rules = array_except($rules, $keys2remove);
        
        foreach($rules2add as $key => $value)
            $rules[$key] = $value;
        
        $validator = Validator::make($data, $rules);
        return $validator;
    }
	
	
    /**
     * Get ripple info
     *
     * @author FireDragon
     * @since  2015/07/11
     * @param  int $user_id
     * @return array
     */
    public function getRipple($user_id)
    {
        if (empty($user_id)) {
            return array();
        }
        $userinfo = Userinfo::where('user_id', '=', $user_id)->first();
        return array($userinfo->ripple_address, $userinfo->ripple_secret);
    }

	
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

            // Insert log
            if (App::environment('production')) {
                try {
                    DB::table('users_info_log')->insert($data);
                } catch (Exception $e) {
                }
            } else {
                DB::table('users_info_log')->insert($data);
            }
        });
    }
}
