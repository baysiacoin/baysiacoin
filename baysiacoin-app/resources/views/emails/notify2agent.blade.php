<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		{{ trans('mail.notice.hello') }}
		@if (Session::get('locale') == 'en')
			{{ $receiver_lastname }}&nbsp;{{ $receiver_firstname }}
		@else
			{{ $receiver_firstname }}&nbsp;{{ $receiver_lastname }}
		@endif
		{{ trans('mail.notice.san') }}!<br>
		<br>
		<!-- New member registration notice -->
		@if (!isset($type) || empty($type))
			"{{ $firstname }} {{ $lastname }}"{{ trans('mail.notice.registered') }}<br>
			{{ trans('mail.notice.member_info') }}<br>
			------------------------------------------------------------<br>
			{{ trans('mail.notice.name') }}　:　{{ $firstname }}　{{ $lastname }}<br>
			{{ trans('mail.notice.mail_address') }}　:　{{ $email }}<br>
			------------------------------------------------------------<br>
			{{ trans('mail.notice.ask_future') }}<br>
			{{ trans('mail.notice.contactus') }}<br>
		<!-- New deposit request coming notice -->
		@elseif ($type == DEPOSIT_REQ_NOTIFICATION)
			"{{ $name }}"&nbsp;{{ trans('mail.notice.deposit.requested') }}<br>
			{{ trans('mail.notice.deposit.information') }}<br>
			<br>
			{{ trans('mail.notice.deposit.amount') }}:&nbsp;{{ $amount }}<br>
			{{ trans('mail.notice.deposit.currency') }}:&nbsp;{{ $currency }}<br>
		@elseif ($type == WITHDRAW_REQ_NOTIFICATION)
			"{{ $name }}"&nbsp;{{ trans('mail.notice.withdrawal.requested') }}<br>
			{{ trans('mail.notice.withdrawal.information') }}<br>
			<br>
			{{ trans('mail.notice.withdrawal.amount') }}:&nbsp;{{ $amount }}<br>
			{{ trans('mail.notice.withdrawal.currency') }}:&nbsp;{{ $currency }}<br>
		@endif
		<br>
		=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-<br>
		Baysiacoin<br>
		Baysia Global Holdings Co.,Limited<br>
		Prosperity Millennia Plaza 2nd Floor, 663 King's Road, Quarry Bay<br>
		=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-<br>
    </body>
</html>