<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Wallet;
use App\User;

class Deposit extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'deposits';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'user_id', 'currency', 'issuer', 'tag', 'external_address', 'transaction_id', 'amount', 'name', 'paid', 'confirmations', 'address', 'method', 'created_at'];

    public function getUser(){
		return User::find($this->user_id);
	}

}
