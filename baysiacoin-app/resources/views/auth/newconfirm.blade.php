@extends('layouts.newuser')
@section('content')
	<section class="hero-slider">
		<ul class="slides">
			<li class="register-header">
				<div class="background-image-holder parallax-background">
					<img class="background-image" alt="Background Image" src="{{ asset('new/images/hero6.jpg') }}">
				</div>
				<div class="container">
					<div class="row">
						<div class="col-md-6 col-sm-6">
							<h1 class="large-h1 text-white">{{ trans('message.contribute') }}</h1>
							<span class="lead text-white">{{ trans('message.block_chain_platform') }}</span>
						</div>
						<div class="col-md-5 col-md-offset-1 col-sm-6">
							<form class="register email-form" method="POST" id="form_confirm" action="">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<h1 class="text-white2">{{ trans('message.register.confirm_title') }}</h1>
								<div class="col-sm-12">
									@if (($locale = Session::get('locale', 'ja')) == 'en')
										<label><strong>{{ trans('message.register.fullname') }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></label>
										<label>{{ $lastname . ' ' . $firstname}}</label>
										<input type="hidden" name="firstname" value="{{ $firstname or '' }}">
										<input type="hidden" name="lastname" value="{{ $lastname or '' }}">
									@else
										<label><strong>{{ trans('message.register.fullname') }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></label>
										<label>{{ $firstname . ' ' . $lastname }}</label>
										<input type="hidden" name="firstname" value="{{ $firstname or '' }}">
										<input type="hidden" name="lastname" value="{{ $lastname or '' }}">
									@endif
								</div>
								<div class="col-sm-12">
									<label><strong>{{ trans('message.register.email') }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></label>
									<label>{{ $email or '' }}</label>
									<input type="hidden" name="email" value="{{ $email or '' }}">
								</div>
								<div class="col-sm-12">
									<label><strong>{{ trans('message.register.password') }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></label>
									<label>********</label>
									<input type="hidden" name="password" value="{{ $password or '' }}">
									<input type="hidden" name="password_confirmation" value="{{ $password_confirmation or '' }}" />
								</div>
								<div class="col-sm-12" style="height: 30px;"></div>
								<div class="col-sm-12">
									<input type="submit" class="btn submit" value="{{ trans('button.auth.register') }}">
								</div>
								<div class="col-sm-12">
									<input type="button" class="btn2 modify" value="{{ trans('button.auth.modify') }}">
								</div>
								<input type="hidden" id="branch1" name="branch1" value="<?=session('branch1') ?>" />
								<input type="hidden" id="branch2" name="branch2" value="<?=session('branch2') ?>" />
								<input type="hidden" name="kiyaku" value="{{ $kiyaku or ''}}" />
								<div class="col-sm-12">
									<div class="form-success"><span>お申し込みが完了しました。確認メールが送信されましたので、メール本文中のリンクをクリックしてください。</span></div>
								</div>
							</form>
						</div>
					</div><!--end of row-->
				</div><!--end of container-->
			</li>
		</ul>
	</section>
@stop
@section('script')
<script type="text/javascript">
	$('.submit').click(function(e)
	{
		e.preventDefault();
		$('#form_confirm').prop('action', '{{ url("/auth/create1") }}');
		$('#form_confirm').submit();
	});
	$('.modify').click(function(e)
	{
		e.preventDefault();
		$('#form_confirm').prop('action', '{{ url("/auth/register1?from=modify") }}');
		$('#form_confirm').submit();
	});
</script>
@stop