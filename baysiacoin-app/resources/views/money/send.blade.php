@extends('layouts.user')

@section('header')
<link rel="stylesheet" href="{{ asset('css/jquery/jquery.ui.all.css') }}" type="text/css" />
@stop

@section('content')
<section class="scrollable padder">
  <div class="m-b-md">
    <h3 class="m-b-none">{{trans('money.transfer.title1')}}</h3>
  </div>
* {{trans('money.transfer.title2')}}
<br><br>
  <section>
    <div class="panel-body">
      <form class="form-horizontal" method="post" action="{{ url('/money/transfer') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="row" style="padding:0px;">
            <div class="col-xs-12 col-sm-offset-2 col-sm-8">
                @if (($result = Session::pull('result')) === SUCCESS)
                    <div class="alert alert-block alert-success">
                        <strong class="green">{{ trans('money.transfer.success') }}</strong>
                    </div>
                @elseif ($result === FAIL)
                  <div class="alert alert-block alert-danger">
                    <strong class="green">{{ trans('money.transfer.fail') }}</strong>
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
                <option>{{ COIN_BSC }}</option>
                @foreach($currencies as $currency)
                  <option value="{{ $currency }}" {{ $currency == old('curr') ? 'selected' : '' }}>{{ $currency }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-sm-4" style="padding: 5px 10px;">
              <div>
                <input class="form-control dropdown-toggle" data-toggle="dropdown" name="issuer" placeholder="Gateway Address or Name" />
                <ul class="dropdown-menu" id="issuer_list">
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group" style="margin-bottom:0px;">
          <label class="col-sm-4 col-sm-offset-2">{{ trans('money.transfer.title3') }}</label>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">{{ trans('money.transfer.wallet_address') }}:</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" name="wallet_address" value="{{ $to_account or ''}}">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">{{ trans('money.transfer.amount') }}:</label>
          <div class="col-sm-6">
            <div class="input-group m-b">
              <div class="input-group-btn">
                <div class="btn btn-default" id="currency">{{ COIN_BSC }}</div>
              </div>
              <input type="text" class="form-control" name="transfer_amount">
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-4 col-sm-offset-2">
            <button type="submit" class="btn btn-primary">{{ trans('money.transfer.button') }}</button>
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
$('ul.nav-main > li:eq(3)').addClass('active');
/*$('ul.dropdown-menu#currency_dropdown > li > a').click(function(){
	$('button.dropdown-toggle#currency_toggle').html($(this).html()+' <span class="caret"></span>');
    $('#currency').val($(this).html());
});
$('ul.dropdown-menu#gateway_dropdown > li > a').click(function(){
  $('button.dropdown-toggle#gateway_toggle').html($(this).html()+' <span class="caret"></span>');
  $('#gateway').val($(this).html());
});*/
setPreviousURL();
$('[name=curr]').bind('change', function(e) {
  $('#currency').html($(this).val());
  $('[name=issuer]').val('');
  $('[name=issuer]').prop('disabled', true);
  if ($(this).val() == 'BSC') return;
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  $.ajax({
    url: "{{ url('/money/gateway-list2/') }}" + "/" + $(this).val(),
    success: function($msg) {
      var gateways = JSON.parse($msg);
      console.log(gateways);
      var gateways_html = "";
      for (var i = 0; i < gateways.length; i++) {
        if (gateways[i] == null) continue;
        gateways_html += '<li><a name="issuer_item" class="cursor-pt" address="' + gateways[i].owner_address + '">' + gateways[i].name + '</a></li>';
      }
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
      $('[name=issuer_item]:first').trigger('click');
    }
  });
});
$('[name=curr]').trigger('change');
</script>
@stop