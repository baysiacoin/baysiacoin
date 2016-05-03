<?php namespace App\Console\Commands;

use App\Currency;
use App\CurrencyContent;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CheckCurrencyStatus extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'currency:check';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Check currency approval status.';

	/**
	 * time from the currency registered
	 * @var int
	 */
	protected $timeout = 259200;// 3days(unit 1s)

	/**
	 * Create a new command instance.
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		//
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			//['example', InputArgument::REQUIRED, 'An example argument.'],
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
			['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
		];
	}
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		// should register the shell below to crontab, crontab -e
		// * * * * * php /...path.../artisan schedule:run 1>> /dev/null 2>&1
		// * * * * * php /var/www/html/baysiacoin-app50/baysiacoin-app/artisan schedule:run 1>> /dev/null 2>&1

		Log::info('Schedule: Checking currency status...');

		$pattern = "0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29";
		$pattern = explode(',', $pattern);
		$currencies = Currency::where(['type' => 1, 'approval' => 0])->get();//get the array of business purpose currencies
		foreach ($currencies as $currency) {
			$now = time();
			$mail_sent_at = strtotime($currency->mail_sent_at);
			$this->timeout = 24 * 3600 * $currency->period;

			if ($now - $mail_sent_at > $this->timeout) {// if the time is over 3 days
				$orders = explode(',', $currency['approval_status']);
				$diff = array_values(array_diff($pattern, $orders));
				$count = count($diff);
				if ($count > 0) {
					/*
                     * rebuild token
                     */
					$currency->token = md5($currency->issuer . time());
					$currency->mail_sent_at = date('Y-m-d h:i:s', time());
					$currency->save();
					/*
                     * get mail data
                     */
					$curr_content = CurrencyContent::where('curr_id', $currency->id)->first();
					$user = new User;
					$random_users = $user->getRandomUsers($count);
					/*
					 * log users will be received mail
					 */
					Log::info($random_users);
					/*
					 * consist mail data
					 */
					$mailData = [
							'sender_name' => $curr_content['sender_name'],
							'content' => $curr_content['content'],
							'currency' => $currency->name,
							'issuer_address' => $currency->issuer,
							'url' => url('/issue/agree/' . $currency->token),
					];
					$i = 0;
					foreach ($random_users as $name => $email) {
						$mailData['receiver_name'] = $name;
						$mailData['url'] = url('/issue/agree/' . $currency->token . str_pad($diff[$i], 2, 0, STR_PAD_LEFT) . '?curr=' . $currency->name . '&issuer=' . $currency->issuer);
						Mail::queue('emails.notify4issue', $mailData, function ($message) use ($email, $curr_content) {
							$message->to($email)->subject($curr_content['subject']);
						});
						$i++;
					}
					/*
					 * send me this mail
					 */
					$email = 'nika90426@gmail.com';
					$mailData['receiver_name'] = 'Kihm';
					$mailData['url'] = url('/issue/agree/' . $currency->token . '?curr=' . $currency->name . '&issuer='. $currency->issuer);
					Mail::queue('emails.notify4issue', $mailData, function($message) use ($email, $curr_content) {
						$message->to($email)->subject($curr_content['subject']);
					});
				}
			}
		}
	}
}
