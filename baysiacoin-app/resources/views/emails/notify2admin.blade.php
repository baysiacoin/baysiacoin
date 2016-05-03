<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		@if($type == SIGN_UP_NOTIFICATION)
			{{ $firstname }}　{{ $lastname }}さんがBaysiaに会員登録しました。<br>
			<br>
			会員情報<br>
			お名前　:　{{ $firstname }}　{{ $lastname }}<br>
			メールアドレス　:　{{ $email }}<br>
		@elseif ($type == UPLOAD_NOTIFICATION)
			{{ $firstname }}　{{ $lastname }}さんが身分情報をアップロードしました。<br>
			<br>
			会員情報<br>
			お名前　:　{{ $firstname }}　{{ $lastname }}<br>
			メールアドレス　:　{{ $email }}<br>
			ファイル名 : {{ $identity_front }},&nbsp;{{ $identity_end }}
		@elseif ($type == SMS_AUTH_NOTIFICATION)
			{{ $firstname }}　{{ $lastname }}さんが2FAを利用しています。<br>
			<br>
			会員情報<br>
			お名前　:　{{ $firstname }}　{{ $lastname }}<br>
			メールアドレス　:　{{ $email }}<br>
		@endif
    </body>
</html>