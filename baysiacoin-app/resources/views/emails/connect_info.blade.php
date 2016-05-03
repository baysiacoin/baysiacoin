<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		@if (($locale = Session::get('locale')) == 'en')
	    {{ trans('mail.notice.hello') }}{{ $lastname }}&nbsp;{{ $firstname }}{{ trans('mail.notice.san') }}!<br>
		@else
		{{ trans('mail.notice.hello') }}{{ $firstname }}&nbsp;{{ $lastname }}{{ trans('mail.notice.san') }}!<br>
		@endif
		<br>
		{{ trans('mail.notice.unknown_browser') }}<br>
		{{ trans('mail.notice.confirm_below') }}<br>
		<br>
		{{ trans('mail.notice.time') }}:&nbsp;{!! $time or '' !!}<br>
		{{ trans('mail.notice.ip') }}:&nbsp;{{ $ip or '' }}<br>
		{{ trans('mail.notice.browser') }}:&nbsp;{{ $browser or '' }}<br>
		{{ trans('mail.notice.user_agent') }}:&nbsp;{{ $user_agent or '' }}<br>
		<br>
		{{ trans('mail.notice.verify_url') }}:&nbsp;<a href="{{ $url or '' }}">{{ $url or '' }}</a><br>
		<br>
		{{--{{ trans('mail.notice.steal_recommend') }}<br>--}}
		{{--<br>--}}
		{{ trans('mail.notice.baysia_address') }}:&nbsp;{{ $wallet_address or '' }}<br>
		<br>
		=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-<br>
		Baysiacoin<br>
		Baysia Global Holdings Co.,Limited<br>
		Prosperity Millennia Plaza 2nd Floor, 663 King's Road, Quarry Bay<br>
		=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-<br>
    </body>
</html>