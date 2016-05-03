@extends('layouts.withoutmenu')
@section('content')
<div class="container aside-xl">
	<a class="navbar-brand block">{{ trans('message.login') }}</a>
	@if ( isset($message) && !empty($message) )
		<div class="alert alert-danger">
			{{ trans('message.register.error') }}<br><br>
			<ul>
				<li>{{ $message or '' }}</li>
			</ul>
		</div>
	@elseif (isset($mail_message))
		<div class="alert alert-success">
			{{ trans('message.register.success') }}<br><br>
			<ul>
				<li>{{ $mail_message or '' }}</li>
				<li><a href='{{ asset('/auth/resendemail') }}' style="color:#3C763D;" onmouseover="this.style.color='red'" onmouseout="this.style.color='#3C763D'"><u>{{ $mail_resend_message }}</u></a></li>
			</ul>
		</div>
	@endif
	<section class="m-b-lg">
		<form id="registerForm" action="{{ url('/auth/login') }}" method="post" >
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			@if (isset($verifyError))
			    <div class="alert alert-danger">
			        {{ $verifyError }}
			    </div>
			@elseif (count($errors) > 0)
				<div class="alert alert-danger">
					{{ trans('message.login_error') }}
					<br><br>
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@elseif (!empty(Session::get('session_id_warning')))
				<div class="alert alert-danger">
					{{ Session::pull('session_id_warning') }}<br/>
					{{ Session::pull('session_id_warning_recommend') }}					
				</div>
			@elseif (!empty(Session::get('session_timeout_warning')))
				<div class="alert alert-danger">
					{{ Session::pull('session_timeout_warning_recommend') }}<br>
					{{ Session::pull('session_timeout_warning') }}					
				</div>
			@elseif (!empty(Session::get('connect_info_warning')))
				<div class="alert alert-danger">
					{{ Session::pull('connect_info_warning') }}<br>
					{!! Session::pull('connect_wish_email_confirm') !!}
				</div>
			@endif			
			<div class="list-group">
				<div class="list-group-item">
					<input type="email" name="email" placeholder="{{ trans('placeholder.email') }}" class="form-control no-border" value="{{ old('email') }}">
            	</div>
				<div class="line line-dashed" style="margin:0px;"></div>
            	<div class="list-group-item">
               		<input type="password" name="password" placeholder="{{ trans('placeholder.password') }}" class="form-control no-border">
            	</div>
         	</div>
          	<button type="submit" class="btn btn-lg btn-primary btn-block">{{ trans('button.auth.login') }} </button>
		  	<div class="line line-dashed"></div>
			<p class="text-muted text-center"><small>{{ trans('message.forgot_password')}}</small></p>
          	<a href="{{ url('/auth/forgot') }}" class="btn btn-lg btn-default btn-block">{{ trans('button.auth.forgot_password') }}</a>
         	
         	<div class="line line-dashed"></div>
          	<p class="text-muted text-center"><small>{{trans('message.register_description')}}</small></p>
          	<a href="{{ url('/auth/register') }}" class="btn btn-lg btn-default btn-block">{{ trans('button.auth.register') }}</a>
          	<div class="line line-dashed"></div>

			<a href="{{ URL::to('auth/fb') }}" class="btn btn-lg btn-default btn-block">{{ trans('button.auth.facebook_create') }} </a>
			<div class="line line-dashed"></div>

		</form>
	</section>
</div>
@stop