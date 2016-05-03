<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		{{ App::setLocale('en') }}
		{{ trans('mail.notice.hello') }}{{ $receiver_name }}{{ trans('mail.notice.san') }}!<br>
		<br>
		{{ trans('mail.notice.thanks_using_bsc') }}<br>
		<br>
		{{ trans('mail.notice.this_time') }}"{{ $sender_name }}"{{ trans('mail.notice.user_requested_issue') }}<br>
		{{ trans('mail.notice.let_you_jury') }}<br>
		{{ trans('mail.notice.part_of_policy') }}<br>
		{{ trans('mail.notice.period_limit') }}<br>
		<br>
		"{{ $sender_name }}"{{ trans('mail.notice.san') }}{{ trans('mail.notice.gonna_issue') }}<br>
		{{ trans('mail.notice.comment_here') }}<br>
		<br>
		------------------------------------------------------------<br>
		{!! nl2br($content) !!}<br>
		------------------------------------------------------------<br>
		<br>
		{{ trans('mail.notice.currency') }} 		: {{ $currency }}<br>
		{{ trans('mail.notice.issuer_address') }}  : {{ $issuer_address }}<br>
		<br>
		{{ trans('mail.notice.agree_click') }}<br>
		<br>
		URL : {{ $url }}<br>
		<br>
		<br>
		{{ App::setLocale('ja') }}
		{{ trans('mail.notice.hello') }}{{ $receiver_name }}{{ trans('mail.notice.san') }}!<br>
		<br>
		{{ trans('mail.notice.thanks_using_bsc') }}<br>
		<br>
		{{ trans('mail.notice.this_time') }}"{{ $sender_name }}"{{ trans('mail.notice.user_requested_issue') }}<br>
		{{ trans('mail.notice.let_you_jury') }}<br>
		{{ trans('mail.notice.part_of_policy') }}<br>
		{{ trans('mail.notice.period_limit') }}<br>
		<br>
		"{{ $sender_name }}"{{ trans('mail.notice.san') }}{{ trans('mail.notice.gonna_issue') }}<br>
		{{ trans('mail.notice.comment_here') }}<br>
		<br>
		------------------------------------------------------------<br>
		{!! nl2br($content) !!}<br>
		------------------------------------------------------------<br>
		<br>
		{{ trans('mail.notice.currency') }} 		: {{ $currency }}<br>
		{{ trans('mail.notice.issuer_address') }}  : {{ $issuer_address }}<br>
		<br>
		{{ trans('mail.notice.agree_click') }}<br>
		<br>
		URL : <a href="{{ $url }}">{{ $url }}</a><br>
		<br>
		<br>
		{{--{{ App::setLocale('cn') }}--}}
		{{--{{ trans('mail.notice.hello') }}{{ $receiver_name }}{{ trans('mail.notice.san') }}!<br>--}}
		{{--<br>--}}
		{{--{{ trans('mail.notice.thanks_using_bsc') }}<br>--}}
		{{--<br>--}}
		{{--{{ trans('mail.notice.this_time') }}"从{{ $sender_name }}"{{ trans('mail.notice.user_requested_issue') }}<br>--}}
		{{--{{ trans('mail.notice.let_you_jury') }}<br>--}}
		{{--{{ trans('mail.notice.part_of_policy') }}<br>--}}
		{{--{{ trans('mail.notice.period_limit') }}<br>--}}
		{{--<br>--}}
		{{--"{{ $sender_name }}"{{ trans('mail.notice.san') }}{{ trans('mail.notice.gonna_issue') }}<br>--}}
		{{--{{ trans('mail.notice.comment_here') }}<br>--}}
		{{--<br>--}}
		{{--------------------------------------------------------------<br>--}}
		{{--{!! nl2br($content) !!}<br>--}}
		{{--------------------------------------------------------------<br>--}}
		{{--<br>--}}
		{{--{{ trans('mail.notice.currency') }} 		: {{ $currency }}<br>--}}
		{{--{{ trans('mail.notice.issuer_address') }}  : {{ $issuer_address }}<br>--}}
		{{--<br>--}}
		{{--{{ trans('mail.notice.agree_click') }}<br>--}}
		{{--<br>--}}
		{{--URL : {{ $url }}<br>--}}
		{{--<br>--}}
		{{--<br>--}}
		=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-<br>
		Baysiacoin<br>
		Baysia Global Holdings Co.,Limited<br>
		Prosperity Millennia Plaza 2nd Floor, 663 King's Road, Quarry Bay<br>
		=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-<br>
	</body>
</html>