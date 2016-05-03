<!DOCTYPE html>
<html>
<head>
    <title>
    @section('title', trans('admin.title'))
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- CSS are placed here -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/bootstrap-responsive.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/superfish/superfish.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/base.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/admin/style.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    {{--<link rel="stylesheet" href="{{ asset('js/dataTables/datatables.css') }}">--}}
    {{--<link rel="stylesheet" href="{{ asset('js/dataTables/plugins/bootstrap/datatables.bootstrap.css') }}">--}}
	<style type='text/css'>div#container{width: 99%;}</style>
    <!-- Scripts are placed here -->
    @section('header')
    @show
</head>

<body>

<!-- Container -->

    <!-- Header -->
    @include('admin.layouts.header')
    <!-- End Header -->
    <!-- Content -->
    <!-- Main Content -->
    <div id="container" class="clear">
        <!-- Main content -->
        <div class="main-contain">
            @yield('content')
        </div>
        <!-- Sidebar right -->
        <?php if(isset($issidebar) && $issidebar){ ?>
        <div class="sidebarright">
            @yield('sidebarright')
        </div>
        <?php } ?>
    </div>
    <!-- Footer -->
    <div id="footer" class="clear">
  		<div id="footer-content">
    		<div style="width: 550px; float:left;"><p class="copyrights">{{ trans('admin.message.footer')}}</div>
  		</div>
	</div>
	<div id="mainLoader"></div>
	<div id="loaderFade"></div>
    
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.js') }}"></script>
    <script src="{{ asset('js/superfish/hoverIntent.js') }}"></script>
    <script src="{{ asset('js/superfish/superfish.js') }}"></script>
    <script src="{{ asset('js/plugins/noty/jquery.noty.js')}}"></script>
    <script src="{{asset('js/plugins/noty/layouts/topCenter.js')}}"></script>
    <script src="{{asset('js/plugins/noty/layouts/topLeft.js') }}"></script>
    <script src="{{asset('js/plugins/noty/layouts/topRight.js')}}"></script>
    <script type='text/javascript' src={{asset('js/plugins/noty/themes/default.js')}}></script>
    @section('footer')
    @show
    <script>
	(function($){ //create closure so we can safely use $ as alias for jQuery
		$(document).ready(function(){
			// initialise plugin
			var example = $('#main-menu').superfish({
				//add options here if required
			});
		});
	});
	</script>
</body>
</html>
