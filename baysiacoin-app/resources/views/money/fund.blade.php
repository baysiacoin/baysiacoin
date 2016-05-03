<?php
use Illuminate\Support\Facades\Session;
$result = Session::pull('state', 'default');
?>
@extends('layouts.user')

@section('header')
<link rel="stylesheet" href="{{ asset('css/jquery/jquery.ui.all.css') }}" type="text/css" />
@stop

@section('content')
<section class="scrollable padder">
	<div class="m-b-md">
		<h3 class="m-b-none">{{ trans('money.fund.title1') }}</h3>
	</div>
	{{ trans('money.fund.title2') }}
	<br>
	<br>
	<b>*{{ trans('money.fund.header') }}</b>
	<section>
		<div class="panel-body">
			<form class="form-horizontal" method="post" action="{{ url('/money/fund') }}">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row" style="padding:0px;">
				    <div class="col-xs-12 col-sm-offset-2 col-sm-8">
					    @if (($result = Session::pull('result')) === SUCCESS)
						    <div class="alert alert-block alert-success">
							    <strong class="green">
									@if (old('curr') == 'STR')
										{{ trans('money.fund.success_str') }}
									@elseif (old('curr') == 'XRP')
										{{ trans('money.fund.success_xrp') }}
									@elseif (old('curr') == 'BTC')
										{{ trans('money.fund.success_btc') }}
									@else
									{{ trans('money.fund.success') }} {{ Session::pull('alert_message') }}
									@endif
								</strong>
						    </div>
					    @endif
					    @if (count($errors) > 0)
						    <div class="alert alert-danger">
							    <strong class="green">{{ trans('money.errors') }}</strong><br><br>
							    <ul>   
								    @foreach ($errors->all() as $error)
									    <li>{{ $error }}</li>
								    @endforeach
							    </ul>
						    </div>
					    @endif
				    </div>
                </div>
				<div class="form-group">
					<label class="col-sm-4 col-sm-offset-2">{{  trans('money.transfer.title4') }}</label>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-2">
						<div class="col-sm-2" style="padding: 5px 10px;">
							<select class="form-control" name="curr">
								@foreach($currencies as $currency)
									<option value="{{ $currency }}" {{ $currency == old('curr') ? 'selected' : '' }}>{{ $currency }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-sm-4" style="padding: 5px 10px;">
							<div>
								<input class="form-control dropdown-toggle" data-toggle="dropdown" name="issuer" placeholder="Gateway Address or Name" value="{{ old('issuer') }}"/>
								<ul class="dropdown-menu" id="issuer_list">
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group" id="tag">
					<label class="col-sm-2 control-label">{{ trans('money.fund.tag_num') }}</label>
					<div class="col-sm-6">
						<input type="text" class="form-control" name="tag" style="background-color:#eee" value="{{ empty(old('tag')) ? $newtag : old('tag') }}">
					</div>
				</div>
				<div class="form-group" id="external_address">
					<label class="col-sm-2 control-label">{{ trans('money.fund.external_address') }}</label>
					<div class="col-sm-6">
						<input type="text" class="form-control" name="ext_addr" value="{{ old('ext_addr')}}">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">{{ trans('money.fund.name') }}</label>
					<div class="col-sm-6">
						<input type="text" class="form-control" name="applicant_name" value="{{ old('applicant_name')}}">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">{{ trans('money.fund.amount') }}</label>
					<div class="col-sm-6">
						<div class="input-group m-b">
							<div class="input-group-btn">
								<div class="btn btn-default" id="currency">{{ empty(old('curr')) ? head($currencies) : old('curr')}} </div>
							</div>
							<input type="text" class="form-control" name="fund_amount" value="{{ old('fund_amount')}}">
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-4 col-sm-offset-2">
						<button type="submit" class="btn btn-primary">{{ trans('money.fund.button') }}</button>
					</div>
				</div>
			</form>
		</div>
	</section>
</section>
@stop

@section('footer')
<script language="javascript">
	$('ul.nav-main li').removeClass('active');
	$('ul.nav-main > li:eq(4)').addClass('active');
	$('li.fund').addClass('active');
	setPreviousURL();
	$('ul.dropdown-menu > li > a').click(function(){
		$('[name=curr]').val($(this).html());
		$('div.dropdown-toggle').html($(this).html() + ' <span class="caret"></span>');
	});
	$('[name=curr]').bind('change', function(e) {
		var value = $(this).val();
		$('#currency').html(value);
		$('[name=issuer]').val('');
		$('[name=issuer]').prop('disabled', true);
		if (value == 'BSC') {
			return;
		} else if (value == 'STR' || value == 'XRP') {
			$('#tag').css('display', 'block');
			$('#external_address').css('display', 'block');
		} else if (value == 'BTC') {
			$('#tag').css('display', 'none');
			$('#external_address').css('display', 'block');
		} else {
			$('#tag').css('display', 'none');
			$('#external_address').css('display', 'none');
		}
		var hasOldIssuer = false;
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			url: "{{ url('/money/gateway-list2/') }}" + "/" + value,
			success: function($msg) {
				var gateways = JSON.parse($msg);
				var gateways_html = "";
				for (var i = 0; i < gateways.length; i++) {
					if (gateways[i] == null) continue;
					if (gateways[i].name == '{{ old('issuer') }}') {
						hasOldIssuer = true;
					}
					gateways_html += '<li><a name="issuer_item" class="cursor-pt" address="' + gateways[i].owner_address + '">' + gateways[i].name + '</a></li>';
				}
				console.log(!gateways_html);
				if (!!gateways_html) {
					$('#issuer_list').html(gateways_html);
					$('[name=issuer]').prop('disabled', false);
				} else {
					$('[name=issuer]').val('');
					$('[name=issuer_item]').html('');
				}
				$('[name=issuer_item]').click(function() {
					$('[name=issuer]').val($(this).html());
				});
				if (!hasOldIssuer) {
					$('[name=issuer_item]:first').trigger('click');
				} else {
					$('[name=issuer]').val('{{ old('issuer') }}');
				}
			}
		});
	});
	$('[name=curr]').trigger('change');
</script>
@stop