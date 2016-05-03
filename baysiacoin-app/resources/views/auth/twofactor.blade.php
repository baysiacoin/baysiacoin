@extends('layouts.withoutmenu')
@section('content')
<div class="container aside-xl">
	<a class="navbar-brand block">{{ trans('message.login_tfa.title') }}</a>
	<section class="m-b-lg">
		<form id="registerForm" action="{{ url('/auth/two-factor') }}" method="post" >
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			@if (count($errors) > 0)
				<div class="alert alert-danger">
					{{ trans('message.login_error') }}
					<br><br>
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif
			<div class="list-group" style="text-align: center;">
				@if ($qr_flag == 1)
					<div>
						{!! QrCode::format('svg')->size(230)->generate($code) !!}
					</div>
					<div style="display: inline-block">
						<a href="{{ url('/download/apps/com.gamma.scan.apk') }}" class="btn btn-success btn-xs pull-right" style="float:right">{{ trans('message.login_tfa.qr_app2') }}</a>
						<a href="{{ url('/download/apps/me.scan.android.client.apk') }}" class="btn btn-info btn-xs pull-right" style="float:right">{{ trans('message.login_tfa.qr_app1') }}</a>
						<a download href="data:image/png;base64, {{ base64_encode(QrCode::format('png')->size(230)->generate($code)) }}" class="btn btn-warning btn-xs pull-right">{{ trans('message.login_tfa.img_download') }}</a>
					</div>
					<div class="line line-dashed"></div>
					<div>
						<p class="text-muted text-center inline"><small>{{  trans('message.login_tfa.scan_code') }}</small></p>
					</div>
					<div class="list-group-item">
						<input type="text" class="form-control no-border" name="qr_token" placeholder="{{ trans('placeholder.qr_code_number') }}">
					</div>
            	@endif
            	@if ($tfa_flag == 1)
					<div class="line line-dashed"></div>
					<p class="text-muted text-center"><small>{{  trans('message.login_tfa.via_sms_code') }}</small></p>
					<div class="list-group-item">
						<input type="text" class="form-control no-border" name="sms_token" placeholder="{{ trans('placeholder.sms_code_number') }}">
					</div>
					<div>
						<label class="h6">{{ trans('message.login_tfa.sms_not_received') }}&nbsp;&nbsp;&nbsp;</label>
						<label><a href="javascript: sendSMSRequest();" class="font-bold h5">{{ trans('message.login_tfa.req_sms') }}</a></label>
					</div>
				@endif
         	</div>
			<button type="submit" class="btn btn-lg btn-primary btn-block">{{ trans('button.auth.send') }} </button>
			<div class="line line-dashed"></div>
			<p class="text-muted text-center"><small>{{ trans('message.forgot_password')}}</small></p>
			<a href="{{ url('/auth/forgot') }}" class="btn btn-lg btn-default btn-block">{{ trans('button.auth.forgot_password') }}</a>
		</form>
	</section>
</div>
@stop
<script type="text/javascript">
	var sendSMSRequest = function() {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.post('{{ url('auth/sms-request') }}', function(msg) {
			console.log(msg);
			if (msg.result == 'Success') {
				alert('A message is successfully sent.');
			} else {
				alert('Unfortunately sending message is failed.\nPlease try again later.');
			}
		}, "json");
	}
</script>