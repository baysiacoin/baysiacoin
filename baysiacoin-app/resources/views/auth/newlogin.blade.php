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
							<form class="register email-form" action="{{ url('/auth/login1') }}" method="post">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<h1 class="text-white2">{{ trans('message.login') }}</h1>
								<div class="col-sm-12">
									<input class="form-email validate-email" type="email" name="email" placeholder="{{ trans('placeholder.email') }}" class="form-control no-border" value="{{ old('email') }}">
								</div>
								<div class="col-sm-12">
									<input class="form-password validate-required" type="password" name="password" placeholder="{{ trans('placeholder.password') }}" class="form-control no-border">
								</div>
								<div class="col-sm-12">
									<input type="submit" value="{{ trans('button.auth.login') }}" class="btn">
								</div>
								<div class="col-sm-12">
									@if ( isset($message) && !empty($message) )
										<div class="form-error">
											<span>{{ $message or '' }}</span>
										</div>
									@elseif (isset($mail_message))
										<div class="form-success display">
											<span>
												{{ $mail_message or '' }}<br>
												<a href='{{ asset('/auth/resendemail1') }}' onmouseover="this.style.color='black'" onmouseout="this.style.color='white'">{{ $mail_resend_message }}</a>
											</span>
										</div>
									@endif
									<div class="form-error"><span>{{ trans('message.input_error') }}</span></div>
									@if (isset($verifyError))
										<div class="form-error display">
											<span>{{ $verifyError }}</span>
										</div>
									@elseif (count($errors) > 0)
										<div class="form-error display">
											@foreach ($errors->all() as $error)
												<span>{{ $error }}</span>
											@endforeach
										</div>
									@elseif (!empty(Session::get('session_id_warning')))
										<div class="form-error display">
											<span>
												{{ Session::pull('session_id_warning') }}<br/>
												{{ Session::pull('session_id_warning_recommend') }}
											</span>
										</div>
									@elseif (!empty(Session::get('session_timeout_warning')))
										<div class="form-error display">
											<span>
												{{ Session::pull('session_timeout_warning_recommend') }}<br>
												{{ Session::pull('session_timeout_warning') }}
											</span>
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