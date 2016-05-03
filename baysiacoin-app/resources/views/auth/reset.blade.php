@extends('layouts.withoutmenu')

@section('content')
<div class="container aside-xl">
	<a class="navbar-brand block">{{ trans('message.reset.title') }}</a>
	<section class="m-b-lg">
		<form id="registerForm" action="{{ url('/auth/reset') }}" method="post" >
			<input type="hidden" name="token" value="{{ $token }}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			
			@if (isset($success))
			    <div class="alert alert-success">
			        {{ $success }}
			    </div>
			@elseif (isset($fail))
				<div class="alert alert-danger">
					{{ $fail }}
				</div>
			@endif
			
			<div class="list-group">
				<div class="list-group-item">
					<input type="email" name="email" placeholder="{{ trans('message.register.email') }}" class="form-control no-border" value="{{ old('email') }}">
            	</div>
            	<div class="list-group-item">
               		<input type="password" name="password" placeholder="{{ trans('message.register.password') }}" class="form-control no-border">
            	</div>
            	<div class="list-group-item">
               		<input type="password" name="password_confirmation" placeholder="{{ trans('message.register.password_confirm') }}" class="form-control no-border">
            	</div>
         	</div>
          	<button type="submit" class="btn btn-lg btn-primary btn-block">{{ trans('button.auth.reset') }}</button>
          	<div class="line line-dashed"></div>
			<p class="text-muted text-center">
				<small>{{ trans('message.register.already_registered') }}</small>
			</p>
			<a href="{{ url('/auth/login') }}" class="btn btn-lg btn-default btn-block">
				{{ trans('button.auth.login') }}
			</a>
        </form>
	</section>
</div>
@stop