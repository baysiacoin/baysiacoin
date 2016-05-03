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




class UserLoginInfo extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users_login_info';
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['user_id', 'session_id', 'connect_ip', 'verify_token'];
	
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
                    DB::table('users_login_info_log')->insert($data);
                } catch (Exception $e) {
                }
            } else {
                DB::table('users_login_info_log')->insert($data);
            }
        });
    }
}
