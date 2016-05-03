<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<html class="app"> <!--<![endif]-->
<head>
 	<meta charset="utf-8" />
  	<meta name="description" content="Baysia Coin | Focusing on Providing transaction Protocol" />
  	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', trans('common.title'))</title>
    <link href="{{ asset('images/favicon.ico') }}" rel="shortcut icon">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}" type="text/css" />
  	<link rel="stylesheet" href="{{ asset('css/animate.css') }} " type="text/css" />
  	<link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }} " type="text/css" />
  	<link rel="stylesheet" href="{{ asset('css/icon.css') }} " type="text/css" />
  	<link rel="stylesheet" href="{{ asset('css/font.css') }} " type="text/css" />
  	<link rel="stylesheet" href="{{ asset('css/app.css') }} " type="text/css" />
    @section('header')
    @show
</head>

<body class="metro @if(Request::is('/')) home @endif @if(Request::is('market/*')) market @endif @if( Auth::guest() ) guest @else logged @endif">
	<header class="bg-white header header-md navbar navbar-fixed-top-xs box-shadow">
		<div class="navbar-header dk">
			<!--a class="btn btn-link visible-xs" data-toggle="class:nav-off-screen,open" data-target="#nav,html">
				<i class="fa fa-bars"></i>
			</a-->
			<a class="btn-right btn-link visible-xs" style="font-weight:400;font-size:13px;" data-toggle="dropdown" data-target=".user">
				{{ trans('menu.menu_language') }}&nbsp;<b class="caret"></b>
			</a>
			<a href="{{ url('user/profile') }}">
				<img src="{{ Session::pull('isTrade') == true ? asset('images/logo2.png') : asset('images/logo.png')}} " alt="scale">
			</a>
		</div>
		<ul class="nav navbar-nav navbar-right m-n hidden-xs nav-user user">
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					@if (($locale = Session::get('locale', 'ja'))== 'ja')
						{{ trans('menu.menu_japanese') }}
					@elseif ($locale == 'en')
						{{ trans('menu.menu_english') }}
					@elseif ($locale == 'cn')
						{{ trans('menu.menu_chinese') }}
					@endif
					<span class="fa fa-globe"></span>
				</a>
				<ul class="dropdown-menu animated fadeInRight">
					<li>
						<a href="{{ url('/user/lang/en') }}">{{ trans('menu.menu_english') }}</a>
					</li>
					<li>
						<a href="{{ url('/user/lang/ja') }}">{{ trans('menu.menu_japanese') }}</a>
					</li>
					<li>
						<a href="{{ url('/user/lang/cn') }}">{{ trans('menu.menu_chinese') }}</a>
					</li>
				</ul>
			</li>
		</ul>
	</header>
	<section id="content" class="m-t-lg wrapper-md animated fadeInUp">
    	@yield('content')
	</section>
	
 	<footer id="footer">
   		<div class="text-center padder">
	      	<p>
	        	<small>Baysia Global Holdings.Co.,Ltd.<br>&copy; 2015</small>
	      	</p>
    	</div>
  	</footer>        
  
    <script src="{{ asset('js/jquery.min.js') }}"></script>
	<script src="{{ asset('js/bootstrap.js') }}"></script>
  	@section('footer')
  	@show
</body>
</html>
