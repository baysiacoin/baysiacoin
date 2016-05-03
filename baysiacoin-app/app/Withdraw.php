<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Wallet;
use App\User;

class Withdraw extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'withdraws';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'user_id', 'currency', 'issuer', 'tag', 'external_address', 'amount', 'fee_amount', 'receive_amount', 'name', 'confirmation_code', 'status', 'transaction_id', 'accountname', 'created_at'];

    public function getUser() {
		return User::find($this->user_id);
	}    
    
}
