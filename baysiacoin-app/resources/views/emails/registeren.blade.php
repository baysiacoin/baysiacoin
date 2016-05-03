<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		Hi,&nbsp;{{ $lastname }} {{ $firstname }}!<br>
		<br>
		Welcome to baysiacoin.asia!<br>
		<br>
		We are so pleased to let you be our guest.<br>
		@if (isset($isFBCallback) && $isFBCallback == true)
		We recommend you to reset the password because the current was randomly set by the system.<br>
		Please click the url below to change the password.
		@else
		Please click the url below to activate your account.
		@endif
		<br>
		URL: <a href="{{ $url }}">{{ $url }}</a><br>
		<br>
		Your information is as follows. <br>
		<br>
		ACCOUNT ID：{{ $email }}<br>
		PASSWORD  ：******* @unless (isset($isFBCallback) && $isFBCallback == true) (The passsword is the one you input.) @endunless<br>
		URL: <a href="{{ url('/auth/login') }}">{{ url('/auth/login') }}</a><br>
		<br>
		<br>
		<br>
		<br>
		=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-<br>
		Baysiacoin<br>
		Baysia Global Holdings Co.,Ltd.<br>
		20F, C C Wu Building,302-308 Hennessy Road
		Wan Chai, Hong Kong<br>
		E-mail: info@baysia-gh.com<br>
		<br>
		Baysiacoin is P2P network with crypto currency.<br>
		https://baysiacoin.asia/<br>
		<br>
		=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-<br>
    </body>
</html>
