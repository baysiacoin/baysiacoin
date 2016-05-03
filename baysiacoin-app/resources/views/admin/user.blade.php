@extends('admin.layouts.master')

@section('header')
<style type="text/css">
#container {
	width: 97%;
}
</style>
@stop
@section('content')
<h2>{{ trans('admin.user.title') }}</h2>
<form class="form-inline" style="float: left" id="frm_condition" method="POST" action="{{ url('/manage/root/user') }}">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<label>{{ trans('common.email') }}</label>&nbsp;:
	<input type="text" class="form-control form-input-control" id="txt_email" name="condition[email]" value="{{ $condition['email'] }}"/>&nbsp;&nbsp;
	<label>{{ trans('common.name') }}</label>&nbsp;:
	<input type="text" class="form-control form-input-control" id="txt_name" name="condition[name]" value="{{ $condition['name'] or ''}}"/>&nbsp;&nbsp;
	<label>{{ trans('common.wallet_address') }}</label>&nbsp;:
	<input type="text" class="form-control form-input-control" id="txt_wallet_address" name="condition[wallet_address]" value="{{ $condition['wallet_address'] or '' }}"/>&nbsp;&nbsp;
	<label>{{ trans('common.tel_number') }}</label>&nbsp;:
	<input type="text" class="form-control form-input-control" id="txt_tel_number" name="condition[tel_number]" value="{{ $condition['tel_number'] or '' }}"/>&nbsp;&nbsp;
	<button type="submit" class="btn btn-primary" name="do_filter" value="ok">{{ trans('button.search') }}</button>
	<button type="button" class="btn btn-primary" name="clear" id="btn_clear">{{ trans('button.clear') }}</button>
</form>
<div class="clear"></div>
<hr class="hr-margin-line">
<hr class="hr-margin">
@if ( is_array(Session::get('error')) )
<div class="alert alert-danger">{{ head(Session::get('error')) }}</div>
@elseif ( Session::get('error') )
<div class="alert alert-danger">{{ Session::get('error') }}</div>
@endif @if ( Session::get('success') )
<div class="alert alert-success">{{ Session::get('success') }}</div>
@endif @if ( Session::get('notice') )
<div class="alert">{{ Session::get('notice') }}</div>
@endif

<div id="messages"></div>

<table class="table table-striped" id="list-fees" style="text-align:center;">
	<tr>
		<th>ID</th>
		<th>{{ trans('admin.user.message.name') }}</th>
		<th>{{ trans('admin.user.message.username') }}</th>
		<th>{{ trans('admin.user.message.email') }}</th>
		<th>{{ trans('admin.user.message.fb_register') }}</th>
		<th>{{ trans('admin.user.message.baysiacoin_address') }}</th>
		<th>{{ trans('admin.user.message.baysiacoin_balance') }}</th>
		<th>{{ trans('admin.user.message.baysiacoin_balance_jpy') }}</th>
		<th>{{ trans('admin.user.message.branch1') }}</th>
		<th>{{ trans('admin.user.message.branch2') }}</th>
		<th>{{ trans('admin.user.message.role') }}</th>
		<th>{{ trans('admin.user.message.identity_confirm') }}</th>
		<th>{{ trans('admin.user.message.mail_confirm') }}</th>
		<th>{{ trans('admin.user.message.action') }}</th>
	</tr>

	@foreach($users as $key => $user)
	<tr>
		<td>{{ $key + 1 + ($page - 1) * X_100__PER_PAGE }}</td>
		<td>
			@if (($locale = Session::get('locale', 'ja')) == 'ja')
				{{ $user['firstname'] }} {{ $user['lastname'] }}
			@elseif ($locale == 'en')
				{{ $user['lastname'] }} {{ $user['firstname'] }}
			@elseif ($locale == 'cn')
				{{ $user['firstname'] }} {{ $user['lastname'] }}	
			@endif
		</td>
		<td>{{ $user['username'] }}</td>
		<td>{{ $user['email'] }}</td>
		<td>
		    @if($user['fb_register'] == USER_REGISTER_FROM_FB)
		        {{ trans('admin.user.message.fb_register_yes') }}
		    @else
		        {{ trans('admin.user.message.fb_register_no') }}
		    @endif
		</td>
		<td name="wallet_address">{{ $user['wallet_address'] }}</td>
		<td name="balance"><img src="{{ asset('images/trade_waiting.png') }}"></td>
		<td name="balance_jpy"><img src="{{ asset('images/trade_waiting.png') }}"></td>
		{{--<td name="temp_balance"> {{ $user['balance'] == 0 ? 0 : number_format($user['balance'], 6) }}</td>--}}
		<td>{{ $user['branch1'] }}</td>
		<td>{{ $user['branch2'] }}</td>
		<td>
			<?php $i=1; ?> 
			@foreach($user['roles'] as $role) 
				@if($i==1)
				{{ $role['name'] }} 
				@else , 
				{{ $role['name'] }} 
				@endif
			<?php $i++; ?>
			@endforeach
		</td>
		<td style="text-align: center;">
			@if($user['licensed'] == USER_LICENSE_CHECKED)
			{{ trans('admin.user.message.confirmed') }}
			@else
			{{ trans('admin.user.message.unconfirmed') }}
			@endif
		</td>
		@if($user['verified'])
		<td>{{trans('admin.user.message.verified') }}</td>
		@else
		<td class="status">{{trans('admin.user.message.unverified') }}</td>
		@endif
		@if( $login_user->hasRole(ROLE_ADMIN) )
		<td>
			<a href="{{ url('/manage/root/user/edit') }}/{{ $user['id'] }}">{{ trans('button.edit') }}</a> |
			<span name="href_delete">
				<a href="#" onclick="javascript:notyConfirm('{{ $user['id'] }}');" >
				{{ trans('button.delete') }}
				</a>
			</span>
		</td>
		@else
		<td>
			<a href="{{ url('/manage/root/user/edit') }}/{{ $user['id'] }}">{{ trans('button.edit') }}</a>
		</td>
		@endif
	</tr>
	@endforeach
</table>
<div id="pager"></div>
@stop

@section('footer')
<script src="{{ asset('js/bootstrap-paginator.js') }}"></script>
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/stellar/stellar-lib.js') }}"></script>
<script type='text/javascript'>
var shown = false;
var options = {
    currentPage: '<?php echo $page ?>',
    totalPages: '<?php echo $total_pages ?>',
    alignment:'right',
    pageUrl: function(type, page, current){
    	return "<?php echo URL::to('/manage/root/user'); ?>" + '?page=' + page;
    }
}

$(document).ready(function() {
	var Remote = stellar.Remote;
	var remote = new Remote({
		// see the API Reference for available options
		trusted:        true,
		local_signing:  true,
		local_fee:      true,
		fee_cushion:     1.5,
		servers: [
			{
				host:    'baysia.asia'
				, port:    9001
				, secure:  true
			}
		]
	});
	//
	remote.connect(function() {
		//remote.on('transaction_all', transactionListener);
		//            remote.on('ledger_closed', ledgerListener);
	});
	var getAccount_Info = function(account, toObj){
		var request = remote.requestAccountInfo({
			"account": account
		});
		request.callback(function(err, res){
//			console.log(res);
			if (!err) {
				/*if (res.account_data.Balance == 0 || res.lines.length == 0) {
					toObj.html('0');
					return;
				}
				var lines = res.lines;
				//			alert(lines);
				//			console.log(lines);
				/!*for (var line in lines) {
				 alert(line);
				 console.log(line);
				 if ("BSC" == line.currency) {
				 alert(line.balance);
				 toObj.html(line.balance);
				 }
				 }*!/
				var sumOfBalance = 0;
				for (var i = 0; i < lines.length; i++) {
					if ("BSC" == lines[i].currency) {
						//alert(line.balance);
						sumOfBalance += parseFloat(lines[i].balance);
						continue;
					}
					//toObj.html('0');
				}*/
				toObj.html(number_format(res.account_data.Balance / 1000000, 6));
			} else {
				toObj.html('0');
			}
		});
		request.request();
	};
	var getAccount_Lines = function(account, peer, curr, toObj){
		var request = remote.requestAccountLines({
			"account": account,
			"peer": peer
		});
		request.callback(function(err, res){
			//console.log(err);
//			console.log(res);
			if (!err) {
				if (res.length == 0 || res.lines.length == 0) {
					toObj.html('0');
					return;
				}
				var lines = res.lines;
	//			alert(lines);
	//			console.log(lines);
				/*for (var line in lines) {
				 alert(line);
				 console.log(line);
				 if ("BSC" == line.currency) {
				 alert(line.balance);
				 toObj.html(line.balance);
				 }
				 }*/
				var sumOfBalance = 0;				
				for (var i = 0; i < lines.length; i++) {
					if (curr == lines[i].currency) {
						//alert(line.balance);
						sumOfBalance += parseFloat(lines[i].balance);
						continue;
					}
					//toObj.html('0');
				}				
				toObj.html(sumOfBalance == 0 ? 0 : number_format(sumOfBalance, 6));
			} else {
				toObj.html('0');
			}
		});

		return request.request();
	};
	$('[name=wallet_address]').each(function() {
		var account = $(this).html();
		if ("" != account) {
//			getAccount_Info(account);			
			getAccount_Info(account, $(this).next('td'));
			getAccount_Lines(account, '{{$gateway_jpy}}', 'JPY', $(this).next('td').next('td'));			
		} else {
			$(this).next('td').html('0');
		}
	});

	$("#btn_clear").click(function(){
		$("#frm_condition input").not("[name='_token']").val("");
		$("#txt_email").val("");
		$("#txt_name").val("");
		$("#txt_wallet_address").val("");
		$("#txt_tel_number").val("");
    });
	$('#pager').bootstrapPaginator(options);

});
function notyConfirm(obj){
	if (shown == true) return;
	shown = true;
	noty({
		text: '{{ trans('admin.user.confirm_delete') }}',
		layout: 'topRight',
		buttons: [
			{addClass: 'btn btn-success btn-clean', text: 'Ok', onClick: function($noty) {
				$noty.close();
				shown = false;
				location.href = '{{ url('/manage/root/user/delete') }}/' + obj;
			}
			},
			{addClass: 'btn btn-danger btn-clean', text: 'Cancel', onClick: function($noty) {
				$noty.close();
				shown = false;
//				$('[name=href_delete]').prop('disabled', true);
				//noty({text: 'You clicked "Cancel" button', layout: 'topRight', type: 'error'});
			}
			}
		]
	})
}
function number_format(value, offset) {
	var s = parseFloat(Math.floor(parseFloat(value) * 1000000) / 1000000).toString();
	if (s.indexOf('.') == -1) s += '.';
	while (s.length < s.indexOf('.') + offset + 1) s += '0';
	s = s.substr(0, s.indexOf('.') + offset + 1);
	return s;
}
</script>
@stop