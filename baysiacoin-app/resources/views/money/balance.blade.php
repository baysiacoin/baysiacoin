@extends('layouts.user')

@section('header')
<link rel="stylesheet" href="{{ asset('css/jquery/jquery.ui.all.css') }}" type="text/css" />
@stop

@section('content')
<section class="scrollable padder bg-white">
	<div class="m-b-md">
		<h3 class="m-b-none">{{ trans('menu.account_balance') }}</h3>
	</div>
	<div class="col-sm-10">
        <div class="col-md-12">
            <a href="#" class="block padder-v hover">
              <span class="clear">
                <span class="h3 block m-t-xs text-danger" id="BSC"><img src="{{ asset('images/waiting.gif') }}"></span>
                <small class="text-muted text-u-c">{{ COIN_BSC }}</small>
              </span>
            </a>
        </div>
    </div>
    <div class="col-sm-10" id="balance_board"></div>
</section>
@stop

@section('footer')
<script language="javascript">
	$('ul.nav-main li').removeClass('active');
	$('ul.nav-main > li:eq(0)').addClass('active');
</script>
<script language="javascript" src="{{asset('js/stellar/stellar-lib.js')}}"></script>
<script language="javascript">
    var gateways = JSON.parse('{{ json_encode($gateways) }}'.replace(/&quot;/ig,'"'));
    console.log(gateways);
    $(document).ready(function(){
        setPreviousURL();
        getAccount_Balance();
        requestBalance2Server();
    });
    var getAccount_Balance = function() {
        var Remote = stellar.Remote;
        var remote = new Remote({
            // see the API Reference for available options
            trusted: true,
            local_signing: true,
            local_fee: true,
            fee_cushion: 1.5,
            servers: [
                {
                    host: 'baysia.asia',
                    port: 9001,
                    secure: true
                }
            ]
        });
        remote.connect(function () {
          remote.on('transaction_all', transactionListener);
//          remote.on('ledger_closed', ledgerListener);
        });
        var request = remote.requestServerInfo();
        request.callback(function(err, res) {
            if (!err) {
                console.log(res);
            }
        });
        request = remote.requestAccountBalance({
            account: "{{ $wallet_address }}"
        });
        request.callback(function (err, res) {
            console.log(err);
            if (!err) {
                console.log(res);
                $('#BSC').html(number_format(res.node.Balance / 1000000, 6));
            }
        });
        request.request();
        request = remote.requestAccountLines({
            account: "{{ $wallet_address }}"
        });
        request.callback(function (err, res) {
            console.log(err);
            if (!err) {
                var currs = [];
                var balance = [];
                $('#balance_board').html('');
                console.log(res.lines);
                res.lines.forEach(function(line) {
                    currs = currs.concat(line.currency);
                });
                currs = currs.reduce(function(a, b){
                            if(a.indexOf(b) < 0) a.push(b);
                            return a;
                        },[]);
                currs.forEach(function(curr, index) {
                    var sub_html = "";
                    var filter_lines =
                            res.lines.filter(function(line) {
                                return line.currency == curr;
                            });
                    balance[index] = 0;
                    filter_lines.forEach(function(filter_line) {
                        if (filter_line.limit != 0) {
                            balance[index] += parseFloat(filter_line.balance);
                        }
                        if (gateways.length == 0) {
                            sub_html += '<span class="clear"><span class="h5 block m-t-xs text-info">' + number_format(parseFloat(filter_line.balance), 6) +
                                    '</span><small class="text-muted">' + filter_line.account + '</small></span>';
                        } else {
                            gateways.some(function (gateway, index) {
                                var gateway_name;
                                if (gateway.owner_address == filter_line.account) {
                                    gateway_name = gateway.name;
                                } else if (index == gateways.length - 1) {
                                    gateway_name = filter_line.account;
                                }
                                if (gateway_name) {
                                    sub_html += '<span class="clear"><span class="h5 block m-t-xs text-info">' + number_format(parseFloat(filter_line.balance), 6) +
                                            '</span><small class="text-muted">' + gateway_name + '</small></span>';
                                    return true;
                                }
                            });
                        }
                    });
                    $('#balance_board').append('<div class="col-md-12"><a href="#" class="block padder-v hover">' +
                            '<span class="clear"><span class="h3 block m-t-xs text-success">' + balance[index] + '</span>' +
                            '<small class="text-muted text-u-c">' + curr + '</small></span>' + sub_html + '</a></div>');
                });
            }
        });
        request.request();
    }
    function transactionListener(res) {
        console.log(res);
        getAccount_Balance();
    }
    function requestBalance2Server() {
        $.getJSON('{{ url('money/raw-balances/') }}', 'base=true', function(res) {
            if (res.status == 'Fail') {
                //alert('Failed to connect to the server!');
                //console.log(res.message);
                return;
            } else if (res.status == 'Success') {
                res = res.raw;
            }
            $('#BSC').html(number_format(res.account_data.Balance / 1000000, 6));
        });
        $.getJSON('{{ url('money/raw-balances') }}', function(res) {
            if (res.status == 'Fail') {
                //alert('Failed to connect to the server!');
                console.log(res.message);
                return;
            } else if (res.status == 'Success') {
                res = res.raw;
            }
            var currs = [];
            var balance = [];
            $('#balance_board').html('');
            res.lines.forEach(function(line) {
                currs = currs.concat(line.currency);
            });
            currs = currs.reduce(function(a, b){
                if(a.indexOf(b) < 0) a.push(b);
                return a;
            },[]);
            currs.forEach(function(curr, index) {
                var sub_html = "";
                var filter_lines =
                        res.lines.filter(function(line) {
                            return line.currency == curr;
                        });
                balance[index] = 0;
                filter_lines.forEach(function(filter_line) {
                    if (filter_line.limit != 0) {
                        balance[index] += parseFloat(filter_line.balance);
                    }
                    if (gateways.length == 0) {
                        sub_html += '<span class="clear"><span class="h5 block m-t-xs text-info">' + number_format(parseFloat(filter_line.balance), 6) +
                                '</span><small class="text-muted">' + filter_line.account + '</small></span>';
                    } else {
                        gateways.some(function (gateway, index) {
                            var gateway_name;
                            if (gateway.owner_address == filter_line.account) {
                                gateway_name = gateway.name;
                            } else if (index == gateways.length - 1) {
                                gateway_name = filter_line.account;
                            }
                            if (gateway_name) {
                                sub_html += '<span class="clear"><span class="h5 block m-t-xs text-info">' + number_format(parseFloat(filter_line.balance), 6) +
                                        '</span><small class="text-muted">' + gateway_name + '</small></span>';
                                return true;
                            }
                        });
                    }
                });
                $('#balance_board').append('<div class="col-md-12"><a href="#" class="block padder-v hover">' +
                        '<span class="clear"><span class="h3 block m-t-xs text-success">' + balance[index] + '</span>' +
                        '<small class="text-muted text-u-c">' + curr + '</small></span>' + sub_html + '</a></div>');
            });
        })
        .fail(function(){
            //alert('The server is not running!');
        });
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