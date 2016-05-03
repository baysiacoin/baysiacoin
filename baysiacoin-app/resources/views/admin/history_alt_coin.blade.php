
@extends('admin.layouts.master')

@section('header')
<style type="text/css">
th,td{
	text-align : center;
}
</style>
@stop

@section('content')
	<h2>{{ trans('admin.history.coins.title') }}</h2>
    <div class="clear"></div>
    <hr class="hr-margin-line">
    <hr class="hr-margin">
    <table class="table table-striped" id="list-fees">
        <tr>
            <th width="10%">{{trans('admin.history.coins.market')}}</th>
			<th width="10%">{{trans('admin.history.coins.type')}}</th>
            <th>{{trans('admin.history.coins.address')}}</th>
            <th width="10%">{{trans('admin.history.coins.tag')}}</th>
            <th width="10%">{{trans('admin.history.coins.currency')}}</th>
            <th width="10%">{{trans('admin.history.coins.amount')}}</th>
			<th>{{trans('admin.history.username')}}</th>
        </tr>
        @foreach($coins as $coin)
		<tr>
            <td>{{$coin->market}}</td>
		  @if($coin->type == COIN_FUND)
			<td>{{trans('admin.history.coins.fund')}}</td>
			<td>{{$coin->coin_address}}</td>
		  @else
			<td>{{trans('admin.history.coins.withdraw')}}</td>
			<td>{{$coin->to_address}}</td>
		  @endif
            <td>{{$coin->coin_tag}}</td>
			<td>{{$coin->coin_currency}}</td>
			<td>{{ number_format($coin->coin_amount, 6) }}</td>
		  @if($coin->type == COIN_FUND)
			<td>@if (!empty($coin->relative_id))
					{{$users[$deposits[$coin->relative_id]->user_id]->username}}
					<br/><a href="#">({{trans('admin.history.fund.transaction_id')}}:{{$deposits[$coin->relative_id]->transaction_id}})</a>
				@endif
			</td>
		  @else
			<td>@if (!empty($coin->relative_id))
					{{$users[$withdraws[$coin->relative_id]->user_id]->username}}
					<br/><a href="#">({{trans('admin.history.fund.transaction_id')}}:{{$withdraws[$coin->relative_id]->transaction_id}})</a>
				@endif
			</td>
		  @endif
        </tr>
        @endforeach
    </table>
	
	<div class='row right' id='page_render'><?php echo str_replace('alt_coin/?', 'alt_coin?', $coins->render()) ?></div>
@stop

@section('footer')
<script type='text/javascript'>
</script>

@stop
