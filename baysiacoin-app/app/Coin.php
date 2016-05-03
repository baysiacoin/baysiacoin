<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Coin extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'coins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'type', 'market', 'coin_address', 'coin_tag', 'coin_currency', 'coin_amount', 'relative_id'];

	public function matchWithDeposits() {
		$initCoins = Coin::where(
			['type' => COIN_FUND,
			'status' => COIN_INIT,
			'relative_id' => 0,
			]
		)->get();

		foreach ($initCoins as $initCoin) {
			if (!empty($initCoin->coin_tag)) {
				$deposits = Deposit::where([
					'external_address' => $initCoin->coin_address,
					'tag' => $initCoin->coin_tag,
					'amount' => $initCoin->coin_amount,
					'currency' => $initCoin->coin_curency,
					'paid' => DEPOSIT_REQUESTED,
				])->orderBy('id', 'desc')->take(1)->get();

				if (!$deposits->isEmpty()) {
					$initCoin->relative_id = $deposits[0]->id;
					$deposits[0]->paid = DEPOSIT_CONFIRMED;
					$deposits[0]->save();
				}
			}
			// find with address
			if (empty($initCoin->relative_id)) {
				$deposits = Deposit::where(
					['external_address' => $initCoin->coin_address,
					'amount' => $initCoin->coin_amount,
					'currency' => $initCoin->coin_currency,
					'paid' => DEPOSIT_REQUESTED,
					]
				)->orderBy('id', 'desc')->take(1)->get();
				if (!$deposits->isEmpty()) {
					$initCoin->relative_id = $deposits[0]->id;
					$deposits[0]->paid = DEPOSIT_CONFIRMED;
					$deposits[0]->save();
				}
			}
			if (!empty($initCoin->relative_id)) {
				$initCoin->status = COIN_COMPLETE;
			} else {
				$initCoin->status = COIN_ERROR;
			}
			
			$initCoin->save();
		}
	}
	public function matchWithWithdraws() {
		$initCoins = Coin::where([
			'type' => COIN_WITHDRAW,
			'status' => COIN_INIT,
			'relative_id' => 0,
		])->get();

		foreach ($initCoins as $initCoin) {
			if (!empty($initCoin->coin_tag)) {
//				print_r($initCoin->coin_tag);print_r('<br>');
//				print_r($initCoin->coin_amount);print_r('<br>');
//				print_r($initCoin->coin_currency);print_r('<br>');
//				print_r($initCoin->coin_address);print_r('<br>');
//				exit;
				$withdraws = Withdraw::where([
					'external_address' => $initCoin->coin_address,
					'tag' => $initCoin->coin_tag,
					'amount' => $initCoin->coin_amount,
					'currency' => $initCoin->coin_currency,
					'status' => WITHDRAW_REQUESTED,
				])->orderBy('id', 'desc')->take(1)->get();

				if (!$withdraws->isEmpty()) {
					$initCoin->relative_id = $withdraws[0]->id;
					$withdraws[0]->status = WITHDRAW_CONFIRMED;
					$withdraws[0]->save();
				}
			}
			// find with address
			if (empty($initCoin->relative_id)) {
				$withdraws = Withdraw::where([
					'external_address' => $initCoin->coin_address,
					'amount' => $initCoin->coin_amount,
					'currency' => $initCoin->coin_curency,
					'status' => WITHDRAW_REQUESTED,
				])->orderBy('id', 'desc')->take(1)->get();

				if (!$withdraws->isEmpty()) {
					$initCoin->relative_id = $withdraws[0]->id;
					$withdraws[0]->status = WITHDRAW_CONFIRMED;
					$withdraws[0]->save();
				}
			}
			if (!empty($initCoin->relative_id)) {
				$initCoin->status = COIN_COMPLETE;
			} else {
				$initCoin->status = COIN_ERROR;
			}

			$initCoin->save();
		}
	}
}
