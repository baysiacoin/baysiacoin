@extends('layouts.user')
@section('header')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery/jquery.ui.all.css') }}" />
@stop
@section('content')
    <section class="scrollable padder">
        <div class="m-b-md">
            <h3 class="m-b-none">{{ trans('money.trade.title') }}</h3>
        </div>
        <div class="row m-l-xxs">
            <div class="col-sm-4" style="min-width: 400px;padding:0px;">
                <div class="input-group-btn" style="padding:5px;">
                    <table width="100%">
                        <tbody>
                        <tr>
                            <td>
                                <button type="button" id="btnBuy_fromUnit" class="btn btn-default2 dropdown-toggle" tabindex="-1" data-toggle="dropdown" style="width:110px;"><span style="float:left">{{ !empty(old('primaryCurrency')) ? old('primaryCurrency') : COIN_BSC }}</span><span style="float:right;"><span class="caret"></span></span></button>
                                <ul class="dropdown-menu">
                                    <li><a name="select_pri_currency" class="cursor-pt">{{ COIN_BSC }}</a></li>
                                    @foreach($currencies as $currency)
                                        <li><a class="cursor-pt" name="select_pri_currency">{{ $currency }}</a></li>
                                    @endforeach
                                </ul>
                            </td>
                            <td style="width:100%;">
                                <input class="btn btn-default2" id="p_issuer" name="p_issuer" data-toggle="dropdown" style="text-align: left;-moz-user-select:text;cursor:pointer;min-width:209px;width:100%;" tabindex="-1">
                                <ul class="dropdown-menu" id="p_issuer_dropdown">
                                </ul>
                            </td>
                            <td>
                                <span id="flip" style="width:100px;font-size:15px;padding-left:12px;cursor:pointer;">
                                    Flip
                                </span>                         
                            </td>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-sm-4" style="min-width: 400px;padding:0px;">
                <div class="input-group-btn" style="padding:5px;">
                    <table  width="100%">
                        <tbody>
                        <tr>
                            <td>
                                <button type="button" id="btnBuy_toUnit" class="btn btn-default2 dropdown-toggle" tabindex="-1" data-toggle="dropdown"  style="width:110px;"><span style="float:left">{{ !empty(old('2ndCurrency')) ? old('2ndCurrency') : COIN_JPY }}</span><span style="float:right;"><span class="caret"></span></span></button>
                                <ul class="dropdown-menu">
                                    <li><a class="cursor-pt" name="select_2nd_currency">{{ COIN_BSC }}</a></li>
                                    @foreach($currencies as $currency)
                                        <li><a class="cursor-pt" name="select_2nd_currency">{{ $currency }}</a></li>
                                    @endforeach
                                </ul>
                            </td>
                            <td style="width:100%;">
                                <input class="btn btn-default2" id="s_issuer" name="s_issuer" data-toggle="dropdown" style="text-align: left;-moz-user-select:text;cursor:pointer;min-width:209px;width:100%;" tabindex="-1">
                                <ul class="dropdown-menu" id="s_issuer_dropdown">
                                </ul>
                            </td>
                            <td>
                                <span style="width:100px;font-size:15px;padding-left:12px;visibility:hidden;">
                                    Flip
                                </span>
                            </td>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="input-group-btn" style="padding:5px;">
                    <button type="button" id="exchange_all" class="btn btn-default2 dropdown-toggle" data-toggle="dropdown" style="width:120px;">
                        <span name="2ndCurrency">{{ COIN_JPY }}</span>
                        &nbsp;→&nbsp;
                        <span name="primaryCurrency">{{ COIN_BSC }}</span>
                        {{--<span>--}}
                            {{--<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="15px" height="15px" viewBox="0 0 200 200" enable-background="new 0 0 200 200" xml:space="preserve">--}}
                                {{--<polygon fill="#8C50AA" points="171.8,20.6 138.7,10.7 100.4,81.2 62.5,10.7 28.2,20.6 76.4,102.3 41.2,102.3 41.2,124.3   82.9,124.3 82.9,141.9 41.2,141.9 41.2,164 82.9,164 82.9,182.2 82.9,189.3 116.9,189.3 116.9,164 155.4,164 155.4,141.9   116.8,141.9 116.8,124.3 155.4,124.3 155.4,102.3 123.6,102.3 "/>--}}
                            {{--</svg>--}}
                            {{--{{ 'JPY&nbsp;→&nbsp;' }}{{'BSC'}}--}}
{{--                            <img src="{{ asset('images/favicon.ico') }}">{{'BSC'}}--}}
                        {{--</span>--}}
                    </button>
                </div>
            </div>
        </div>
        <div class="row m-b-xs m-t-sm">
            <div class="col-sm-3" id="rate_board" style="margin-top:10px;visibility: hidden;">
                <span>H:&nbsp;</span>
                <span id="highPrice"></span>
                &nbsp;&nbsp;
                <span>L:&nbsp;</span>
                <span id="lowPrice"></span>
                &nbsp;&nbsp;
                <span>VOL:&nbsp;</span>
                <span class="vol" id="volume"></span>
                <span class="vol" id="volume_unit"></span>
                <span id="bidPrice" style="display:none;"></span>
                <span id="askPrice" style="display:none;"></span>
            </div>
            <div class="col-sm-5 alert-success2" id="result_board" style="display: none;">
                <span>
                    {{--@if (($result = Session::pull('result')) == 'Success')--}}
                        {{--{{ trans('money.trade.req_success') }}--}}
                    {{--@elseif ($result == 'LowBalance')--}}
                        {{--{{ trans('money.trade.fail_by_balance') }}--}}
                    {{--@elseif ($result == 'Fail')--}}
                        {{--{{ trans('money.trade.fail') }}--}}
                    {{--@endif--}}
                </span>
            </div>
        </div>
        <section>
            <div class="row">
                <div class="col-sm-6">
                    <section id="sell_board" class="panel panel-default">
                        <header class="panel-heading font-bold">{{ trans('money.trade.sell') }}</header>
                        @include('money.trade.sell')
                        <header class="panel-heading font-bold">{{ trans('money.trade.bids') }}</header>
                        <div class="table-responsive">
                            <table id="sellTrade" class="table table-hover table-striped b-t b-light">
                                <thead>
                                <tr>
                                    <th style="text-align: center">{{ trans('money.trade.total') }} &nbsp; ( <span name="2ndCurrency">{{ empty(old('2ndCurrency')) ? COIN_JPY : old('2ndCurrency') }}</span> ) &nbsp; </th>
                                    <th style="text-align: center">{{ trans('money.trade.size') }} &nbsp; ( <span name="primaryCurrency">{{ empty(old('primaryCurrency')) ? COIN_BSC : old('primaryCurrency') }}</span> ) &nbsp; </th>
                                    <th style="text-align: center">{{ trans('money.trade.bid_price') }} &nbsp; ( <span name="2ndCurrency">{{ empty(old('2ndCurrency')) ? COIN_JPY : old('2ndCurrency') }}</span> ) &nbsp; </th>
                                </tr>
                                </thead>
                                <tbody id="bid_offers" style="text-align: center;">
                                    <tr>
                                        <td colspan="3">
                                            <img  class="loader" src="{{ asset('images/trade_waiting.png') }}" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div style="height:20px;"></div>
                            <div class="form-group" style="text-align:center;">
                                <div class="col-lg-12">
                                    <a href="#" name="view_more_bids" class="btn btn-success" data-toggle="modal">{{ trans('stellar.offer_create.view_more_bids') }}</a>
                                </div>
                            </div>
                            <div style="height:40px;"></div>
                        </div>
                        <header class="panel-heading font-bold">{{ trans('money.trade.sells') }}</header>
                        <div class="table-responsive">
                            <table class="table b-t b-light">
                                <thead>
                                <tr>
                                    <th style="text-align: center">{{ trans('money.trade.total') }} &nbsp; ( <span name="2ndCurrency">{{ empty(old('2ndCurrency')) ? COIN_JPY : old('2ndCurrency') }}</span> ) &nbsp; </th>
                                    <th style="text-align: center">{{ trans('money.trade.size') }} &nbsp; ( <span name="primaryCurrency">{{ empty(old('primaryCurrency')) ? COIN_BSC : old('primaryCurrency') }}</span> ) &nbsp; </th>
                                    <th style="text-align: center">{{ trans('money.trade.ask_price') }} &nbsp; ( <span name="2ndCurrency">{{ empty(old('2ndCurrency')) ? COIN_JPY : old('2ndCurrency') }}</span> ) &nbsp; </th>
                                </tr>
                                </thead>
                                <tbody id="self_ask_offers" style="text-align: center;">
                                    <tr>
                                        <td colspan="3">
                                            <img  class="loader" src="{{ asset('images/trade_waiting.png') }}" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div style="height:40px;"></div>
                        </div>
                    </section>
                </div>
                <div class="col-sm-6">
                    <section id="buy_board" class="panel panel-default">
                        <header class="panel-heading font-bold">{{ trans('money.trade.buy') }}</header>
                        @include('money.trade.buy')
                        <header class="panel-heading font-bold">{{ trans('money.trade.asks') }}</header>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped b-t b-light">
                                <thead>
                                <tr>
                                    <th style="text-align: center">{{ trans('money.trade.ask_price') }} &nbsp; ( <span name="2ndCurrency">{{ empty(old('2ndCurrency')) ? COIN_JPY : old('2ndCurrency') }}</span> ) &nbsp;</th>
                                    <th style="text-align: center">{{ trans('money.trade.size') }} &nbsp; ( <span name="primaryCurrency">{{ empty(old('primaryCurrency')) ? COIN_BSC : old('primaryCurrency') }}</span> ) &nbsp;</th>
                                    <th style="text-align: center">{{ trans('money.trade.total') }} &nbsp; ( <span name="2ndCurrency">{{ empty(old('2ndCurrency')) ? COIN_JPY : old('2ndCurrency') }}</span> ) &nbsp;</th>
                                </tr>
                                </thead>
                                <tbody id="ask_offers" style="text-align: center;">
                                    <tr>
                                        <td colspan="3">
                                            <img  class="loader" src="{{ asset('images/trade_waiting.png') }}" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div style="height:20px;"></div>
                            <div class="form-group" style="text-align:center;">
                                <div class="col-lg-12">
                                    <a href="#" name="view_more_asks" class="btn btn-success" data-toggle="modal">{{ trans('stellar.offer_create.view_more_asks') }}</a>
                                </div>
                            </div>
                            <div style="height:40px;"></div>
                        </div>
                        <header class="panel-heading font-bold">{{ trans('money.trade.buys') }}</header>
                        <div class="table-responsive">
                            <table class="table b-t b-light">
                                <thead>
                                <tr>
                                    <th style="text-align: center">{{ trans('money.trade.bid_price') }} &nbsp; ( <span name="2ndCurrency">&nbsp;{{ empty(old('2ndCurrency')) ? COIN_JPY : old('2ndCurrency') }}</span> ) &nbsp; </th>
                                    <th style="text-align: center">{{ trans('money.trade.size') }} &nbsp; ( <span name="primaryCurrency">&nbsp;{{ empty(old('primaryCurrency')) ? COIN_BSC : old('primaryCurrency') }}</span> ) &nbsp; </th>
                                    <th style="text-align: center">{{ trans('money.trade.total') }} &nbsp; ( <span name="2ndCurrency">{{ empty(old('2ndCurrency')) ? COIN_JPY : old('2ndCurrency') }}</span> ) &nbsp; </th>
                                </tr>
                                </thead>
                                <tbody id="self_bid_offers" style="text-align: center;">
                                    <tr>
                                        <td colspan="3">
                                            <img  class="loader" src="{{ asset('images/trade_waiting.png') }}" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div style="height:40px;"></div>
                        </div>
                    </section>
                </div>
            </div>
            <div class="row">
                <!--div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div-->
                <div ui-view="main"></div>
            </div>
        </section>
    </section>
    </section>
    <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen,open" data-target="#nav,html"></a>
    </section>
    </section>
     <!-- Trigger the modal with a button -->
    {{--<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button>--}}
    <!-- Modal -->
    <div class="modal fade" id="tx_modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content alert-success">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">{{ trans('money.trade.trade_success') }}</h4>
                </div>
                <div class="modal-body">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="col-sm-4 no-padder"><span style="display: inline-block;">{{ trans('money.trade.ordered') }}:&nbsp;</span><span id="tx_orderedAmount" style="display: inline-block;"></span></div>
                        <div class="col-sm-4 no-padder"><span style="display: inline-block;" class="text-black">{{ trans('money.trade.dealed') }}:&nbsp;</span><span id="tx_dealedAmount" style="display: inline-block;" class="text-black"></span></div>
                        <div class="col-sm-4 no-padder"><span style="display: inline-block;" class="text-danger">{{ trans('money.trade.pending') }}:&nbsp;</span><span id="tx_pendingAmount" style="display: inline-block;" class="text-danger"></span></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div style="float:right; text-align: left; word-break: break-all;" id="tx_order_key">
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('footer')
    {{--<script src="{{ asset('js/bootstrap-dialog.min.js') }}"></script>--}}
<script language="javascript" src="{{asset('js/stellar/stellar-lib.js')}}" ></script>
<script language="javascript">
//live.stellar.org
//52.69.104.11
    var getBid_Offers, getAsk_Offers;
    var getOffers = function(){
        var lowPrice = 0;
        var highPrice = 0;
        var volume = 0;
        var bidPrice = 0;
        var askPrice = 0;
        {{--var base_issuer = '{{ Config::get('conf.stellar.issuer_wallet_address') }}';--}}/*
        $('#btnBuy_fromUnit').html('<span style="float:left">BSC</span><span style="float:right;"><span class="caret"></span></span>');
        $('#btnBuy_toUnit').html('<span style="float:left">JPY</span><span style="float:right;"><span class="caret"></span></span>');*/
        $('[name=sellPrice]').attr('disabled', $('#s_mod_limit').hasClass('active') ? false : true);
        $('[name=buyPrice]').attr('disabled', $('#b_mod_limit').hasClass('active') ? false : true);
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
//            remote.on('ledger_closed', ledgerListener);
        });
        getBid_Offers = function(){
            var primaryCurrency = $('[name=primaryCurrency]').html();//BSC, JPY
            var secondCurrency = $('[name=2ndCurrency]').html();
            var p_issuer = $('[name=p_issuer]').val();
            var s_issuer = $('[name=s_issuer]').val();
            if ($('[name=p_issuer]').prop('address')) {
                p_issuer = $('[name=p_issuer]').prop('address');
            }
            if ($('[name=s_issuer]').prop('address')) {
                s_issuer = $('[name=s_issuer]').prop('address');
            }
            var request = remote.request_book_offers({
                gets: {
                    'currency': secondCurrency,
                    'issuer': s_issuer
                },
                pays: {
                    'currency': primaryCurrency,
                    'issuer': p_issuer
                }
            });
            request.callback(function(err, res) {
                if (!err) {
                    processBidOffers(res);
                } else
                    $('#bid_offers').html("<tr><td colspan=3>{{ trans('money.trade.nodata') }}</td></tr>");
                $('#sell_board').css('opacity', 1);
                $('#buy_board').css('opacity', 1);
            });
            request.request();

            function processBidOffers(res) {
                var amount;
                var total;
                var price;
                if (!!res) {
                    //console.log(secondCurrency + "2" + primaryCurrency);
                    console.log(res);
                    var bid_offers = "";
                    var length = count_view_bid == 0 ? res.offers.length : Math.min(count_view_bid, res.offers.length);

                    for (var i = 0; i < length; i++) {
                        if (primaryCurrency == 'BSC') {
                            amount = round_up(res.offers[i].TakerPays / 1000000);//BSC
                        } else {
                            amount = round_up(res.offers[i].TakerPays.value);//BSC
                        }
                        if (secondCurrency == 'BSC') {
                            total = round_down(res.offers[i].TakerGets / 1000000);//JPY
                        } else {
                            total = round_down(res.offers[i].TakerGets.value);//JPY
                        }
                        if (primaryCurrency == 'BSC') {
                            price = round_down(1 / res.offers[i].quality * 1000000);//JPY:BSC
                        } else if (secondCurrency == 'BSC') {
                            price = round_down(1 / res.offers[i].quality / 1000000);//JPY:BSC
                        } else {
                            price = round_down(1 / res.offers[i].quality);
                        }
                        if (highPrice == 0 || parseFloat(price) > parseFloat(highPrice)) {
                            highPrice = price;
                        }
                        if (lowPrice == 0 || parseFloat(price) < parseFloat(lowPrice)) {
                            lowPrice = price;
                        }
                        if (askPrice == 0 || parseFloat(price) > parseFloat(askPrice)) {
                            askPrice = price;
                        }
                        //console.log(amount);
                        volume += parseFloat(amount);
                        bid_offers += "<tr name=bid index=" + i + "><td id=bid_total" + i + ">" + total + "</td><td id=bid_amount" + i + ">"
                                + amount + "</td><td id=bid_price" + i + ">" + price + "</td></tr>";
                    }
                    if (bid_offers == "") {
                        bid_offers = "<tr><td colspan=3>{{ trans('money.trade.nodata') }}</td></tr>";
                        $('#sellTrade').removeClass('table-hover');
                    }
                    $('#bid_offers').html(bid_offers);
                    $('[name=bid]').click(function(){
                        if ($('[name=sellPrice]').attr('disabled') == true) return;
                        index = $(this).attr('index');
                        $('[name=sellAmount]').val($('#bid_amount' + index).html());
                        $('[name=sellPrice]').val($('#bid_price' + index).html());
                        $('[name=sellTotal]').val($('#bid_total' + index).html());
                        //$('[name="ask_buy_account"]').val($('#bid_account' + index).val());
                    });
                    $('#highPrice').html(round_down(highPrice));
                    $('#lowPrice').html(round_up(lowPrice));
                    $('#volume').html(round_down(volume));
                    $('#askPrice').html(round_down(askPrice));
                    $('#volume_unit').html($('[name=primaryCurrency]').html());
                    $('#rate_board').attr('style', 'margin-top: 10px');
                    // alert($('[name="bid"]').html());
                } else
                    $('#bid_offers').html("<tr><td colspan=3>{{ trans('money.trade.nodata') }}</td></tr>");
                $('#sell_board').css('opacity', 1);
                $('#buy_board').css('opacity', 1);
            }
            var data = {
                gets: {
                    'currency': secondCurrency,
                    'issuer': s_issuer
                },
                pays: {
                    'currency': primaryCurrency,
                    'issuer': p_issuer
                }
            };
            /*$.getJSON('{{ url('money/raw-offers') }}', data, function(res) {
                if (res.status == 'Success') {
                    processBidOffers(res.raw);
                }
            });*/
        };
        getAsk_Offers = function() {
            var primaryCurrency = $('[name=primaryCurrency]').html();
            var secondCurrency = $('[name=2ndCurrency]').html();
            var p_issuer = $('[name=p_issuer]').val();
            var s_issuer = $('[name=s_issuer]').val();
            if ($('[name=p_issuer]').prop('address')) {
                p_issuer = $('[name=p_issuer]').prop('address');
            }
            if ($('[name=s_issuer]').prop('address')) {
                s_issuer = $('[name=s_issuer]').prop('address');
            }
            {{--if (!p_issuer || p_issuer == '{{ Config::get('conf.base_gateway') }}') p_issuer = base_issuer;--}}
            {{--if (!s_issuer || s_issuer == '{{ Config::get('conf.base_gateway') }}') s_issuer = base_issuer;--}}
            var request = remote.request_book_offers({
                gets: {
                    'currency': primaryCurrency,
                    'issuer': p_issuer
                },
                pays: {
                    'currency': secondCurrency,
                    'issuer': s_issuer
                }
            });
            request.callback(function(err, res) {
                if (!err) {
                    processAskOffers(res);
                } else
                    $('#ask_offers').html("<tr><td colspan=3>{{ trans('money.trade.nodata') }}</td></tr>");
                $('#sell_board').css('opacity', 1);
                $('#buy_board').css('opacity', 1);
            });
            request.request();

            function processAskOffers(res) {
                var amount;
                var total;
                var price;
                if (!!res) {
//                    //console.log(primaryCurrency + "2" + secondCurrency);
//                    //console.log(res);
                    var ask_offers = "";
                    var length = count_view_ask == 0 ? res.offers.length : Math.min(count_view_ask, res.offers.length);
                    for (var i = 0; i < length; i++) {
                        /*if (res.offers[i].Account == '{{ $account or ''}}') {
                         continue;
                         }*/
                        if (primaryCurrency == 'BSC') {
                            amount = round_up(res.offers[i].TakerGets / 1000000);//BSC
                        } else {
                            amount = round_up(res.offers[i].TakerGets.value);//BSC
                        }
                        if (secondCurrency == 'BSC') {
                            total = round_down(res.offers[i].TakerPays / 1000000);//JPY
                        } else {
                            total = round_down(res.offers[i].TakerPays.value);//JPY
                        }
                        //alert(a.toFixed(2));
                        if (primaryCurrency == 'BSC') {
                            price = round_up(res.offers[i].quality * 1000000);//JPY:BSC
                        } else if (secondCurrency == 'BSC') {
                            price = round_up(res.offers[i].quality / 1000000);//JPY:BSC
                        } else {
                            price = round_up(res.offers[i].quality);//JPY:BSC
                        }
                        if (highPrice == 0 || parseFloat(price) > parseFloat(highPrice)) {
                            highPrice = price;
                        }
                        if (lowPrice == 0 || parseFloat(price) < parseFloat(lowPrice)) {
                            lowPrice = price;
                        }
                        if (bidPrice == 0 || parseFloat(price) < parseFloat(bidPrice)) {                            
                            bidPrice = price;                            
                        }                        
                        volume += parseFloat(amount);
                        ask_offers += "<tr name=ask index=" + i + "><td id=ask_price" + i + ">" + price + "</td><td id=ask_amount" + i + ">"
                                + amount + "</td><td id=ask_total" + i + ">" + total + "</td></tr>";
                    }
                    if (ask_offers == "") ask_offers = "<tr><td colspan=3>{{ trans('money.trade.nodata') }}</td></tr>";
                    $('#ask_offers').html(ask_offers);
                    $('[name=ask]').click(function(){
                        if ($('[name=buyPrice]').attr('disabled') == true) return;
                        index = $(this).attr('index');
                        $('[name=buyAmount]').val($('#ask_amount' + index).html());
                        $('[name=buyPrice]').val($('#ask_price' + index).html());
                        $('[name=buyTotal]').val($('#ask_total' + index).html());
                    });
                    $('#lowPrice').html(round_up(lowPrice, 6));
                    $('#highPrice').html(round_down(highPrice, 6));
                    $('#volume').html(round_down(volume, 6));
                    $('#bidPrice').html(round_up(bidPrice));
                    $('#volume_unit').html($('[name=primaryCurrency]').html());
                    $('#rate_board').attr('style', 'margin-top: 10px;');
                } else
                    $('#ask_offers').html("<tr><td colspan=3>{{ trans('money.trade.nodata') }}</td></tr>");
                $('#sell_board').css('opacity', 1);
                $('#buy_board').css('opacity', 1);
            }
            var data = {
                gets: {
                    'currency': primaryCurrency,
                    'issuer': p_issuer
                },
                pays: {
                    'currency': secondCurrency,
                    'issuer': s_issuer
                }
            };
            /*$.getJSON('{{ url('money/raw-offers') }}', data, function(res) {
                if (res.status == 'Success') {
                    processAskOffers(res.raw);
                }
            });*/
        };
        var getAccount_Offers = function (){
            var request = remote.request_account_offers({
                account: '{{ $account or '' }}'
            });
            request.callback(function(err, res){
                console.log(err);
                if (!err) {
                    processAccountOffers(res);
                }
            });
            request.request();
            function processAccountOffers(res) {
                var self_ask_offers = "";
                var self_bid_offers = "";
                var primaryCurrency = $('[name=primaryCurrency]').html();
                var secondCurrency = $('[name=2ndCurrency]').html();
                var p_issuer = $('[name=p_issuer]').val();
                var s_issuer = $('[name=s_issuer]').val();
                if ($('[name=p_issuer]').prop('address')) {
                    p_issuer = $('[name=p_issuer]').prop('address');
                }
                if ($('[name=s_issuer]').prop('address')) {
                    s_issuer = $('[name=s_issuer]').prop('address');
                }
                //console.log(primaryCurrency, p_issuer);
                //console.log(secondCurrency, s_issuer);
                var ask_offers = res.offers.filter(function(d) {
                    if (primaryCurrency == 'BSC') {
                        return typeof d.taker_gets == 'string' && typeof d.taker_pays == 'object'
                                && (d.taker_pays.currency == secondCurrency && d.taker_pays.issuer == s_issuer);
                    } else if (secondCurrency == 'BSC') {
                        return typeof d.taker_gets == 'object' && typeof d.taker_pays == 'string'
                                && (d.taker_gets.currency == primaryCurrency && d.taker_gets.issuer == p_issuer);
                    } else {
                        return typeof d.taker_gets == 'object' && typeof d.taker_pays == 'object'
                                && (d.taker_gets.currency == primaryCurrency && d.taker_gets.issuer == p_issuer)
                                && (d.taker_pays.currency == secondCurrency && d.taker_pays.issuer == s_issuer);
                    }
                });
                var bid_offers = res.offers.filter(function(d) {
                    if (primaryCurrency == 'BSC') {
                        return typeof d.taker_gets == 'object' && typeof d.taker_pays == 'string'
                                && (d.taker_gets.currency == secondCurrency && d.taker_gets.issuer == s_issuer)
                    } else if (secondCurrency == 'BSC') {
                        return typeof d.taker_gets == 'string' && typeof d.taker_pays == 'object'
                                && (d.taker_pays.currency == primaryCurrency && d.taker_pays.issuer == p_issuer);
                    } else {
                        return typeof d.taker_gets == 'object' && typeof d.taker_pays == 'object'
                                && (d.taker_gets.currency == secondCurrency && d.taker_gets.issuer == s_issuer)
                                && (d.taker_pays.currency == primaryCurrency && d.taker_pays.issuer == p_issuer);
                    }
                });
                ask_offers.forEach(function(d) {
                    var amount = 0;
                    var total = 0;
                    var price = 0;
                    if (primaryCurrency == 'BSC') {
                        amount = round_up(d.taker_gets / 1000000); // BSC
                        total = round_up(d.taker_pays.value);//JPY
                        price = round_up(d.taker_pays.value / d.taker_gets * 1000000);
                    } else if (secondCurrency == 'BSC') {
                        amount = round_up(d.taker_gets.value); // BSC
                        total = round_up(d.taker_pays / 1000000);//JPY
                        price = round_up(d.taker_pays / d.taker_gets.value / 1000000);
                    } else {
                        amount = round_up(d.taker_gets.value); // BSC
                        total = round_up(d.taker_pays.value);//JPY
                        price = round_up(d.taker_pays.value / d.taker_gets.value)
                    }
                    self_ask_offers += "<tr name=self_ask_offers><td>" + total + "</td><td>" + amount + "</td><td>" + price
                            + "</td><td><a href='#' name='del_offers' seq=" + d.seq + ">{{ trans('money.trade.delete') }}</a></td></tr>";
                });
                bid_offers.forEach(function(d) {
                    var amount = 0;
                    var total = 0;
                    var price = 0;
                    if (primaryCurrency == 'BSC') {
                        total = round_up(d.taker_gets.value); // BSC
                        amount = round_up(d.taker_pays / 1000000);//JPY
                        price = round_up(d.taker_gets.value / d.taker_pays * 1000000);
                    } else if (secondCurrency == 'BSC') {
                        total = round_up(d.taker_gets / 1000000); // BSC
                        amount = round_up(d.taker_pays.value);//JPY
                        price = round_up(d.taker_gets / d.taker_pays.value / 1000000);
                    } else {
                        total = round_up(d.taker_gets.value); // BSC
                        amount = round_up(d.taker_pays.value);//JPY
                        price = round_up(d.taker_gets.value / d.taker_pays.value);
                    }
                    self_bid_offers += "<tr name=self_bid_offers><td>" + price + "</td><td>" + amount + "</td><td>" + total
                            + "</td><td><a href='#' name='del_offers' seq=" + d.seq + ">{{ trans('money.trade.delete') }}</a></td></tr>";
                });
                if (self_ask_offers == "") self_ask_offers = "<tr><td colspan=3>{{ trans('money.trade.nodata') }}</td></tr>";
                if (self_bid_offers == "") self_bid_offers = "<tr><td colspan=3>{{ trans('money.trade.nodata') }}</td></tr>";
                $('#self_ask_offers').html(self_ask_offers);
                $('#self_bid_offers').html(self_bid_offers);
                validateAccountOffers(ask_offers, bid_offers);

                $('[name=del_offers]').click(function(){
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: '{{ url('/money/cancel') }}',
                        type: 'post',
                        data: 'seq=' + $(this).attr('seq'),
                        dataType: 'json',
                        success: function(msg){
                            //console.log(msg);
                            if (msg.result == 'Success')
                                alert("Success!");
                            else
                                alert("Failed!");
                        },
                        error: function(err){
                            alert("FailedByConnection!")
                        }
                    });
                });
            }
            /*$.getJSON('{{ url('money/raw-account-offers') }}', function(res) {
                if (res.raw) {
                    res = res.raw;
                    processAccountOffers(res);
                }
            });*/
        };
        var validateAccountOffers = function(self_ask_offers, self_bid_offers) {
            var account = '{{ $account or '' }}';
            var primaryCurrency = $('[name=primaryCurrency]').html();
            var secondCurrency = $('[name=2ndCurrency]').html();
            var p_issuer = $('[name=p_issuer]').val();
            var s_issuer = $('[name=s_issuer]').val();
            //console.log('p_issuer', p_issuer);
            //console.log('s_issuer', s_issuer);
            var p_request, s_request;
            var amount = 0;

            if ($('[name=p_issuer]').prop('address')) {
                p_issuer = $('[name=p_issuer]').prop('address');
            }
            if ($('[name=s_issuer]').prop('address')) {
                s_issuer = $('[name=s_issuer]').prop('address');
            }

            if (primaryCurrency == secondCurrency) {
                return;
            } else if (primaryCurrency == 'BSC') {
                p_request = remote.requestAccountBalance({
                    account: account
                });
                p_request.callback(function(err, res) {
                    if (!err) {
                        var balance = res.node.Balance / 1000000;
                        var ask_total = 0;
                        var invalid_seqs = [];
                        self_ask_offers.forEach(function(d) {
                            if (balance < (ask_total += d.taker_gets / 1000000)) {
                                invalid_seqs.push(d.seq);
                            }
                        });
                        cancelOffers(invalid_seqs);
                        //console.log('pri_invalid', invalid_seqs);
                    }
                });
                s_request = remote.requestAccountLines({
                    account: account,
                    peer: s_issuer
                });
                s_request.callback(function(err, res) {
                    if (!err) {
                        var line = res.lines.filter(function(d) {
                            return d.currency == secondCurrency;
                        });
                        var bid_total = 0;
                        var invalid_seqs = [];
                        self_bid_offers.forEach(function(d) {
                            if (line.balance < (bid_total += d.taker_gets.value)) {
                                invalid_seqs.push(d.seq);
                            }
                        });
                        cancelOffers(invalid_seqs);
                        //console.log('sec_invalid', invalid_seqs);
                    }
                });
            } else if (secondCurrency == 'BSC') {
                p_request = remote.requestAccountLines({
                    account: account,
                    peer: p_issuer
                });
                p_request.callback(function(err, res) {
                    if (!err) {
                        var line = res.lines.filter(function(d) {
                            return d.currency == primaryCurrency;
                        });
                        var ask_total = 0;
                        var invalid_seqs = [];
                        self_ask_offers.forEach(function(d) {
                            if (line.balance < (ask_total += d.taker_gets.value)) {
                                invalid_seqs.push(d.seq);
                            }
                        });
                        cancelOffers(invalid_seqs);
                        //console.log('pri_invalid', invalid_seqs);
                    }
                });
                s_request = remote.requestAccountBalance({
                    account: account
                });
                s_request.callback(function(err, res) {
                    if (!err) {
                        var balance = res.node.Balance / 1000000;
                        var bid_total = 0;
                        var invalid_seqs = [];
                        self_bid_offers.forEach(function(d) {
                            if (balance < (bid_total += d.taker_gets / 1000000)) {
                                invalid_seqs.push(d.seq);
                            }
                        });
                        cancelOffers(invalid_seqs);
                        //console.log('sec_invalid', invalid_seqs);
                    }
                });
            } else {
                p_request = remote.requestAccountLines({
                    account: account,
                    peer: p_issuer
                });
                p_request.callback(function(err, res) {
                    if (!err) {
                        var line = res.lines.filter(function(d) {
                            return d.currency == primaryCurrency;
                        });
                        var ask_total = 0;
                        var invalid_seqs = [];
                        self_ask_offers.forEach(function(d) {
                            if (line.balance < (ask_total += d.taker_gets.value)) {
                                invalid_seqs.push(d.seq);
                            }
                        });
                        cancelOffers(invalid_seqs);
                        //console.log('pri_invalid', invalid_seqs);
                    }
                });
                s_request = remote.requestAccountLines({
                    account: account,
                    peer: s_issuer
                });
                s_request.callback(function(err, res) {
                    if (!err) {
                        var line = res.lines.filter(function(d) {
                            return d.currency == secondCurrency;
                        });
                        var bid_total = 0;
                        var invalid_seqs = [];
                        self_bid_offers.forEach(function(d) {
                            if (line.balance < (bid_total += d.taker_gets.value)) {
                                invalid_seqs.push(d.seq);
                            }
                        });
                        cancelOffers(invalid_seqs);
                        //console.log('sec_invalid', invalid_seqs);
                    }
                });
            }
            p_request.request();
            s_request.request();
        };
        var cancelOffers = function(offers_list) {
            if (typeof offers_list == 'object') {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var index = 0;
                (function poll(){
                    if (index == offers_list.length) return;
                    $.ajax({
                        url: '{{ url('/money/cancel') }}',
                        type: 'post',
                        data: 'seq=' + offers_list[index++],
                        dataType: 'json',
                        success: function(msg){
                            //console.log(msg);
                        },
                        error: function(err){
                            //console.log("FailedByConnection!")
                        },
                        complete: poll,
                        timeout: 1000
                    });
                })();
            }
        }
        function transactionListener (res) {
//            var array = $.map(transaction_data, function(value, index) {
//            return [value];
//            });
//            var array = $.map(transaction_data, function(arr) {
//            return arr;
//            });
            var created_nodes;
            var modified_nodes;
            var deleted_nodes;
            var order_sell_amount, order_buy_amount, dealed_sell_amount, dealed_buy_amount, pending_sell_amount, pending_buy_amount;
            if (res) {
                console.log(res);
                if (res.transaction.Account && res.transaction.Account == '{{ $account or '' }}' && res.transaction.TransactionType == 'OfferCreate') {
                    if (res.engine_result == 'tesSUCCESS') {
                        if (res.mmeta.nodes) {
                            created_nodes = res.mmeta.nodes.filter(function(d) {
                                return d.bookKey && d.entryType == 'Offer' && d.diffType == 'CreatedNode';
                            });
                            modified_nodes = res.mmeta.nodes.filter(function(d) {
                                return d.bookKey && d.entryType == 'Offer' && d.diffType == 'ModifiedNode';
                            });
                            deleted_nodes = res.mmeta.nodes.filter(function(d) {
                                return d.bookKey && d.entryType == 'Offer' && d.diffType == 'DeletedNode';
                            });
                            order_sell_amount = stellar.Amount.from_json(res.transaction.TakerGets);
                            order_buy_amount = stellar.Amount.from_json(res.transaction.TakerPays);

                            var order_sell_key = order_sell_amount.currency().to_json();
                            if (order_sell_key !== 'BSC') order_sell_key += '/' + order_sell_amount.issuer().to_json();
                            order_sell_key = '{{ trans('money.trade.sell') }}: ' + order_sell_key;
                            var order_buy_key = order_buy_amount.currency().to_json();
                            if (order_buy_key !== 'BSC') order_buy_key += '/' + order_buy_amount.issuer().to_json();
                            order_buy_key = '{{ trans('money.trade.buy') }}: ' + order_buy_key;
//                            var order_key = [ order_sell_key, order_buy_key ].join(', ');
                            $('#tx_order_key').html('<span>' + order_sell_key + '</span><br><span>' + order_buy_key + '</span>');
                            $('#tx_orderedAmount').html('<span style="display:inline-block;">' + order_sell_amount.to_human({min_precision: 8}) + '&nbsp;' + order_sell_amount.currency().to_json() +
                                    ',</span>&nbsp;<span style="display:inline-block;">' + order_buy_amount.to_human({min_precision: 8}) + '&nbsp;' + order_buy_amount.currency().to_json() + '</span>');
                            if (created_nodes.length == 0) {
                                dealed_sell_amount = order_sell_amount;
                                dealed_buy_amount = order_buy_amount;
                                pending_sell_amount = stellar.Amount.from_json("");
                                pending_buy_amount = stellar.Amount.from_json("");
                            } else {
                                pending_sell_amount = stellar.Amount.from_json(created_nodes[0].fieldsNew.TakerGets);
                                pending_buy_amount = stellar.Amount.from_json(created_nodes[0].fieldsNew.TakerPays);
                                dealed_sell_amount = order_sell_amount.add(pending_sell_amount.negate());
                                dealed_buy_amount = order_buy_amount.add(pending_buy_amount.negate());
                            }
                            $('#tx_dealedAmount').html('<span style="display:inline-block;">' + dealed_sell_amount.to_human({min_precision: 8}) + '&nbsp;' + order_sell_amount.currency().to_json() +
                                    ',</span>&nbsp;<span style="display:inline-block;">' + dealed_buy_amount.to_human({min_precision: 8}) + '&nbsp;' + order_buy_amount.currency().to_json() + '</span>');
                            $('#tx_pendingAmount').html('<span style="display:inline-block;">' + pending_sell_amount.to_human({min_precision: 8}) + '&nbsp;' + order_sell_amount.currency().to_json() +
                                    ',</span>&nbsp;<span style="display:inline-block;">' + pending_buy_amount.to_human({min_precision: 8}) + '&nbsp;' + order_buy_amount.currency().to_json() + '</span>');
                            $('#tx_modal').modal('show');
                        }
                        /*if ($('#result_board').hasClass('alert-error2')) {
                            $('#result_board').removeClass('alert-error2');
                        }
                        $('#result_board').addClass('alert-success2');
                        $('#result_board').html('<span>{{ trans('money.trade.success') }}</span>');*/
                    } else {
                        if ($('#result_board').hasClass('alert-success2')) {
                            $('#result_board').removeClass('alert-success2');
                        }
                        $('#result_board').addClass('alert-error2');
                        $('#result_board').html('<span>{{ trans('money.trade.fail') }}</span>');
                        $('#result_board').fadeIn(1000, function(){
                            setTimeout(function(){
                                $('#result_board').fadeOut('slow', 'swing');
                            }, 10000);
                        });
                    }
                }
            }
            lowPrice = 0;
            highPrice = 0;
            volume = 0;
            getBid_Offers();
            getAsk_Offers();
            getAccount_Offers();
        }
        function ledgerListener (ledger_data) {
            //console.log(ledger_data);
            // handle ledger_data
            // see https://www.stellar.org/api/#api-subscribe for the format of ledger_data
        }
        $('#sell_board').css('opacity', 0.5);
        $('#buy_board').css('opacity', 0.5);
        getBid_Offers();
        getAsk_Offers();
        getAccount_Offers();
    }
    $(document).ready(function(){
        setPreviousURL();
        $('#btnBuy_fromUnit').trigger('change');
        $('#btnBuy_toUnit').trigger('change');
    });
</script>
<script src="{{ asset('js/plugins/noty/jquery.noty.js')}}"></script>
<script src="{{asset('js/plugins/noty/layouts/topCenter.js')}}"></script>
<script src="{{asset('js/plugins/noty/layouts/topLeft.js') }}"></script>
<script src="{{asset('js/plugins/noty/layouts/topRight.js')}}"></script>
<script src="{{asset('js/plugins/noty/layouts/center.js')}}"></script>
<script type='text/javascript' src={{asset('js/plugins/noty/themes/default.js')}}></script>
<script type="text/javascript">
    var count_view_ask = 20;
    var count_view_bid = 20;
    var index = 0;
    $('[name=select_pri_currency]').click(function(){
        $('#btnBuy_fromUnit').html('<span style="float:left">' + $(this).html() + '</span><span style="float:right;"><span class="caret"></span></span>');
        
        var pri_cur = $(this).html();
        var sec_cur = $('[name=2ndCurrency]').html();
        var s_mod_market = $('#s_mod_market').hasClass('active') ? true : false;
        var b_mod_market = $('#b_mod_market').hasClass('active') ? true : false;

        $('[name=primaryCurrency]').html(pri_cur);
        $('[name=primaryCurrency]').val(pri_cur);
        
        $('#btnBuy_fromUnit').trigger('change');
         
        if (pri_cur == sec_cur) {
            $('#rate_board').prop('style', 'visibility:hidden');
            $('[name=sellPrice]').val('');
            $('[name=buyPrice]').val('');
//            $('#exchange_all').prop('disabled', true);
            $('#exchange_all').css('pointer-events', 'none');
            return;
        } else {
            $('#exchange_all').css('pointer-events', 'auto');
        }/* else if (pri_cur == 'BSC' && sec_cur == 'JPY') {
            $('#exchange_all').css('display', 'block');
        } else {
            $('#exchange_all').css('display', 'none');
        }  */

        if ($('[name=sellPrice]').prop('disabled')) {
            $('[name=sellPrice]').val($('#highPrice').html() == 0 ? $('#lowPrice').html() : $('#highPrice').html());
            $('[name=buyPrice]').val($('#lowPrice').html() == 0 ? $('#highPrice').html() : $('#lowPrice').html());
            $('[name=sellAmount]').val('');
            $('[name=sellTotal]').val('');
        }

    });
    $('#btnBuy_fromUnit').change(function(){
        getGatewayList2($(this).children('span').html(), 'pri');
    });
    $('[name=p_issuer]').change(function(){
        $('[name=p_issuer]').val($(this).val());
        getOffers();
    });
    $('[name=select_2nd_currency]').click(function(){
        $('#btnBuy_toUnit').html('<span style="float:left">' + $(this).html() + '</span><span style="float:right;"><span class="caret"></span></span>');
        
        var pri_cur = $('[name=primaryCurrency]').html();
        var sec_cur = $(this).html();
        var s_mod_market = $('#s_mod_market').hasClass('active') ? true : false;
        var b_mod_market = $('#b_mod_market').hasClass('active') ? true : false;

        $('[name=2ndCurrency]').html(sec_cur);
        $('[name=2ndCurrency]').val(sec_cur);

        $('#btnBuy_toUnit').trigger('change');

        if (pri_cur == sec_cur) {
            $('#rate_board').prop('style', 'visibility:hidden');
            $('[name=sellPrice]').val('');
            $('[name=buyPrice]').val('');
//            $('#exchange_all').prop('disabled', true);
            $('#exchange_all').css('pointer-events', 'none');
            return;
        } else {
            $('#exchange_all').css('pointer-events', 'auto');
        }/*else if (pri_cur == 'BSC' && sec_cur == 'JPY') {
            $('#exchange_all').css('display', 'block');
        } else {
            $('#exchange_all').css('display', 'none');
        }*/

        if ($('[name=sellPrice]').prop('disabled')) {
            $('[name=sellPrice]').val($('#highPrice').html() == 0 ? $('#lowPrice').html() : $('#highPrice').html());
            $('[name=buyPrice]').val($('#lowPrice').html() == 0 ? $('#highPrice').html() : $('#lowPrice').html());
            $('[name=sellAmount]').val('');
            $('[name=sellTotal]').val('');
        }
    });
    $('#btnBuy_toUnit').change(function(){
        getGatewayList2($(this).children('span').html(), '2nd');
    });
    $('[name=s_issuer]').change(function(){
        $('[name=s_issuer]').val($(this).val());
        getOffers();
    });
    $('#sell_submit').click(function(){
//        $('form#sell_form').submit();
        $(this).css({'pointer-events': 'none', opacity: 0.2});
        $('#sell_waiting').waiting({
            className: 'waiting-nonfluid',
            elements: 5,
            fluid: false,
            auto: true,
            speed: 500
        });
        var data = $('form#sell_form').serialize();
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: '{{ url('money/trade') }}',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(res) {
                console.log(res);
                if (res.result == '{{ SUCCESS }}') {
                    if ($('#result_board').hasClass('alert-error2')) {
                        $('#result_board').removeClass('alert-error2');
                    }
                    $('#result_board').addClass('alert-success2');
                    $('#result_board').html('<span>{{ trans('money.trade.req_success') }}</span>');
                } else if (res.result == '{{ LOW_BALANCE }}') {
                    if ($('#result_board').hasClass('alert-success2')) {
                        $('#result_board').removeClass('alert-success2');
                    }
                    $('#result_board').addClass('alert-error2');
                    $('#result_board').html('<span>{{ trans('money.trade.fail_by_balance') }}</span>');
                } else if (res.result == '{{ FAIL }}') {
                    if ($('#result_board').hasClass('alert-success2')) {
                        $('#result_board').removeClass('alert-success2');
                    }
                    $('#result_board').addClass('alert-error2');
                    $('#result_board').html('<span>{{ trans('money.trade.fail') }}</span>');
                } else {
                    if ($('#result_board').hasClass('alert-success2')) {
                        $('#result_board').removeClass('alert-success2');
                    }
                    $('#result_board').addClass('alert-error2');
                    $('#result_board').html('<span>{{ trans('money.trade.fail') }}</span>');
                }
                $('#result_board').fadeIn(1000, function(){
                    setTimeout(function(){
                        $('#result_board').fadeOut('slow', 'swing');
                    }, 10000);
                });
                $('#result_board')[0].scrollIntoView(false);
                setTimeout(function(){
                    $('#sell_submit').css({'pointer-events': 'auto', opacity: 1});
                    $('#sell_waiting').waiting('disable');
                }, 5000);
            },
            error: function(err) {
                if ($('#result_board').hasClass('alert-success2')) {
                    $('#result_board').removeClass('alert-success2');
                }
                $('#result_board').addClass('alert-error2');
                $('#result_board').html('<span>{{ trans('money.trade.fail') }}</span>');
                $('#result_board')[0].scrollIntoView(false);
                setTimeout(function(){
                    $('#sell_submit').css({'pointer-events': 'auto', opacity: 1});
                    $('#sell_waiting').waiting('disable');
                }, 5000);
            }
        });
    });
    $('#buy_submit').click(function(){
//        $('form#buy_form').submit();
        $(this).css({'pointer-events': 'none', opacity: 0.2});
        $('#buy_waiting').waiting({
            className: 'waiting-nonfluid',
            elements: 5,
            fluid: false,
            auto: true,
            speed: 500
        });
        var data = $('form#buy_form').serialize();
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: '{{ url('money/trade') }}',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(res) {
                console.log(res);
                if (res.result == '{{ SUCCESS }}') {
                    if ($('#result_board').hasClass('alert-error2')) {
                        $('#result_board').removeClass('alert-error2');
                    }
                    $('#result_board').addClass('alert-success2');
                    $('#result_board').html('<span>{{ trans('money.trade.req_success') }}</span>');
                } else if (res.result == '{{ LOW_BALANCE }}') {
                    if ($('#result_board').hasClass('alert-success2')) {
                        $('#result_board').removeClass('alert-success2');
                    }
                    $('#result_board').addClass('alert-error2');
                    $('#result_board').html('<span>{{ trans('money.trade.fail_by_balance') }}</span>');
                } else if (res.result == '{{ FAIL }}') {
                    if ($('#result_board').hasClass('alert-success2')) {
                        $('#result_board').removeClass('alert-success2');
                    }
                    $('#result_board').addClass('alert-error2');
                    $('#result_board').html('<span>{{ trans('money.trade.fail') }}</span>');
                } else {
                    if ($('#result_board').hasClass('alert-success2')) {
                        $('#result_board').removeClass('alert-success2');
                    }
                    $('#result_board').addClass('alert-error2');
                    $('#result_board').html('<span>{{ trans('money.trade.fail') }}</span>');
                }
                $('#result_board').fadeIn(1000, function(){
                    setTimeout(function(){
                        $('#result_board').fadeOut('slow', 'swing');
                    }, 10000);
                });
                $('#result_board')[0].scrollIntoView(false);
                setTimeout(function(){
                    $('#buy_submit').css({'pointer-events': 'auto', opacity: 1});
                    $('#buy_waiting').waiting('disable');
                }, 5000);
            },
            error: function(err) {
                if ($('#result_board').hasClass('alert-success2')) {
                    $('#result_board').removeClass('alert-success2');
                }
                $('#result_board').addClass('alert-error2');
                $('#result_board').html('<span>{{ trans('money.trade.fail') }}</span>');
                $('#result_board')[0].scrollIntoView(false);
                setTimeout(function(){
                    $('#sell_submit').css({'pointer-events': 'auto', opacity: 1});
                    $('#sell_waiting').waiting('disable');
                }, 5000);
            }
        });
    });
    $('#s_mod_market').click(function(){
        $('[name=sellPrice]').attr('disabled', true);
        $('[name=sellPrice]').val($('#askPrice').html() == 0 ? $('#bidPrice').html() : $('#askPrice').html());
        $('[name=sellAmount]').val('');
        $('[name=sellTotal]').val('');
    });
    $('#s_mod_limit').click(function(){
        $('[name=sellPrice]').attr('disabled', false);
        $('[name=sellPrice]').val('');
        $('[name=sellAmount]').val('');
        $('[name=sellTotal]').val('');
    });
    $('#b_mod_market').click(function(){
        $('[name=buyPrice]').attr('disabled', true);
        $('[name=buyPrice]').val($('#bidPrice').html() == 0 ? $('#askPrice').html() : $('#bidPrice').html());
        $('[name=buyAmount]').val('');
        $('[name=buyTotal]').val('');
    });
    $('#b_mod_limit').click(function(){
        $('[name=buyPrice]').attr('disabled', false);
        $('[name=buyPrice]').val('');
        $('[name=buyAmount]').val('');
        $('[name=buyTotal]').val('');
    });
    $('[name="bid"]').click(function(){
        //alert("dd");
        if ($('[name=sellPrice]').attr('disabled') == true) return;
        index = $(this).attr('index');
        $('[name=sellAmount]').val($('#bid_total' + index).html());
        $('[name=sellPrice]').val($('#bid_price' + index).html());
        $('[name="sellTotal"]').val($('#bid_amount' + index).html());
        //$('[name="ask_buy_account"]').val($('#bid_account' + index).val());
    });
    $('[name=sellAmount]').bind('focusin, keyup', function(e){
        check_decimal_count($(this));
        var amount = $(this).val();
        var price = $('[name=sellPrice]').val();
        var total = round_down(amount * price, 8);
        $('[name="sellTotal"]').val(total);
    });
    $('[name=sellAmount]').change(function(){
        check_decimal_count($(this));
        var amount = $(this).val();
        var price = $('[name=sellPrice]').val();
        var total = round_down(amount * price, 8);
        $('[name="sellTotal"]').val(total);
    });
    $('[name=sellPrice]').bind('focusin, keyup', function(){
        check_decimal_count($(this));
        var price = $(this).val();
        var amount = $('[name=sellAmount]').val();
        var total = round_down(amount * price, 8);
        $('[name="sellTotal"]').val(total);
    });
    $('[name=sellPrice]').change(function(){
        check_decimal_count($(this));
        var price = $(this).val();
        var amount = $('[name=sellAmount]').val();
        var total = round_down(amount * price, 8);
        $('[name="sellTotal"]').val(total);
    });
    $('[name=buyAmount]').bind('focusin, keyup', function(){
        check_decimal_count($(this));
        var amount = $(this).val();
        var price = $('[name=buyPrice]').val();
        var total = round_up(amount * price, 8);
        $('[name="buyTotal"]').val(total);
    });
    $('[name=buyAmount]').change(function(){
        check_decimal_count($(this));
        var amount = $(this).val();
        var price = $('[name=buyPrice]').val();
        var total = round_up(amount * price, 8);
        $('[name="buyTotal"]').val(total);
    });
    $('[name=buyPrice]').bind('focusin, keyup', function(){
        check_decimal_count($(this));
        var price = $(this).val();
        var amount = $('[name=buyAmount]').val();
        var total = round_up(amount * price, 8);
        $('[name="buyTotal"]').val(total);
    });
    $('[name=buyPrice]').change(function(){
        check_decimal_count($(this));
        var price = $(this).val();
        var amount = $('[name=buyAmount]').val();
        var total = round_up(amount * price, 8);
        $('[name="buyTotal"]').val(total);
    });
    function getGatewayList2(curr, type) {
        var base_gateway = '{{ Config::get('conf.base_gateway') }}';
        if (type == 'pri') {
            $('[name=p_issuer]').val('');
            if (curr == 'BSC') $('[name=p_issuer]').prop('disabled', true);
            else $('[name=p_issuer]').prop('disabled', false);
        } else if (type == '2nd')
            $('[name=s_issuer]').val('');
            if (curr == 'BSC') $('[name=s_issuer]').prop('disabled', true);
            else $('[name=s_issuer]').prop('disabled', false);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'GET',
            url: "{{ url('/money/gateway-list2/') }}" + "/" + curr,
            success: function(msg) {
                //console.log(msg);
                var gateways = JSON.parse(msg);
                var curr_gateways = "";
                if (type == 'pri') {
//                    $('[name=p_issuer]').val(base_gateway);
                    $('[name=p_issuer]').val('');
                    $('[name=p_issuer]').prop('address', '');
                } else if (type == '2nd') {
//                    $('[name=s_issuer]').val(base_gateway);
                    $('[name=s_issuer]').val('');
                    $('[name=s_issuer]').prop('address', '');
                }
                gateways.forEach(function(d, index){
                    if (d == null) return;
                    if (type == 'pri') {
                        curr_gateways += '<li><a class="cursor-pt" name="p_issuer_list">' + d.name + '</a><span class="ui-helper-hidden">' + d.owner_address + '</span></li>';
                        if (index == 0 || d.name == '{{ old('p_issuer') }}') {
                            $('[name=p_issuer]').val(d.name);
                            $('[name=p_issuer]').prop('address', d.owner_address);
                        }
                    } else if (type == '2nd') {
                        curr_gateways += '<li><a class="cursor-pt" name="s_issuer_list">' + d.name + '</a><span class="ui-helper-hidden">' + d.owner_address + '</span></li>';
                        if (index == 0 || d.name == '{{ old('s_issuer') }}') {
                            $('[name=s_issuer]').val(d.name);
                            $('[name=s_issuer]').prop('address', d.owner_address);
                        }
                    }
                });
                /*if (type == 'pri') {
                    $('#p_issuer_dropdown').html('<li><a class="cursor-pt" name="p_issuer_list">' + base_gateway + '</a></li>' + curr_gateways);
                    {{--$('[name=p_issuer]').val('{{ !empty(old('p_issuer')) ? old('p_issuer') : Config::get('conf.base_gateway') }}');--}}
                } else if (type == '2nd') {
                    $('#s_issuer_dropdown').html('<li><a class="cursor-pt" name="s_issuer_list">' + base_gateway + '</a></li>' + curr_gateways);
                    {{--$('[name=s_issuer]').val('{{ !empty(old('s_issuer')) ? old('s_issuer') : Config::get('conf.base_gateway') }}');--}}
                }*/
                if (type == 'pri') {
                    $('#p_issuer_dropdown').html(curr_gateways);
                    {{--$('[name=p_issuer]').val('{{ !empty(old('p_issuer')) ? old('p_issuer') : Config::get('conf.base_gateway') }}');--}}
                } else if (type == '2nd') {
                    $('#s_issuer_dropdown').html(curr_gateways);
                    {{--$('[name=s_issuer]').val('{{ !empty(old('s_issuer')) ? old('s_issuer') : Config::get('conf.base_gateway') }}');--}}
                }
                /*
                 * get offer list(ask, bid)
                 */
                getOffers();
                $('[name=p_issuer_list]').click(function(){
                    $('[name=p_issuer]').val($(this).html());
                    if ($(this).next('span.ui-helper-hidden').length > 0) {
                        $('[name=p_issuer]').prop('address', $(this).next('span.ui-helper-hidden').html());
                    } else {
                        $('[name=p_issuer]').prop('address', '');
                    }
                    getOffers();
                });
                $('[name=s_issuer_list]').click(function(){
                    $('[name=s_issuer]').val($(this).html());
                    if ($(this).next('span.ui-helper-hidden').length > 0) {
                        $('[name=s_issuer]').prop('address', $(this).next('span.ui-helper-hidden').html());
                    } else {
                        $('[name=s_issuer]').prop('address', '');
                    }
                    getOffers();
                });
            },
            error: function(err) {
                //console.log(err);
            }
        });
    }
    $('#flip').click(function(){
        var tmp = "";
        tmp = $('#btnBuy_fromUnit').html();
        $('#btnBuy_fromUnit').html($('#btnBuy_toUnit').html());
        $('#btnBuy_toUnit').html(tmp);
        var pri_curr = $('#btnBuy_fromUnit').children('span').html();
        var sec_curr = $('#btnBuy_toUnit').children('span').html();
        $('[name=primaryCurrency]').html(pri_curr);
        $('[name=primaryCurrency]').val(pri_curr);
        $('[name=2ndCurrency]').html(sec_curr);
        $('[name=2ndCurrency]').val(sec_curr);

        /*if (pri_curr == 'BSC' && sec_curr == 'JPY') {
            $('#exchange_all').css('display', 'block');
        } else {
            $('#exchange_all').css('display', 'none');
        }*/
        $('#btnBuy_fromUnit').trigger('change');
        $('#btnBuy_toUnit').trigger('change');
    });
    $('a[name=view_more_bids]').unbind('click').click(function() {
        count_view_bid = 0;
        getBid_Offers();
    });
    $('a[name=view_more_asks]').unbind('click').click(function() {
        count_view_ask = 0;
        getAsk_Offers();
    });
    var shown = false;
    $('#exchange_all').click(function(){
            var p_curr = $('[name=primaryCurrency]').html();
            var p_issuer = $('[name=p_issuer]').prop('address');
            var s_curr = $('[name=2ndCurrency]').html();
            var s_issuer = $('[name=s_issuer]').prop('address');

            if (shown == true) return;
            shown = true;
            noty({
                text: '{{ trans('money.trade.confirm_exchange') }}',
                layout: 'topRight',
                buttons: [
                    {addClass : 'btn btn-success',
                     text : 'Ok',
                     onClick : function($noty) {
                        $noty.close();
                        shown = false;
                        $.post('{{ url('money/exchange') }}', { base: p_curr + "/" + p_issuer, counter: s_curr + "/" + s_issuer, src: 'counter' }, function(msg){
                            //console.log(msg);
                            if (msg.result == 'LowBalance') {
                                if ($('#result_board').hasClass('alert-success2')) {
                                    $('#result_board').removeClass('alert-success2');
                                }
                                $('#result_board').addClass('alert-error2');
                                $('#result_board').html('<span>{{ trans('money.trade.fail_by_balance') }}</span>');
                            } else if (msg.result == 'Failed') {
                                if ($('#result_board').hasClass('alert-success2')) {
                                    $('#result_board').removeClass('alert-success2');
                                }
                                $('#result_board').addClass('alert-error2');
                                $('#result_board').html('<span>{{ trans('money.trade.fail') }}</span>');
                            } else if (msg.result == 'Success') {
                                if ($('#result_board').hasClass('alert-error2')) {
                                    $('#result_board').removeClass('alert-error2');
                                }
                                $('#result_board').addClass('alert-success2');
                                $('#result_board').html('<span>{{ trans('money.trade.req_success') }}</span>');
                            }
                            $('#result_board').fadeIn(1000, function(){
                                setTimeout(function(){
                                    $('#result_board').fadeOut('slow', 'swing');
                                }, 10000);
                            });
                            scrollToElement('result_board');
                        }, "json");
                     }
                    },
                    {addClass : 'btn btn-danger',
                        text     : 'Cancel',
                        onClick  : function($noty) {
                            $noty.close();
                            shown = false;
                        }
                    },
                ]
            });
    });
    function round_up(value, offset) {
        if (typeof offset == 'undefined') {
            offset = 8;
        }
        var delta = Math.pow(10, offset);
        var s = parseFloat(Math.ceil(parseFloat(value) * delta) / delta).toString();
        s = number_format(s, offset);
        return s;
    }
    function round_down(value, offset) {
        if (typeof offset == 'undefined') {
            offset = 8;
        }
        var delta = Math.pow(10, offset);
        var s = parseFloat(Math.floor(parseFloat(value) * delta)/ delta).toString();
        s = number_format(s, offset);
        return s;
    }
    function number_format(value, offset) {
        if (typeof offset == 'undefined') {
            offset = 8;
        }
        var s = parseFloat(value).toString();
        if (s.indexOf('.') == -1) s += '.';
        while (s.length < s.indexOf('.') + offset + 1) s += '0';
        return s;
    }
    function check_decimal_count(obj, count) {
        if (!count) count = 8;
        var val = $(obj).val().split('.');
        if (val.length == 1) return;
        $(obj).val(val[0] + "." + val[1].substr(0, count));
    }
</script>
@stop