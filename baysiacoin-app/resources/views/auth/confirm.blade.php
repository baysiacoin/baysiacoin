@extends('layouts.withoutmenu')

@section('content')
<div class="container aside-xl">
	<a class="navbar-brand block">{{ trans('message.register.confirm_title') }}</a>
	<section class="m-b-lg">
		<header class="wrapper text-center"> {{ trans('message.register.confirm_necessary') }} </header>
		
		<form id="confirm_form" role="form" method="POST" action="">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<div>
				<section class="panel panel-default">
					<header class="panel-heading">
						<span class="h4">{{ trans('message.register.profile_title') }}</span>
					</header>
					<div class="panel-body">
						@if (($locale = Session::get('locale', 'ja')) == 'en')
						<div class="form-group">
							<label><strong>{{ trans('message.register.fullname') }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></label>
							<label>{{ $lastname . ' ' . $firstname }}</label>
							<input type="hidden" name="firstname" value="{{ $firstname or '' }}">
							<input type="hidden" name="lastname" value="{{ $lastname or '' }}">
						</div>
						@else
							<label><strong>{{ trans('message.register.fullname') }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></label>
							<label>{{ $firstname . ' ' . $lastname }}</label>
							<input type="hidden" name="firstname" value="{{ $firstname or '' }}">
							<input type="hidden" name="lastname" value="{{ $lastname or '' }}">
						@endif
						<div class="form-group">
							<label><strong>{{ trans('message.register.email') }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></label>
							<label>{{ $email or '' }}</label>
							<input type="hidden" name="email" value="{{ $email or '' }}">
						</div>
						<div class="form-group">
							<label><strong>{{ trans('message.register.password') }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></label>
							<label>********</label>
							<input type="hidden" name="password" value="{{ $password or '' }}">
							<input type="hidden" name="password_confirmation" value="{{ $password_confirmation or '' }}" />
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
						<div class="form-group">
							<label>
								<a href="">{{ trans('message.register.kiyaku_title') }}</a>{{ trans('message.register.kiyaku_accepted') }}
							</label>
						</div>
					</div>
				</section>
			</div>
			<button type="button" class="btn btn-lg btn-primary btn-block register">
				{{ trans('button.auth.register') }}
			</button>
			<div class="line line-dashed"></div>
			<p class="text-muted text-center">
				<small>{{ trans('message.register.confirm_modify_desc') }}</small>
			</p>
			<button type="button" class="btn btn-lg btn-default btn-block modify">
				{{ trans('button.auth.modify') }}
			</button>
			<input type="hidden" name="branch1" value="{{ $branch1 or ''}}" />
			<input type="hidden" name="branch2" value="{{ $branch2 or ''}}" />
			<input type="hidden" name="kiyaku" value="{{ $kiyaku or ''}}" />
		</form>
	</section>
</div>
@stop

@section('footer')
<script type="text/javascript">
$('.register').click(function()
{
	$('#confirm_form').prop('action', '{{ url("/auth/create") }}');
	$('#confirm_form').submit(); 
});

$('.modify').click(function()
{
	$('#confirm_form').prop('action', '{{ url("/auth/register?from=modify") }}');
	$('#confirm_form').submit();
});
</script>
@stop