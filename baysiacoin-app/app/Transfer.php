<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'transfers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'sender_id', 'receiver_id', 'currency', 'transaction_id', 'amount', 'paid', 'created_at'];
}
