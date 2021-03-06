<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Currency extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'currencies';
	
	/**
	 * The attributes that are mass assignable.
     *
	 * {id, name, description, issuer,
     * type 0: personal usage 1: economic usage,
     * count: the count of users for personal usage limit,
	 * ratio: the ratio of the approvals
	 * period: limit period for waiting or resending email
     * approval: the flag represent the top 10 BSC owners}
     *
	 * @var array
	 */
	protected $fillable = ['id', 'name', 'description', 'issuer', 'type', 'approval', 'count', 'ratio', 'period', 'token', 'approval_status', 'created_at', 'updated_at', 'mail_sent_at'];
	
	/**
	 * データベースログ追加
	 *
	 */
/*	public static function boot()
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
    }*/
}
