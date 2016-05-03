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
							<form class="register email-form" method="POST" action="{{ url('/auth/confirm1') }}" enctype="multipart/form-data">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<h1 class="text-white2">{{ trans('message.register.title') }}</h1>
								@if (($locale = Session::get('locale', 'ja')) == 'en')
									<div class="col-sm-6">
										<input class="form-name validate-required" type="text" name="lastname" value="{{ old('lastname') }}" placeholder="{{ trans('message.register.firstname_ph') }}">
									</div>
									<div class="col-sm-6">
										<input class="form-name validate-required" type="text" name="firstname" value="{{ old('firstname') }}" placeholder="{{ trans('message.register.lastname_ph') }}">
									</div>
								@else
									<div class="col-sm-6">
										<input class="form-name validate-required" type="text" name="firstname" value="{{ old('firstname') }}" placeholder="{{ trans('message.register.firstname_ph') }}">
									</div>
									<div class="col-sm-6">
										<input class="form-name validate-required" type="text" name="lastname" value="{{ old('lastname') }}" placeholder="{{ trans('message.register.lastname_ph') }}">
									</div>
								@endif
								{{--<div class="col-sm-12">--}}
									{{--<input class="form-account-name validate-required" type="text" placeholder="アカウント名" name="アカウント名">--}}
								{{--</div>--}}
								<div class="col-sm-12">
									<input class="form-email validate-email" type="text" name="email" value="{{ old('email') }}" placeholder="{{ trans('placeholder.email') }}">
								</div>
								<div class="col-sm-12">
									<input class="form-password validate-required" type="password" name="password" value="{{ old('password') }}" placeholder="{{ trans('placeholder.password') }}">
								</div>
								<div class="col-sm-12">
									<input class="form-password validate-required" type="password" name="password_confirmation" value="{{ old('password_confirmation') }}" placeholder="{{ trans('placeholder.password_confirmation') }}">
								</div>
								<div class="col-sm-12">
									<input type="submit" class="btn" value="{{ trans('button.auth.create') }}">
								</div>
								<div class="col-sm-12">
									<input type="button" id="fb_btn" class="btn2" value="{{ trans('button.auth.facebook_create') }}">
								</div>
								<input type="hidden" id="branch1" name="branch1" value="<?=session('branch1') ?>" />
								<input type="hidden" id="branch2" name="branch2" value="<?=session('branch2') ?>" />
								<div class="col-sm-12">
									<div class="form-error"><span>{{ trans('message.input_error') }}</span></div>
									@if (!empty($errors->all()))
									<div class="form-error display">
										@foreach ($errors->all() as $error)
											<span>{{ $error }}</span><br>
										@endforeach
									</div>
									@endif
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
<script>
	$('#fb_btn').click(function(){
		location.href = "{{ url('auth/fb') }}";
	});
</script>
@stop