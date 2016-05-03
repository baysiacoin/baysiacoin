<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserWallet extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users_wallet';
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
	   'user_id',
       'wallet_username',
       'wallet_password',
       'wallet_balance',
       'wallet_address', 
       'wallet_secret',
	   'wallet_secrethex',
	   'wallet_public',
	   'wallet_publichex',
	   'active',
    ];
	
	
	
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
                    DB::table('users_wallet_log')->insert($data);
                } catch (Exception $e) {
                }
            } else {
                DB::table('users_wallet_log')->insert($data);
            }
        });
    }
}
