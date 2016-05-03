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
							<form class="register email-form" method="POST" action="{{ url('auth/forgot1') }}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<h1 class="text-white2">{{trans('message.forgot.title')}}</h1>
								<div class="col-sm-12">
									<input class="form-email validate-email" type="email" name="email" value="{{ old('email') }}" placeholder="{{ trans('message.forgot.email_ph') }}">
								</div>
								<div class="col-sm-12">
									<input type="submit" class="btn" value="{{ trans('button.auth.send') }}">
								</div>
								<div class="col-sm-12">
									<div class="form-error"><span>{{ trans('message.input_error') }}</span></div>
									@if (isset($success))
										<div class="form-success display">
											<span>{{ $success }}</span>
										</div>
									@elseif (!empty($errors->first('email')))
										<div class="form-error display">
											<span>{{ $errors->first('email') }}</span>
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