<?php
use Illuminate\Support\Facades\Session;
$state = Session::pull('state', 'default');
?>
@extends('layouts.user')

@section('header')
<link rel="stylesheet" href="{{ asset('css/jquery/jquery.ui.all.css') }}" type="text/css" />
@stop

@section('content')
<style type='text/css'>
  /*#balance,
  #trust_received,
  #trust_given,
  #offers,
  #incoming_transactions {
    font-weight: bold;
  }*/
  table th {
     color:#08B5E5
  }
</style>
<section class="scrollable padder">
  <div class="m-b-md">
    <h3 class="m-b-none">{{trans('money.view.title')}}</h3>
  </div>


  <section class="panel-default">
    <div class="panel-body">
        <form class="form-horizontal" method="post" action="#">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="row" style="padding:0px;">
                <div class="col-xs-12 col-sm-offset-2 col-sm-8">
                    @if ($state === SUCCESS)
                        <div class="alert alert-block alert-success">
                            <strong class="green">{{ trans('money.view.success') }}</strong>
                        </div>
                    @elseif ($state === FAIL)
                      <div class="alert alert-block alert-danger">
                        <strong class="green">{{ trans('money.view.fail') }}</strong>
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
            <div style="height: 30px;"></div>
            <div class="form-group">
              <label class="col-sm-3 control-label">{{ trans('money.view.wallet_address') }}</label>
              <div class="col-sm-4 col-sm-offset-1">
                <input type="text" class="form-control" name="wallet_address">
                <span class="help-block m-b-none">{{ trans('money.view.title1') }}</span>
              </div>
            </div>
            <div class="pull-in"></div>
            <div class="form-group">
              <div class="col-sm-3 col-sm-offset-4">
                <div id="search" class="btn btn-primary">{{ trans('money.view.button') }}</div>
              </div>
            </div>
            <div class="pull-in"></div>
            <div class="form-group">
              <div class="col-sm-3 control-label" id="loading" style="font-size: 20px;">
              </div>
            </div>
            <div class="pull-in"></div>
            <div class="form-group" id="result">
              <div class="col-sm-3 control-label">
                Balance
              </div>
              <div class="col-sm-3 col-sm-offset-1" id="balance">
                No Balance                
              </div>
            </div>
            <div class="pull-in"></div>
            <div class="form-group" id="result">
              <div class="col-sm-3 control-label">
                Trust Received
              </div>
              <div class="col-sm-8 col-sm-offset-1" id="trust_received">
                No trust lines received
              </div>
            </div>
            <div class="pull-in"></div>
            <div class="form-group" id="result">
              <div class="col-sm-3 control-label">
                Trust Given
              </div>
              <div class="col-sm-8 col-sm-offset-1" id="trust_given">
                No trust lines given
              </div>
            </div>
            <div class="pull-in"></div>
            <div class="form-group" id="result">
              <div class="col-sm-3 control-label">
                Offers
              </div>
              <div class="col-sm-8 col-sm-offset-1" id="offers">
                No Offers
              </div>
            </div>
            <div class="pull-in"></div>
            <div class="form-group" id="result">
              <div class="col-sm-3 control-label">
                Incoming Transactions
              </div>
              <div class="col-sm-8 col-sm-offset-1" id="incoming_transactions">
                No incoming transactions
              </div>
            </div>
        </form>
    </div>
  </section>
</section>
@stop

@section('footer')
<script language="javascript" src="{{asset('js/stellar/stellar-lib.js')}}"></script>
<script language="javascript">
$('ul.nav-main li').removeClass('active');
$('ul.nav-main > li:eq(6)').addClass('active');
setPreviousURL();
$('ul.dropdown-menu > li > a').click(function(){
	$('button.dropdown-toggle').html($(this).html()+' <span class="caret"></span>');
    $('#currency').val($(this).html());
});
var account = "";
$('div#search').click(function(){
  var account = $('[name=wallet_address]').val();
  if (!!account) {
    $('#loading').html('Loading...');
    startSearch(account);
  }  
});
var startSearch = function(account) {
    var sumOfBSC = 0;
    var sumOfJPY = 0;    
    var Remote = stellar.Remote;
    var remote = new Remote({
    // see the API Reference for available options
      trusted: true,
      local_signing: true,
      local_fee: true,
      fee_cushion: 1.5,
      servers: [{
      host: 'baysia.asia'
      , port: 9001
      , secure: true
      }]
    });
    /*var remote = new Remote({
    // see the API Reference for available options
      trusted: true,
      local_signing: true,
      local_fee: true,
      fee_cushion: 1.5,
      servers: [{
      host: '52.69.231.154'
      , port: 9001
      , secure: false
      }]
    });*/
    remote.connect(function () {
      remote.on('transaction_all', transactionListener);
      remote.on('ledger_closed', ledgerListener);
    });
    //account: "{{-- $wallet_address --}}",
    var request = remote.requestAccountBalance(account);
    request.callback(function (err, res) {
      console.log(err);
      if (!err) {
        console.log(res);
        $('#loading').html('');
        $('#balance').html('<div style="font-weight: bold;font-size:25px;">' + number_format(res.node.Balance / 1000000, 6) + '</div><div>BSC</div><div>&nbsp;</div>');
      }
    });
    request.request();
    request = remote.requestAccountLines(account);
    request.callback(function (err, res) {
      console.log(err);
      if (!err) {
        console.log(res);
        var trust_received_html = '';
        var trust_given_html = '';
        for (var i = 0; i < res.lines.length; i++) {
          if (res.lines[i].currency == 'BSC') {
            sumOfBSC += parseFloat(res.lines[i].balance);
            //$('#BSC').html(number_format(res.lines[i].balance, 6));
  //                        balance[1] = res.lines[i].balance;
          } else if (res.lines[i].currency == 'JPY') {
            sumOfJPY += parseFloat(res.lines[i].balance);
  //                        balance[2] = res.lines[i].balance;
          }
          if (res.lines[i].limit == 0) {
            trust_received_html += '<tr><td>' + res.lines[i].currency + '</td><td>' + res.lines[i].balance + '</td><td>' + res.lines[i].limit_peer + '</td><td>' + res.lines[i].account + '</td></tr>';  
          } else {
            trust_given_html += '<tr><td>' + res.lines[i].currency + '</td><td>' + res.lines[i].balance + '</td><td>' + res.lines[i].limit + '</td><td>' + res.lines[i].account + '</td></tr>';  
          }          
        }
        //$('#balance').append('<div style="font-weight: bold;font-size:25px;color: red;">' + number_format(sumOfBSC, 6) + '</div><div>BSC</div><div>&nbsp;</div>');
        $('#balance').append('<div style="font-weight: bold;font-size:25px;color: red;">' + number_format(sumOfJPY, 6) + '</div><div>JPY</div><div>&nbsp;</div>');
        if (!!trust_received_html) {
          trust_received_html = '<table width="100%"><tr><th>Currency</th><th>Balance</th><th>Limit</th><th>From Account</th></tr>' + trust_received_html + '</table>';
          $('#trust_received').html(trust_received_html);          
        } else {
          $('#trust_received').html('<b>No trust lines received</b>');          
        }
        if (!!trust_given_html) {
          trust_given_html = '<table width="100%"><tr><th>Currency</th><th>Balance</th><th>Limit</th><th>From Account</th></tr>' + trust_given_html + '</table>';
          $('#trust_given').html(trust_given_html);
        } else {
          $('#trust_given').html('<b>No trust lines given</b>');          
        }
      }
    });
    request.request();

    var param = { 
              account: "",
              ledger_index_min: "-1",
              ledger_index_max: "-1",
              forward: "true",
              limit: "1000"
            };
    param.account = account;
    var request = remote.requestAccountTx(param);
    request.callback(function (err, res) {
      console.log(err);
      console.log(res);
      /*if (!err) {
        console.log(res);
        for (var i = 0; i < res.lines.length; i++) {
          if (res.lines[i].currency == 'BSC') {
            sumOfBSC += parseFloat(res.lines[i].balance);
            //$('#BSC').html(number_format(res.lines[i].balance, 6));
  //                        balance[1] = res.lines[i].balance;
          } else if (res.lines[i].currency == 'JPY') {
            sumOfJPY += parseFloat(res.lines[i].balance);
  //                        balance[2] = res.lines[i].balance;
          }
        }
        $('#balance').append('<div style="font-weight: bold;font-size:25px;color: red;">' + number_format(sumOfBSC, 6) + '</div><div>BSC</div><div>&nbsp;</div>');
        $('#balance').append('<div style="font-weight: bold;font-size:25px;color: red;">' + number_format(sumOfJPY, 6) + '</div><div>JPY</div><div>&nbsp;</div>');*/
      //}
    });
    /* setTimeout(function(){
    $(this).trigger('connection_timeout');
    //Displays stored data when connection times out.
    $(this).bind("connection_timeout", search_data());
    }, 2000);*/
    request.request();
    function transactionListener (transaction_data) {
  //            var array = $.map(transaction_data, function(value, index) {
  //            return [value];
  //            });
  //            var array = $.map(transaction_data, function(arr) {
  //            return arr;
  //            });
        console.log(transaction_data);
        var account = $('[name=wallet_address]').val();
        if (!!account) {
          $('#loading').html('Loading...');
          $('#incoming_transactions').html('A new transaction is coming...')
          startSearch(account);
        }  
        //alert(array);

        // handle transaction_data
        // see https://www.stellar.org/api/#api-subscribe for the format of transaction_data
    }

    function ledgerListener (ledger_data) {
        //console.log(ledger_data);
        var account = $('[name=wallet_address]').val();
        if (!!account) {
          $('#loading').html('Loading...');
          $('#incoming_transactions').html('');
          //startSearch(account);
        }  
        // handle ledger_data
        // see https://www.stellar.org/api/#api-subscribe for the format of ledger_data
    }
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