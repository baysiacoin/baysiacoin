<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Baysiacoin</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Baysia Coin | Focusing on Providing transaction Protocol" />
		<link href="{{ asset('new/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" media="all"/>
		<link href="{{ asset('new/css/flexslider.min.css') }}'" rel="stylesheet" type="text/css" media="all"/>
		<link href="{{ asset('new/css/elegant-icons.min.css') }}" rel="stylesheet" type="text/css" media="all"/>
		<link href="{{ asset('new/css/pe-icon-7-stroke.min.css') }}" rel="stylesheet" type="text/css" media="all"/>
		<link href="{{ asset('new/css/lightbox.min.css') }}" rel="stylesheet" type="text/css" media="all"/>
		<link href="{{ asset('new/css/theme.css') }}" rel="stylesheet" type="text/css" media="all"/>
		{{--<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,300' rel='stylesheet' type='text/css'>--}}
		<script src="{{ asset('new/js/jquery.min.js') }}" ></script>
		<script src="{{ asset('new/js/bootstrap.min.js') }}"></script>
		<script src="{{ asset('new/js/skrollr.min.js') }}"></script>
		<script src="{{ asset('new/js/spectragram.min.js') }}"></script>
		<script src="{{ asset('new/js/flexslider.min.js') }}"></script>
		<script src="{{ asset('new/js/jquery.plugin.min.js') }}"></script>
		<script src="{{ asset('new/js/jquery.countdown.min.js') }}"></script>
		<script src="{{ asset('new/js/lightbox.min.js') }}"></script>
		<script src="{{ asset('new/js/smooth-scroll.min.js') }}"></script>
		<script src="{{ asset('new/js/twitterfetcher.min.js') }}"></script>
		<script src="{{ asset('new/js/scripts.js') }}"></script>
		@section('header')
		@show
	</head>
	<body>
		<a id="top"></a>
		<div class="nav-container">
			<nav class="overlay-nav">
				<div class="container">
					<div class="row">
						<div class="col-md-2">
							<a href="{{  url('/auth/login1') }}">
								<img alt="Logo" class="logo logo-light" src="{{ asset('new/images/logo.png') }}">
								<img alt="Logo" class="logo logo-dark" src="{{ asset('new/images/logo-dark.png') }}">
							</a>
						</div>
						<div class="col-md-9 text-right">
							<ul class="menu">
								<li class="has-dropdown">
									<a class="inner-link" href="#home" target="default">
										@if (($locale = Session::get('locale', 'ja')) == 'ja')
											{{ trans('menu.menu_japanese') }}
										@elseif ($locale == 'en')
											{{ trans('menu.menu_english') }}
										@elseif ($locale == 'cn')
											{{ trans('menu.menu_chinese') }}
										@endif
									</a>
									<ul class="nav-dropdown">
										<li><a href="{{ url('/user/lang/en') }}">{{ trans('menu.menu_english') }}</a></li>
										<li><a href="{{ url('/user/lang/ja') }}">{{ trans('menu.menu_japanese') }}</a></li>
										<li><a href="{{ url('/user/lang/cn') }}">{{ trans('menu.menu_chinese') }}</a></li>
									</ul>
								</li>
							</ul>
						</div>
						<div class="col-md-1 text-right">
							<ul class="menu">
								<li class="has-dropdown"><a class="inner-link" href="#home" target="default">{{ trans('menu.menu') }}</a>
									<ul class="nav-dropdown">
										<li><a href="{{ url('/auth/login1') }}">{{ trans('button.auth.login') }}</a></li>
										<li><a href="{{ url('/auth/register1') }}">{{ trans('button.auth.register') }}</a></li>
										<li><a href="{{ url('/auth/forgot1') }}">{{ trans('button.auth.forgot_password') }}</a></li>
									</ul>
								</li>
							</ul>
						</div>
					</div><!--end of row-->
				</div><!--end of container-->
				<div class="bottom-border"></div>
				<div class="sidebar-menu">
					<img alt="Logo" class="logo" src="{{ asset('new/images/logo.png') }}">
					<div class="bottom-border"></div>
					<div class="sidebar-content">
						<div class="widget">
							<ul class="menu">
								<li><a class="inner-link" href="#home" target="default">home</a></li>
								<li><a class="inner-link" href="#about" target="default">about</a></li><li><a class="inner-link" href="#schedule" target="default">schedule</a></li><li><a class="inner-link" href="#pricing" target="default">pricing</a></li><li><a class="inner-link" href="#register" target="default">register</a></li><li><a class="inner-link" href="#subscribe" target="default">subscribe</a></li>
								<li class="social-link"><a href="#"><i class="icon social_twitter"></i></a></li>
								<li class="social-link"><a href="#"><i class="icon social_facebook"></i></a></li>
								<li class="social-link instagram-toggle"><a href="#" class="instagram-toggle-init"><i class="icon social_instagram"></i></a></li>
							</ul>
						</div>
						<div class="widget">
							<ul class="social-profiles">
								<li><a href="#"><i class="icon social_twitter"></i></a></li>
								<li><a href="#"><i class="icon social_facebook"></i></a></li>
								<li><a href="#"><i class="icon social_dribbble"></i></a></li>
								<li><a href="#"><i class="icon social_instagram"></i></a></li>
								<li><a href="#"><i class="icon social_googleplus"></i></a></li>
							</ul>
						</div>

						<div class="copy-text">
							<span></span>
						</div>
					</div><!--end of sidebar content-->
				</div><!--end of sidebar-->
			</nav>
		</div>
		<div class="main-container">
			@yield('content')
		</div>
		<div class="footer-container"></div>
		@section('footer')
		@show

		@yield('script')
	</body>
</html>