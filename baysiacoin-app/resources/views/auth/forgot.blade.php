@extends('layouts.withoutmenu')

@section('content')
<div class="container aside-xl">
	<a class="navbar-brand block">{{trans('message.forgot.title')}}</a>	
	<section class="m-b-lg">
		<form method="POST" action="{{ url('auth/forgot') }}" accept-charset="UTF-8">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			@if (isset($success))		   
			    <div class="alert alert-success">
			        {{ $success }}
			    </div>
			@elseif (!empty($errors->first('email')))
				<div class="alert alert-danger">
					{{ $errors->first('email') }}
				</div>
			@endif
			<p class="text-muted text-center">
				<small>{{trans('message.forgot.necessary')}}</small>
			</p>
			<div class="list-group">
				<div class="list-group-item">
					<input type="email" class="form-control no-border" name="email"
						value="{{ old('email') }}"
						placeholder="{{ trans('message.forgot.email_ph') }}">
				</div>
			</div>
			<button type="submit" class="btn btn-lg btn-primary btn-block">{{ trans('button.auth.send') }}</button>
			<div class="line line-dashed"></div>
			<p class="text-muted text-center">
				<small>{{trans('message.register_description')}}</small>
			</p>
			<a href="{{URL::to('/auth/register')}}"
				class="btn btn-lg btn-default btn-block">{{trans('button.auth.register')}}</a>
			<div class="line line-dashed"></div>
			<p class="text-muted text-center">
				<small>{{trans('message.login')}}</small>
			</p>
			<a href="{{URL::to('/auth/login')}}"
				class="btn btn-lg btn-default btn-block">{{trans('button.auth.login')}}</a>
		</form>
	</section>
</div>
@stop
