@extends('layouts.withoutmenu')
@section('header')
<link rel="stylesheet" href="{{ asset('css/jquery/jquery.ui.all.css') }}" type="text/css" />
@stop
@section('content')
<div class="container aside-xl">
    <a class="navbar-brand block">{{ trans('message.register.title') }}</a>
    <section class="m-b-lg">

		<div class="line line-dashed"></div>
		<a href="{{ url('/auth/fb') }}" class="btn btn-lg btn-primary btn-block">
			{{ trans('button.auth.facebook_create') }}
		</a>
        <header class="wrapper text-center" style="padding-bottom: 0px;"> {{ trans('message.register.necessary') }} </header>
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                {{ trans('message.register.error') }}<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

		<form role="form" method="POST" action="{{ url('/auth/confirm') }}" enctype="multipart/form-data">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<div>
				<section class="panel panel-default">
					<header class="panel-heading">
						<span class="h4">{{ trans('message.register.profile_title') }}</span>
					</header>
					<div class="panel-body">
						@if (($locale = Session::get('locale', 'ja')) == 'en')
							<div class="form-group pull-in clearfix">
								<div class="col-sm-6">
									<label>{{ trans('message.register.fullname') }}</label>
									<input type="text" class="form-control" name="lastname" value="{{ old('lastname') }}" placeholder="{{ trans('message.register.firstname_ph') }}">
								</div>
								<div class="col-sm-6">
									<label>&nbsp;</label>
									<input type="text" class="form-control" name="firstname" value="{{ old('firstname') }}" placeholder="{{ trans('message.register.lastname_ph') }}">
								</div>
							</div>
						@else
							<div class="form-group pull-in clearfix">
								<div class="col-sm-6">
									<label>{{ trans('message.register.fullname') }}</label>
									<input type="text" class="form-control" name="firstname" value="{{ old('firstname') }}" placeholder="{{ trans('message.register.firstname_ph') }}">
								</div>
								<div class="col-sm-6">
									<label>&nbsp;</label>
									<input type="text" class="form-control" name="lastname" value="{{ old('lastname') }}" placeholder="{{ trans('message.register.lastname_ph') }}">
								</div>
							</div>
						@endif
						{{--<div class="pull-in clearfix">--}}
							{{--<label class="col-sm-3 control-label">{{ trans('message.register.gender') }}</label>--}}
						{{--</div>--}}
						{{--<div class="form-group pull-in clearfix">--}}
							{{--<div class="col-sm-6">--}}
								{{--<div class="radio i-checks">--}}
									{{--<label>--}}
									{{--<input type="radio" name="gender" value="0" {{ $gender == 0 ? 'checked' : '' }}>--}}
										{{--<i></i>{{ trans('message.register.gender_man') }}--}}
									{{--</label>--}}
								{{--</div>--}}
								{{--<div class="radio i-checks">--}}
									{{--<label>--}}
									{{--<input type="radio" name="gender" value="1" {{ $gender == 1 ? 'checked' : '' }}>--}}
										{{--<i></i>{{ trans('message.register.gender_woman') }}--}}
									{{--</label>--}}
								{{--</div>--}}
							{{--</div>--}}
						{{--</div>						--}}
						<div class="form-group">
							<label>{{ trans('message.register.email') }}</label>
							<input type="text" class="form-control" name="email" value="{{ old('email') }}" placeholder="{{ trans('message.register.only_engnum_ph') }}">
						</div>
						<div class="form-group">
							<label>{{ trans('message.register.password') }}</label>
							<input type="password" class="form-control" name="password" value="{{ old('password') }}" placeholder="{{ trans('message.register.password_ph') }}">
						</div>
						<div class="form-group">
							<label>{{ trans('message.register.password_confirm') }}</label>
							<input type="password" class="form-control" name="password_confirmation" value="{{ old('password_confirmation') }}" placeholder="{{ trans('message.register.password_ph') }}">
						</div>
					</div>
				</section>
			</div>
			<div>
				<section class="panel panel-default">
					<header class="panel-heading">
						<span class="h4">{{ trans('message.register.kiyaku_title') }}</span>
					</header>
					<div class="panel-body">
						<div class="col-sm-12">
							<label class="checkbox i-checks">
								<input type="checkbox" id="kiyaku" name="kiyaku" {{ old('kiyaku') == 1 ? 'checked' : '' }} value="{{ old('kiyaku') }}" />
								<i></i><a href="https://japan.baysiacoin.com/terms/" target="_blank">{{ trans('message.register.kiyaku_title') }}</a>
								{{ trans('message.register.kiyaku_accept') }}
							</label>
						</div>
						<br>
						<br>
						<div>
						{{ trans('message.register.kiyaku_guide_1') }}<a href="https://japan.baysiacoin.com/terms/" target="_blank">{{ trans('message.register.kiyaku_guide_2') }}</a>
						</div>
					</div>
				</section>
			</div>
			<button type="submit" class="submit btn btn-lg btn-primary btn-block">
				{{ trans('button.auth.create') }}
            </button>
            <p class="text-muted text-center" style="margin-bottom: 0px;margin-top: 10px;">
                <small>{{ trans('message.register.already_registered') }}</small>
            </p>
			<a href="{{ url('/auth/login') }}" class="btn btn-lg btn-default btn-block">
				{{ trans('button.auth.login') }}
			</a>
			<input type="hidden" id="branch1" name="branch1" value="<?=session('branch1') ?>" />
			<input type="hidden" id="branch2" name="branch2" value="<?=session('branch2') ?>" />
		</form>
	</section>
</div>
@stop

@section('footer')
<script src="{{ asset('js/ui/jquery.ui.core.js') }}"></script>
<script src="{{ asset('js/ui/jquery.ui.datepicker.js') }}"></script>
@if (config('app.locale') != 'en')
	<script src="{{ asset('js/ui/i18n/jquery.ui.datepicker-ja.js') }}"></script>
@endif
<script type="text/javascript">
	$(document).ready(function()
	{
		var branch1 = get_branch(1);
		var branch2 = get_branch(2);
		if (!branch1) {
			branch1 = $('[name="branch1"]').val();
			set_branch(branch1, 1);
		} else {
			$('[name="branch1"]').val(branch1);
		}
		if (!branch2) {
			branch2 = $('[name="branch2"]').val();
			set_branch(branch2, 2);
		} else {
			$('[name="branch2"]').val(branch2);
		}
		if ('{{ Session::get('locale', 'ja') }}' == 'ja' || '{{ Session::get('locale', 'ja') }}' == 'cn') {
			$( "#birthday_datepicker" ).datepicker(
			{
				showButtonPanel: true,
				changeMonth: true,
				changeYear: true,
				yearRange:　"c-100:c",
				dateFormat: "yy年 mm月 dd日",
				onSelect: function(dateText, inst) {
					dateText = dateText.replace("年 ", "/");
					dateText = dateText.replace("月 ", "/");
					dateText = dateText.replace("日", "");
					$( "#birthday" ).val(dateText);
				}
			});
		} else {
			$( "#birthday_datepicker" ).datepicker(
			{
				showButtonPanel: true,
				changeMonth: true,
				changeYear: true,
				yearRange:　"c-100:c",
				dateFormat: "yy/mm/dd",
				onSelect: function(dateText, inst) {
					$( "#birthday" ).val(dateText);
				}
			});
		}
	});
	function get_branch(type) {
		if (!window.localStorage) {
			return;
		}
		var json;
		if (type == 1) {
			json = window.localStorage.getItem("branch1");
		} else if (type == 2) {
			json = window.localStorage.getItem("branch2");
		}
		if (json == null) {
			return '';
		}
		json = JSON.parse(json);
		// expire 30 days
		if (Date.now() - json.timestamp > 2592000000) {
			return '';
		}
		return json.code;
	}
	function set_branch(branchcode, type) {
		if (!window.localStorage) {
			return;
		}
		var json = {code: branchcode, timestamp: Date.now()};
		if (type == 1) {
			window.localStorage.setItem("branch1", JSON.stringify(json));
		} else if (type == 2) {
			window.localStorage.setItem("branch2", JSON.stringify(json));
		}
		return true;
	}
	$('#kiyaku').change(function(){
		if (!$(this).prop('checked')) {
			$(this).val(0);
		} else 	{
			$(this).val(1);
		}
	});
</script>
@stop
