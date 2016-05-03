@extends('admin.layouts.master')

@section('header')
<style type="text/css">
th,td{
	text-align : center;
}
</style>
@stop

@section('content')
<h2>{{ trans('admin.history.withdraw.title') }}</h2>
    <form class="form-inline" style="float: left" id="frm_condition" method="POST" action="{{url('/manage/root/history/withdraw')}}">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <div class="form-group">                                                                                
            <label>{{trans('admin.edit.wallet.title')}}</label> :
            <select class="form-control" id="currency" name="search_currency">
                <option value="0">{{trans('admin.history.all')}}</option>
            @foreach($currencies as $currency)
                <option value="{{ $currency }}">{{ $currency }}</option>
            @endforeach
			</select>
            @if ($login_user->hasRole(ROLE_ADMIN))
                <label>{{trans('admin.edit.wallet.gateway')}}</label> :
                <select class="form-control" id="issuer" name='search_issuer' style="width: 180px;">
                    <option value="0">{{trans('admin.history.all')}}</option>
                </select>
            @endif
            <label>{{trans('admin.history.status')}}</label> :
            <select class="form-control" id="status" name='search_status'>
                <option value="0">{{trans('admin.history.all')}}</option>
                <option value="{{WITHDRAW_REQUESTED}}">{{trans('admin.history.withdraw.requested')}}</option>
                <option value="{{WITHDRAW_CONFIRMED}}">{{trans('admin.history.withdraw.completed')}}</option>
                <option value="{{WITHDRAW_FAILED}}">{{trans('admin.history.withdraw.failed')}}</option>
            </select>
            <label>{{trans('admin.history.username')}}</label> :
            <input type="text" class="form-control" id="txt_username" name="search_username" value="{{$search_username}}"/>   
			&nbsp;
			<button type="submit" class="btn btn-primary" name="do_filter" value="ok">{{trans('admin.history.search')}}</button>
			<button type="button" class="btn btn-primary" name="clear" id="btn_clear">{{trans('admin.history.clear')}}</button>
        </div>
    </form>
    <div class="clear"></div>
    <hr class="hr-margin-line">
    <hr class="hr-margin">
    <div id="messages"></div>
    <table class="table table-striped" id="list-fees">
        <tr>
            <th>No</th>
            <th>{{trans('admin.history.fund.transaction_id')}}</th>
            <th>{{trans('admin.history.username')}}</th>
            <th>{{trans('admin.history.amount')}}</th>
            <th>{{trans('admin.history.withdraw.fee_amount')}}</th>
            <th>{{trans('admin.history.withdraw.receive_amount')}}</th>
            <th>{{trans('admin.history.currency')}}</th>
            @if ($login_user->hasRole(ROLE_ADMIN))
                <th>{{trans('admin.history.issuer_address')}}</th>
            @endif
            <th>{{trans('admin.history.tag')}}</th>
            <th>{{trans('admin.history.external_address')}}</th>
            <th>{{trans('admin.history.created_at')}}</th>
            <th>{{trans('admin.history.status')}}</th>
        </tr>
        <?php $i = PER_PAGE * ($withdraws->currentPage() - 1) ?>
        @foreach($withdraws as $withdraw)
		<tr>
            <td>{{ ++$i }}</td>
            <td>{{ $withdraw->transaction_id }}<br>( {{ empty($withdraw->name) ? '- - - - -' : $withdraw->name}} )</td>
            <td style="text-align: center;"><a href='{{url('/manage/root/user/edit')}}/{{$withdraw->getUser()['id']}}'>{{$withdraw->getUser()['username']}}</a><br>&nbsp;({{$withdraw->getUser()['firstname']}} {{$withdraw->getUser()['lastname']}})</td>
            <td style="text-align: right;">{{$withdraw->amount}}</td>
            <td style="text-align: right;">{{$withdraw->fee_amount}}</td>
            <td style="text-align: right;">{{$withdraw->receive_amount}}</td>
            <td>{{ $withdraw->currency }}</td>
            @if ($login_user->hasRole(ROLE_ADMIN))
                <td>
                    {{ $withdraw->issuer }}
                </td>
            @endif
            <td>
                {{ $withdraw->tag }}
            </td>
            <td>
                {{ $withdraw->external_address }}
            </td>
            <td>{{$withdraw->created_at}}</td>
            <td>
	        @if($withdraw->status==WITHDRAW_CONFIRMED)
				<b style="color:green">{{trans('admin.history.withdraw.completed')}}</b>
	        @elseif ($withdraw->status==WITHDRAW_REQUESTED)
				<b style="color:red">{{trans('admin.history.withdraw.requested')}}</b><br>
				<a href="{{URL::to('manage/root/history/withdraw_complete')}}/{{$withdraw->id}}" class="edit_page">
                    <button class="btn btn-primary">
                        {{trans('admin.history.withdraw.confirmed')}}
                    </button>
                </a>
	        @else
				{{trans('admin.history.withdraw.failed')}}
	        @endif
            </td>
        </tr>
        @endforeach
    </table>
	
	<div class='row right' id='page_render'><?php echo str_replace('withdraw/?', 'withdraw?', $withdraws->render()) ?></div>

@stop

@section('footer')
<script type='text/javascript'>
$(document).ready(function() {
    $("#btn_clear").click(function(){
        $("select").val('0');
        $("#txt_username").val("");
    });
});
$("#currency option[value='{{$search_currency}}']").attr('selected', 'selected');
$("#status option[value='{{$search_status}}']").attr('selected', 'selected');

@if ($login_user->hasRole(ROLE_ADMIN))
    $('[name=search_currency]').bind('change', function(e) {
        var value = $(this).val();
        //        alert(value);
        //$('#currency').html(value);
        $('[name=search_issuer]').val('');
        $('[name=search_issuer]').prop('disabled', true);

        if (value == '0') {
            $('[name=search_issuer]').prop('disabled', false);
            $('[name=search_issuer]').html('<option value="0">{{trans('admin.history.all')}}</option>');
            return;
        } /*else if (value == 'STR' || value == 'XRP') {
         $('#tag').css('display', 'block');
         $('#external_address').css('display', 'block');
         } else {
         $('#tag').css('display', 'none');
         $('#external_address').css('display', 'none');
         }*/
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ url('/money/gateway-list2/') }}" + "/" + value,
            success: function($msg) {
                var gateways = JSON.parse($msg);
                console.log(gateways);
                var gateways_html = "";
                for (var i = 0; i < gateways.length; i++) {
                    if (gateways[i] == null) continue;
                    gateways_html += '<option value="' + gateways[i].owner_address + '"><a name="issuer_item" class="cursor-pt" address="' + gateways[i].owner_address + '">' + gateways[i].name + '</a></option>';
                }
                gateways_html = '<option value="0">{{trans('admin.history.all')}}</option>' + gateways_html;
                console.log(!gateways_html);
                if (!!gateways_html) {
                    $('[name=search_issuer]').html(gateways_html);
                    $('[name=search_issuer]').prop('disabled', false);
                    $("#issuer option[value='{{$search_issuer}}']").attr('selected', 'selected');
                } else {
                    $('[name=search_issuer]').val('');
                    $('[name=issuer_item]').html('');
                }
                /*$('[name=issuer_item]').click(function() {
                 $('[name=issuer]').val($(this).html());
                 });
                 $('[name=issuer_item]:first').trigger('click');*/
            }
        });
    });
    $('[name=search_currency]').trigger('change');
@endif
</script>
@stop