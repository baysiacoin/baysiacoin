@extends('layouts.withoutmenu')

@section('content')
<div class="container aside-xl">
	<a class="navbar-brand block">{{ trans('welcome.welcome_baysia') }}</a>
    <section class="m-b-lg">
    	<header class="wrapper text-center">
        {{ trans('welcome.welcome_baysia_description') }}
        </header>

        <a href="{{ URL::to('auth/register') }}" class="btn btn-lg btn-default btn-block">{{ trans('button.auth.register')}} </a>
        <div class="line line-dashed"></div>
        @if ( isset($message) || !empty($message) )
            <div class="alert alert-danger">
                {{ trans('message.register.error') }}<br><br>
                <ul>
                    <li>{{ $message }}</li>
                </ul>
            </div>
         @endif

        <a href="{{ URL::to('auth/fb') }}" class="btn btn-lg btn-default btn-block">{{ trans('button.auth.facebook_create') }} </a>
        <div class="line line-dashed"></div>
        <a href="{{ URL::to('auth/login')}}" class="btn btn-lg btn-default btn-block">{{ trans('button.auth.login') }}</a>
        <div class="line line-dashed"></div>
        <a href="{{ URL::to('auth/forgot') }}" class="btn btn-lg btn-default btn-block">{{ trans('button.auth.forgot_password') }}</a>
	</section>
</div>
@stop