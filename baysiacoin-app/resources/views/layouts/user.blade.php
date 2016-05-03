<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> 
<html class="app"> <!--<![endif]-->
<head>
 	<meta charset="utf-8" />
	<meta name="description" content="Baysia Coin | Focusing on Providing transaction Protocol" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<title>@yield('title', trans('common.title'))</title>
	<link href="{{ asset('images/favicon.ico') }}" rel="shortcut icon">
	<link rel="stylesheet" href="{{ Session::get('isTrade') == true ? asset('css/bootstrap2.css') : asset('css/bootstrap.css') }}" type="text/css" />
	<link rel="stylesheet" href="{{ asset('css/animate.css') }}" type="text/css" />
	<link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}" type="text/css" />
	<link rel="stylesheet" href="{{ asset('css/icon.css') }}" type="text/css" />
	<link rel="stylesheet" href="{{ asset('css/icomoon.css') }}" type="text/css" />
	<link rel="stylesheet" href="{{ asset('css/font.css') }}" type="text/css" />
	<link rel="stylesheet" href="{{ asset('css/flags.authy.css') }}" type="text/css" />
	<link rel="stylesheet" href="{{ asset('css/jquery-waiting.css') }}" type="text/css" />
	<link rel="stylesheet" href="{{ asset('css/pro-range-slider.css') }}" type="text/css" />
  	<link rel="stylesheet" href="{{ Session::get('isTrade') == true ? asset('css/app2.css') : asset('css/app.css') }} " type="text/css" />
	<script src="{{ asset('js/jquery.min.js') }}"></script>
	<script src="{{ asset('js/jquery-waiting.js') }}"></script>
	<script src="{{ asset('js/bootstrap.js') }}"></script>
	<script src="{{ asset('js/app.js') }}"></script>
	<script src="{{ asset('js/slimscroll/jquery.slimscroll.min.js') }}"></script>
	<script src="{{ asset('js/charts/easypiechart/jquery.easy-pie-chart.js') }}"></script>
	<script src="{{ asset('js/app.plugin.js') }}"></script>
	<script src="{{ asset('js/main.js') }}"></script>
	@section('header')
    @show
</head>
<body>
	<section class="vbox">
		<header class="bg-white header header-md navbar navbar-fixed-top-xs box-shadow">
			<div class="navbar-header dk">
				<a class="btn-left btn-link visible-xs" data-toggle="class:nav-off-screen,open" data-target="#nav,html">
					<i class="fa fa-bars"></i>
				</a>				
				<a href="{{ url('/') }}">
					<img src="{{ Session::get('isTrade') == true ? asset('images/logo_dark.png') : asset('images/logo.png')}} " alt="scale">
				</a>				
				<a class="btn-right btn-link visible-xs" data-toggle="dropdown" data-target=".user">
					<i class="fa fa-cog"></i>
				</a>				
				<a class="btn-right btn-link visible-xs" style="font-weight:400;font-size:13px;" data-toggle="dropdown" data-target=".lang">
					@if (($locale = Session::get('locale', 'ja')) == 'ja')
						<i class="aflag flag-ja81"></i>
					@elseif ($locale == 'en')
						<i class="aflag flag-un44"></i>
					@elseif ($locale == 'cn')
						<i class="aflag flag-ch86"></i>	
					@endif					
				</a>			
				<a style="display:inline-block;font-size:1.1em;color:red;font-family: 'MS PGothic', 'Osaka', Arial, sans-serif;font-style:normal;font-weight:bold;" >
				@if(Session::has('licensed') && Session::get('licensed') != USER_LICENSE_CHECKED)
					@if (!empty($usersinfo->identity_front) && !empty($usersinfo->identity_end))
                    	{{ trans('message.alert_msg_identify_img_uploaded') }}
					@else
						{{ trans('message.alert_msg_identify') }}
					@endif
                @endif
                </a>               
			</div>
			<ul class="nav navbar-nav navbar-right m-n hidden-xs nav-user user">				
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						{{ $login_username }}{{Session::get('locale', 'ja') == 'ja' ? 'さん' : ''}}<b class="caret"></b>
					</a>
					<ul class="dropdown-menu animated fadeInRight">            
						<li>
							<span class="arrow top"></span>
							<a href="{{ url('/user/profile') }}">{{ trans('menu.my_profile') }}</a>
						</li>
						@if ($locale == 'ja')
							<li>
								<a href="http://japan.baysiacoin.com/faq/index.html">FAQ</a>
							</li>
							<li>
								<a href="http://japan.baysiacoin.com/contact/index.php">{{ trans('menu.inquiry') }}</a>
							</li>
						@elseif ($locale == 'en')
							<li>
								<a href="http://english.baysiacoin.com/faq/index.html">FAQ</a>
							</li>
							<li>
								<a href="http://english.baysiacoin.com/contact/index.php">{{ trans('menu.inquiry') }}</a>
							</li>
						@elseif ($locale == 'cn')
							<li>
								<a href="http://china.baysiacoin.com/faq/index.html">FAQ</a>
							</li>
							<li>
								<a href="http://china.baysiacoin.com/contact/index.php">{{ trans('menu.inquiry') }}</a>
							</li>
						@endif
						<li class="divider"></li>
						<li>
							<a href="{{URL::to('/auth/logout') }}"  >{{ trans('menu.logout') }}</a>
						</li>
					</ul>
				</li>
			</ul>
			<ul class="nav navbar-nav navbar-right m-n hidden-xs nav-user lang">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						@if ($locale == 'ja')
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
		<section>
			<section class="hbox stretch">
				<aside class="bg-black aside-md hidden-print hidden-xs" id="nav">          
					<section class="vbox">
						<section class="w-f scrollable">
						  <div class="slimScrollDiv">
							<div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="10px" data-railOpacity="0.2">
								<div class="clearfix wrapper dk nav-user hidden-xs">
				                  <div>
				                  </div>
				                </div>
								 <!-- nav --> 
								 <nav class="nav-primary hidden-xs">
									 <ul class="nav nav-main" data-ride="collapse">
										 <li>
											 <a href="{{ url('/money/balance') }}" class="auto">
												 <i class="i icon-coin icon"></i>
												 <span>{{ trans('menu.account_balance') }}</span>
											 </a>
										 </li>
										 <li>
											 <a href="{{ url('/money/trade') }}" class="auto">
												 <i class="i icon-busy icon"></i>
											 	 <span>{{ trans('menu.trade') }}</span>
											 </a>
										 </li>
										 <li>
											 <a href="{{ url('/money/history/fund') }}" class="auto">
												 <i class="i icon-history con"></i>
												 <span>{{ trans('menu.history') }}</span>
											 </a>
										 </li>
										 <li >
											 <a href="{{ url('/money/transfer') }}" class="auto">
												 <i class="i icon-paperplane icon"></i>
												 <span>{{ trans('menu.send') }}</span>
											 </a>
										 </li>
										 <li>
											 <a href="#" class="auto">
												 <span class="pull-right text-muted">
													 <i class="fa fa-angle-double-up text"></i>
													 <i class="fa fa-angle-double-down text-active"></i>
												 </span>
												 <i class="i icon-loop-2 icon"></i>
												 <span>{{ trans('menu.deposit_and_withdraw') }}</span>
											 </a>
											 <ul class="nav dk">											 
												 <li>
													 <a href="{{ url('/money/fund') }}" class="auto">
													 <!--a href="http://japan.baysiacoin.com/buy/index.html" class="auto"-->
														 <i class="fa fa-angle-right"></i>
														 <span>{{ trans('menu.deposit_request') }}</span>
													 </a>
												 </li>
												 <li>
													 <a href="{{ url('/money/withdraw') }}" class="auto">
													 <!--a href="http://japan.baysiacoin.com/withdraw/index.html" class="auto"-->
														 <i class="fa fa-angle-right"></i>
														 <span>{{ trans('menu.withdraw_request') }}</span>
													 </a>
												 </li>
											 </ul>
										 </li>
										  <li>
											 <a href="{{ url('/money/gateway') }}" class="auto">
												 <i class="i icon-home-4 icon"></i>
												 <span>{{ trans('menu.gateway') }}</span>
											 </a>
										 </li>
										 <li>
											 <a href="{{ url('/money/view') }}" class="auto">
												 <i class="i icon-search icon"></i>
												 <span>{{ trans('menu.account_explorer') }}</span>
											 </a>
										 </li>
									  	 <!-- <li>
											 <a href="http://baysia.asia/" class="auto" target="_blank" id="_chart">
												 <i class="i icon-bars icon"></i>
												 <span>{{ trans('menu.chart') }}</span>
											 </a>
										 </li> -->
										 <li>
											 <a href="{{ url('/user/profile') }}" class="auto">
												 <i class="i icon-profile icon"></i>
												 <span>{{ trans('menu.my_profile') }}</span>
											 </a>
										 </li>
										@if ($locale == 'ja')
											<!-- <li>
											 <a href="http://japan.baysiacoin.com/withdraw/index.html" class="auto">
												 <i class="i i-statistics icon"></i>
												 <span>{{ trans('menu.fee') }}</span>
											 </a>
											 </li> -->
											 <li>
												 <a href="#" class="auto">
													 <span class="pull-right text-muted">
														<i class="fa fa-angle-double-up text"></i>
													 	<i class="fa fa-angle-double-down text-active"></i>
													 </span>
													 <i class="i icon-support icon"> </i>
													 <span>{{ trans('menu.support') }}</span>
												 </a>
												 <ul class="nav dk">
													 <li >
														 <a href="http://japan.baysiacoin.com/faq/index.html" class="auto"> 
															 <i class="fa fa-angle-right"></i>
															 <span>FAQ</span>
														 </a>
													 </li>
													 <li >
														 <a href="http://japan.baysiacoin.com/contact/index.php" class="auto"> 
															 <i class="fa fa-angle-right"></i>
															 <span>{{ trans('menu.inquiry') }}</span>
														 </a>
													 </li>
												 </ul>
											 </li>
										@elseif ($locale == 'en')
											<!-- <li>
											 <a href="http://english.baysiacoin.com/withdraw/index.html" class="auto">
												 <i class="i i-statistics icon"></i>
												 <span>{{ trans('menu.fee') }}</span>
											 </a>
											 </li> -->
											 <li>
												 <a href="#" class="auto">
													 <span class="pull-right text-muted">
														<i class="fa fa-angle-double-up text"></i>
													 	<i class="fa fa-angle-double-down text-active"></i>
													 </span>
													 <i class="i icon-support icon"></i>
													 <span>{{ trans('menu.support') }}</span>
												 </a>
												 <ul class="nav dk">
													 <li >
														 <a href="http://english.baysiacoin.com/faq/index.html" class="auto"> 
														 <i class="fa fa-angle-right"></i>
														 <span>FAQ</span>
														 </a>
													 </li>
													 <li >
														 <a href="http://english.baysiacoin.com/contact/index.php" class="auto"> 
															 <i class="fa fa-angle-right"></i>
															 <span>{{ trans('menu.inquiry') }}</span>
														 </a>
													 </li>
												 </ul>
											 </li>
										@elseif ($locale == 'cn')
											<!-- <li>
											 <a href="http://china.baysiacoin.com/withdraw/index.html" class="auto">
												 <i class="i i-statistics icon"></i>
												 <span>{{ trans('menu.fee') }}</span>
											 </a>
											 </li> -->
											 <li>
												 <a href="#" class="auto">
													 <span class="pull-right text-muted">
														<i class="fa fa-angle-double-up text"></i>
													 	<i class="fa fa-angle-double-down text-active"></i>
													 </span>
													 <i class="i icon-support icon"></i>
													 <span>{{ trans('menu.support') }}</span>
												 </a>
												 <ul class="nav dk">
													 <li >
														 <a href="http://china.baysiacoin.com/faq/index.html" class="auto"> 
															 <i class="fa fa-angle-right"></i>
															 <span>FAQ</span>
														 </a>
													 </li>
													 <li >
														 <a href="http://china.baysiacoin.com/contact/index.php" class="auto"> 
															 <i class="fa fa-angle-right"></i>
															 <span>{{ trans('menu.inquiry') }}</span>
														 </a>
													 </li>
												 </ul>
											 </li>
										@endif										 
									 </ul>
									 <div class="line dk hidden-nav-xs"></div>
								 </nav>
							 <!-- / nav -->
							 </div>
						   </div>
						 </section>
						 
						 <footer class="footer hidden-xs no-padder text-center-nav-xs">
						 <a href="modal.lockme.html" data-toggle="ajaxModal" class="btn btn-icon icon-muted btn-inactive pull-right m-l-xs m-r-xs hidden-nav-xs">
						 <i class="i i-logout"></i>
						 </a>
						 <a href="#nav" data-toggle="class:nav-xs" class="btn btn-icon icon-muted btn-inactive m-l-xs m-r-xs">
						 <i class="i i-circleleft text"></i>
						 <i class="i i-circleright text-active"></i>
						 </a>
						 </footer>
					 </section>
				</aside>
				<section id="content" class="{{ Session::get('isTrade') == true ? 'bg-black' : 'bg-white' }}">
					<section class="vbox {{ Session::pull('isTrade') == true ? 'bg-black1' : 'bg-white1' }}">
	        			@yield('content')
					</section>
					<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen,open" data-target="#nav,html"></a>
				</section>
			</section>
		</section>
	</section>
	@section('footer')
  	@show
</body>
<script type="text/javascript">
	$(document).ready(function() {
		$("#_chart").click(function(e) {
			//location.href = getPreviousURL();
		});
	});
	function getPreviousURL() {
		return localStorage.getItem('prev_url');
	}
</script>
</html>