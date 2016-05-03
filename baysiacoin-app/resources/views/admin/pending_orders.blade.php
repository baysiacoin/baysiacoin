@extends('admin.layouts.master')

@section('header')
<link rel="stylesheet" type="text/css" href="{{ asset('css/jquery/jquery.ui.all.css') }}" />
<style type="text/css">
#container {
    width: 97%;
}
.curr_board {
    margin: 25px 0px 70px;
}
select {
    width: 33% !important;
    float:left;
}
.dropdown {
    width: 67% !important;
    float:left;
}
.flip {
    width: 2.5%;
    float: left;
}
</style>
@stop
@section('content')
<h2>{{ trans('admin.offers.title') }}</h2>
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
<div class="curr_board">
    <div class="col-sm-3">
        <select class="form-control" id="pri_curr" name="pri_curr">
            <option value="{{ COIN_BSC }}">{{ COIN_BSC }}</option>
            @foreach ($currs as $curr)
                <option value="{{ $curr }}">{{ $curr }}</option>
            @endforeach
        </select>
        <div class="dropdown">
            <input class="form-control dropdown-toggle" data-toggle="dropdown" id="pri_issuer" name="pri_issuer" placeholder="Gateway Address or Name" />
            <ul class="dropdown-menu" id="pri_issuer_list">
            </ul>
        </div>
    </div>
    <div class="flip">
        <span><img src="{{ asset('images/arrow_gray.png') }}" alt="scale"></span>
    </div>
    <div class="col-sm-3">
        <select class="form-control" id="sec_curr" name="sec_curr">
            <option value="{{ COIN_BSC }}">{{ COIN_BSC }}</option>
            @foreach ($currs as $curr)
                <option value="{{ $curr }}" {{ $curr == head($currs) ? 'selected' : '' }}>{{ $curr }}</option>
            @endforeach
        </select>
        <div class="dropdown">
            <input class="form-control dropdown-toggle" data-toggle="dropdown" id="sec_issuer" name="sec_issuer" placeholder="Gateway Address or Name" />
            <ul class="dropdown-menu" id="sec_issuer_list">
            </ul>
        </div>
    </div>
</div>

<div class="col-sm-6">
    <table class="table table-striped" id="list-sells" style="text-align:center;">
        <thead>
        <tr>
            <th style="text-align: center" colspan="100">BIDS</th>
        </tr>
        <tr style="font-weight: bold">
            <td style="text-align: center">{{ trans('admin.user.message.name') }}</td>
            <td style="text-align: center">{{ trans('admin.user.message.email') }}</td>
            <td style="text-align: center">{{ trans('money.trade.bid_price') }} &nbsp; ( <span name="2ndCurrency">&nbsp;{{ COIN_JPY }}</span> ) &nbsp; </td>
            <td style="text-align: center">{{ trans('money.trade.size') }} &nbsp; ( <span name="primaryCurrency">&nbsp;{{ COIN_BSC }}</span> ) &nbsp; </td>
            <td style="text-align: center">{{ trans('money.trade.total') }} &nbsp; ( <span name="2ndCurrency">{{ COIN_JPY }}</span> ) &nbsp; </td>
        </tr>
        </thead>
        <tbody id="bid_offers" style="text-align: center;">
            <tr>
                <td colspan="100">
                    <img  class="loader" src="{{ asset('images/trade_waiting.png') }}" />
                </td>
            </tr>
        </tbody>
    </table>
</div>
<div class="col-sm-6">
    <table class="table table-striped" id="list-sells" style="text-align:center;">
        <thead>
        <tr>
            <th style="text-align: center" colspan="100">ASKS</th>
        </tr>
        <tr style="font-weight: bold">
            <td style="text-align: center">{{ trans('money.trade.total') }} &nbsp; ( <span name="2ndCurrency">{{ COIN_JPY }}</span> ) &nbsp; </td>
            <td style="text-align: center">{{ trans('money.trade.size') }} &nbsp; ( <span name="primaryCurrency">&nbsp;{{ COIN_BSC }}</span> ) &nbsp; </td>
            <td style="text-align: center">{{ trans('money.trade.ask_price') }} &nbsp; ( <span name="2ndCurrency">&nbsp;{{ COIN_JPY }}</span> ) &nbsp; </td>
            <td style="text-align: center">{{ trans('admin.user.message.email') }}</td>
            <td style="text-align: center">{{ trans('admin.user.message.name') }}</td>
        </tr>
        </thead>
        <tbody id="ask_offers" style="text-align: center;">
            <tr>
                <td colspan="100">
                    <img class="loader" src="{{ asset('images/trade_waiting.png') }}" />
                </td>
            </tr>
        </tbody>
    </table>
</div>
<div id="pager"></div>
@stop

@section('footer')
<script src="{{ asset('js/bootstrap-paginator.js') }}"></script>
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script language="javascript" src="{{asset('js/stellar/stellar-lib.js')}}" ></script>
<script language="javascript">
    //live.stellar.org
    //52.69.104.11
    $(document).ready(function(){
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var g_bid_offers = [];
    var g_ask_offers = [];

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
    remote.connect(function() {
        remote.on('transaction_all', transactionListener);
    });

    var getBid_Offers = function(){
        //Init (Show Loading Icon)bid_offers
        $('#bid_offers').html( "<tr><td colspan=100><img class='loader' src={{ asset('images/trade_waiting.png') }} /></td></tr>" );

        //Get Offers
        var primaryCurrency = $('#pri_curr').val();
        var secondCurrency = $('#sec_curr').val();

        var pri_issuer = $('#pri_issuer').prop('address') ? $('#pri_issuer').prop('address') : '';
        var sec_issuer = $('#sec_issuer').prop('address') ? $('#sec_issuer').prop('address') : '';

        if (primaryCurrency == secondCurrency && pri_issuer == sec_issuer || primaryCurrency == secondCurrency == 'BSC') {
            $('#bid_offers').html("<tr><td colspan=100>{{ trans('money.trade.nodata') }}</td></tr>");
            return;
        }

        var request = remote.request_book_offers({
            gets: {
                'currency': secondCurrency,
                'issuer': sec_issuer
            },
            pays: {
                'currency': primaryCurrency,
                'issuer': pri_issuer
            }
        });

        request.callback(function(err, res) {
            if (!err) {
                console.log(secondCurrency + "2" + primaryCurrency);
                console.log(res);
                g_bid_offers = res;
                showBidOffers();
            } else {
                $('#bid_offers').html("<tr><td colspan=100>{{ trans('money.trade.nodata') }}</td></tr>");
            }
        });
        request.request();
    };

    var getAsk_Offers = function() {
        //Init (Show Loading Icon)bid_offers
        $('#ask_offers').html( "<tr><td colspan=100><img class='loader' src={{ asset('images/trade_waiting.png') }} /></td></tr>" );

        //Get Offers
        var primaryCurrency = $('#pri_curr').val();
        var secondCurrency = $('#sec_curr').val();
        var pri_issuer = $('#pri_issuer').prop('address');
        var sec_issuer = $('#sec_issuer').prop('address');

        if (primaryCurrency == secondCurrency && pri_issuer == sec_issuer || primaryCurrency == secondCurrency == 'BSC') {
            $('#ask_offers').html("<tr><td colspan=100>{{ trans('money.trade.nodata') }}</td></tr>");
            return;
        }
        var request = remote.request_book_offers({
            gets: {
                'currency': primaryCurrency,
                'issuer': pri_issuer
            },
            pays: {
                'currency': secondCurrency,
                'issuer': sec_issuer
            }
        });

        request.callback(function(err, res) {
            if (!err) {
                console.log(primaryCurrency + "2" + secondCurrency);
                console.log(res);
                g_ask_offers = res;
                showAskOffers();
            } else {
                $('#ask_offers').html("<tr><td colspan=100>{{ trans('money.trade.nodata') }}</td></tr>");
            }
        });
        request.request();
    };

    function transactionListener (transaction_data) {
        console.log(transaction_data);
        getBid_Offers();
        getAsk_Offers();
    }

    function ledgerListener (ledger_data) {
        console.log(ledger_data);
    }

    function showBidOffers() {
        //Get Ask Offer's Accounts
        var bid_accounts = "";
        if (g_bid_offers.offers) {
            g_bid_offers.offers.forEach(function (bid_offer, i, bid_offers) {
                if (g_bid_offers.offers) {
                    bid_accounts += bid_offer.Account;
                    if (i != bid_offers.length - 1) {
                        bid_accounts += ',';
                    }
                }
            });
        } else {
            $('#bid_offers').html("<tr><td colspan=100>{{ trans('money.trade.nodata') }}</td></tr>");
            return;
        }
        $.ajax({
            type: "GET",
            url: "{{ url('/manage/root/user/info') }}",
            data: {wallet:bid_accounts},
            context: document.body,success:function(res){
                var user_datas = eval("("+res+")");
                var amount = 0;
                var total = 0;
                var price = 0;
                var account = '';
                var name = '';
                var email = '';
                var bid_offers = "";

                for (var i = 0; i < g_bid_offers.offers.length; i++){
                    if (g_bid_offers.offers[i].Account == '{{ $account or '' }}') {
                        return;
                    }
                    if ($('#sec_curr').val() == 'BSC') {
                        amount = number_format(g_bid_offers.offers[i].TakerGets / 1000000, 6);//BSC
                        total = number_format(g_bid_offers.offers[i].TakerPays.value, 6);//JPY
                        price = number_format(g_bid_offers.offers[i].quality * 1000000, 6);//JPY:BSC
                    } else if ($('#pri_curr').val() == 'BSC') {
                        amount = number_format(g_bid_offers.offers[i].TakerGets.value , 6);//BSC
                        total = number_format(g_bid_offers.offers[i].TakerPays / 1000000, 6);//JPY
                        price = number_format(g_bid_offers.offers[i].quality  * 1000000, 6);//JPY:BSC
                    } else {
                        amount = number_format(g_bid_offers.offers[i].TakerGets.value, 6);//BSC
                        total = number_format(g_bid_offers.offers[i].TakerPays.value, 6);//JPY
                        price = number_format(g_bid_offers.offers[i].quality, 6);//JPY:BSC
                    }
                    /*amount = number_format(g_bid_offers.offers[i].TakerPays.value, 6);//BSC
                     total = number_format(g_bid_offers.offers[i].TakerGets.value, 6);//JPY
                     price = number_format(1 / g_bid_offers.offers[i].quality, 6);//JPY:BSC*/
                    account = g_bid_offers.offers[i].Account;
                    name = '';
                    email = '';
                    if ( typeof user_datas[account] != 'undefined' )
                    {
                        name = user_datas[account].firstname + ' ' + user_datas[account].lastname;
                        email = user_datas[account].email;
                    }

                    bid_offers += "<tr name=bid index=" + i + "><td>" + name + "</td><td>" + email + "</td><td id=bid_total" + i + ">" + total + "</td><td id=bid_amount" + i + ">"
                            + amount + "</td><td id=bid_price" + i + ">" + price + "</td></tr>";
                }
                if (bid_offers == "") {
                    bid_offers = "<tr><td colspan=100>{{ trans('money.trade.nodata') }}</td></tr>";
                    $('#sellTrade').removeClass('table-hover');
                }
                $('#bid_offers').html(bid_offers);
            },
            error: function(){
                user_datas = null;
            }
        });
    }

    function showAskOffers() {
        //Get Ask Offer's Accounts
        var ask_accounts = "";
        if (g_ask_offers.offers) {
            g_ask_offers.offers.forEach(function (ask_offer, i, ask_offers) {
                if (g_bid_offers.offers) {
                    ask_accounts += ask_offer.Account;
                    if (i != ask_offers.length - 1) {
                        ask_accounts += ',';
                    }
                }
            });
        } else {
            $('#ask_offers').html("<tr><td colspan=100>{{ trans('money.trade.nodata') }}</td></tr>");
            return;
        }
        $.ajax({
            type: "GET",
            url: "{{ url('/manage/root/user/info') }}",
            data: {wallet:ask_accounts},
            context: document.body,
            success: function(res){
                var user_datas = eval("("+res+")");
                var amount = 0;
                var total = 0;
                var price = 0;
                var account = '';
                var name = '';
                var email = '';

                var ask_offers = "";
                for (var i = 0; i < g_ask_offers.offers.length; i++) {
                    if (g_ask_offers.offers[i].Account == '{{ $account or ''}}') {
                        continue;
                    }
                    if ($('#pri_curr').val() == 'BSC') {
                        amount = number_format(g_ask_offers.offers[i].TakerGets / 1000000, 6);//BSC
                        total = number_format(g_ask_offers.offers[i].TakerPays.value, 6);//JPY
                        price = number_format(g_ask_offers.offers[i].quality * 1000000, 6);//JPY:BSC
                    } else if ($('#sec_curr').val() == 'BSC') {
                        amount = number_format(g_ask_offers.offers[i].TakerGets.value , 6);//BSC
                        total = number_format(g_ask_offers.offers[i].TakerPays / 1000000, 6);//JPY
                        price = number_format(g_ask_offers.offers[i].quality  * 1000000, 6);//JPY:BSC
                    } else {
                        amount = number_format(g_ask_offers.offers[i].TakerGets.value, 6);//BSC
                        total = number_format(g_ask_offers.offers[i].TakerPays.value, 6);//JPY
                        price = number_format(g_ask_offers.offers[i].quality, 6);//JPY:BSC
                    }
                    account = g_ask_offers.offers[i].Account;
                    name = '';
                    email = '';

                    if ( typeof user_datas[account] != 'undefined' )
                    {
                        name = user_datas[account].firstname + ' ' + user_datas[account].lastname;
                        email = user_datas[account].email;
                    }

                    /*                        ask_offers += "<tr name=ask index=" + i + "><td>" + name + "</td><td>" + email + "</td><td id=ask_price" + i + ">" + price + "</td><td id=ask_amount" + i + ">"
                     + amount + "</td><td id=ask_total" + i + ">" + total + "</td></tr>";*/
                    ask_offers += "<tr name=ask index=" + i + "><td id=ask_total" + i + ">" + total + "</td><td id=ask_amount" + i + ">" + amount + "</td><td id=ask_price" + i + ">" + price + "</td><td>"
                            + email + "</td><td>" + name + "</td></tr>";
                }

                if (ask_offers == "") ask_offers = "<tr><td colspan=100>{{ trans('money.trade.nodata') }}</td></tr>";
                $('#ask_offers').html(ask_offers);
            },
            error: function(){
                user_datas = null;
            }
        });
    }
    function number_format(value, offset) {
        var s = parseFloat(Math.floor(parseFloat(value) * 1000000) / 1000000).toString();
        if (s.indexOf('.') == -1) s += '.';
        while (s.length < s.indexOf('.') + offset + 1) s += '0';
        s = s.substr(0, s.indexOf('.') + offset + 1);
        return s;
    }

    function clone(obj) {
        if (null == obj || "object" != typeof obj) return obj;
        var copy = obj.constructor();
        for (var attr in obj) {
            if (obj.hasOwnProperty(attr)) copy[attr] = obj[attr];
        }
        return copy;
    }
    $('[name=pri_curr]').bind('change', function(e) {
        $('#currency').html($(this).val());
        $('[name=primaryCurrency]').html($(this).val());
        $('[name=pri_issuer]').val('');
        $('[name=pri_issuer]').prop('disabled', true);
        if ($(this).val() == 'BSC') {
            if ($('[name=sec_curr]').val() != 'BSC') {
                $('[name=sec_issuer_item]:selected').trigger('click');
            } else {
//                showBidOffers();
//                showAskOffers();
                $('#ask_offers').html("<tr><td colspan=100>{{ trans('money.trade.nodata') }}</td></tr>");
                $('#bid_offers').html("<tr><td colspan=100>{{ trans('money.trade.nodata') }}</td></tr>");
            }
            return;
        }
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
                    gateways_html += '<li><a name="pri_issuer_item" class="cursor-pt" address="' + gateways[i].owner_address + '">' + gateways[i].name + '</a></li>';
                }
                if (!!gateways_html) {
                    $('#pri_issuer_list').html(gateways_html);
                    $('[name=pri_issuer]').prop('disabled', false);
                } else {
                    $('[name=pri_issuer]').val('');
                    $('[name=pri_issuer_item]').html('');
                }
                $('[name=pri_issuer_item]').click(function() {
                    $('[name=pri_issuer_item]').prop('selected', false);
                    $(this).prop('selected', true);
                    $('[name=pri_issuer]').val($(this).html());
                    $('[name=pri_issuer]').prop('address', $(this).attr('address'));
                    getBid_Offers();
                    getAsk_Offers();
                });
                $('[name=pri_issuer]:first').prop('address', $(this).attr('address'));
                $('[name=pri_issuer_item]:first').trigger('click');
            }
        });
    });
    $('[name=sec_curr]').bind('change', function(e) {
        $('#currency').html($(this).val());
        $('[name=2ndCurrency]').html($(this).val());
        $('[name=sec_issuer]').val('');
        $('[name=sec_issuer]').prop('disabled', true);
        if ($(this).val() == 'BSC') {
            if ($('[name=pri_curr]').val() != 'BSC') {
                $('[name=pri_issuer_item]:selected').trigger('click');
            } else {
//                showBidOffers();
//                showAskOffers();
                $('#ask_offers').html("<tr><td colspan=100>{{ trans('money.trade.nodata') }}</td></tr>");
                $('#bid_offers').html("<tr><td colspan=100>{{ trans('money.trade.nodata') }}</td></tr>");
            }
            return;
        }
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
                    gateways_html += '<li><a name="sec_issuer_item" class="cursor-pt" address="' + gateways[i].owner_address + '">' + gateways[i].name + '</a></li>';
                }
                if (!!gateways_html) {
                    $('#sec_issuer_list').html(gateways_html);
                    $('[name=sec_issuer]').prop('disabled', false);
                } else {
                    $('[name=sec_issuer]').val('');
                    $('[name=sec_issuer_item]').html('');
                }
                $('[name=sec_issuer_item]').click(function() {
                    $('[name=sec_issuer_item]').prop('selected', false);
                    $(this).prop('selected', true);
                    $('[name=sec_issuer]').val($(this).html());
                    $('[name=sec_issuer]').prop('address', $(this).attr('address'));
                    getBid_Offers();
                    getAsk_Offers();
                });
                $('[name=sec_issuer]:first').prop('address', $(this).attr('address'));
                $('[name=sec_issuer_item]:first').trigger('click');
            }
        });
    });
    $('[name=pri_curr]').trigger('change');
    $('[name=sec_curr]').trigger('change');
</script>
@stop