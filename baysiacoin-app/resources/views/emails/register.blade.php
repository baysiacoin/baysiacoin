<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		{{ $firstname }}　{{ $lastname }}様<br>
		<br>
		この度はベイジアコインのウォレット作成をお申し込み頂き誠にありがとうございます。<br>
		<br>
		@if (isset($isFBCallback) && $isFBCallback == true)
		パスワードを保存するか、もしくは再設定してください。<br>
		@else
		登録手続きを完了するには、下記のURLをクリックしてください。<br>
		@endif
		ＵＲＬ: <a href="{{ $url }}">{{ $url }}</a><br>
		<br>
		また、お客様のログイン情報は下記になります。慎重に管理頂きます様お願いいたします。<br>
		<br>
		アカウントID：{{ $email }}<br>
		パスワード  ：******* @unless (isset($isFBCallback) && $isFBCallback == true) (パスワードはご登録時に頂いたものです) @endunless<br>
		ログインURL: <a href="{{ url('/auth/login') }}">{{ url('/auth/login') }}</a><br>
		<br>
		<br>
		=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-<br>
		Baysiacoin<br>
		Baysia Global Holdings Co.,Limited<br>
		Prosperity Millennia Plaza 2nd Floor, 663 King's Road, Quarry Bay<br>
		=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-<br>
    </body>
</html>