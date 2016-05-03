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
.no {
   width: 4%;
}
.datetime {
    width: 15%;
}
.sell {
    width: 30%;
}
.buy {
    width: 30%;
}
.address {
    width: 21%;
}
table, tr, td {
    border: 1px solid #E6E3E3;
    text-align: center;
}
th {
    border: 1px solid #BFBCBC;
    font-weight: bold;
}
</style>
@stop
@section('content')
<h2>{{ trans('admin.history.tx.title') }}</h2>
<hr class="hr-margin-line">
<hr class="hr-margin">
<div class="col-sm-12">
    <table class="table table-striped" id="list-sells" style="text-align:center;">
        <thead>
            <tr>
                <th class="no">{{ trans('admin.history.tx.no') }}</th>
                <th class="datetime">{{ trans('admin.history.tx.datetime') }}</th>
                <th class="sell" colspan="3">{{ trans('admin.history.tx.sell') }}</th>
                <th class="buy" colspan="3">{{ trans('admin.history.tx.buy') }}</th>
                <th class="address">{{ trans('admin.history.tx.address') }}</th>
            </tr>
        </thead>
        <tbody id="history_tx" style="text-align: center;">
            <tr>
                <td colspan="9">
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
        getTradeHistory();
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

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

    var g_bid_offers;
    var g_ask_offers;

    remote.connect(function() {
        remote.on('transaction_all', transactionListener);
    });
    var getTradeHistory = function(){
        var request = remote.requestTxHistory(1);
        request.callback(function(err, res) {
            var tx_html = "";
            var no, date, sell = [], buy = [], address;
            console.log(err);
            if (!err) {
                console.log('rest', res);
                if (!res.txs || res.txs.length == 0) {
                    $('#history_tx').html("<tr><td colspan=9>{{ trans('money.trade.nodata') }}</td></tr>");
                }
                res = res.txs.filter(function(tx) {
                    return tx.TransactionType == 'OfferCreate';
                });
                res.forEach(function(tx, index) {
                    no = index + 1;
                    date = typeof tx.date == 'number' ? new Date(stellar.utils.toTimestamp(tx.date)).toLocaleString() : '- - - - - - - - -  - - - - - - - - - - -  - -';
                    sell.value = typeof tx.TakerGets == 'string' ? tx.TakerGets / 1000000 : tx.TakerGets.value;
                    sell.currency = typeof tx.TakerGets == 'string' ? 'BSC' : tx.TakerGets.currency;
                    sell.issuer = typeof tx.TakerGets == 'string' ? '- - - - - - - - -  - - - - - - - - - - -  - -' : tx.TakerGets.issuer;
                    buy.value = typeof tx.TakerPays == 'string' ? tx.TakerPays / 1000000 : tx.TakerPays.value;
                    buy.currency = typeof tx.TakerPays == 'string' ? 'BSC' : tx.TakerPays.currency;
                    buy.issuer = typeof tx.TakerPays == 'string' ? '- - - - - - - - -  - - - - - - - - - - -  - -' : tx.TakerPays.issuer;
                    address = typeof tx.Account ? tx.Account : '';
                    tx_html += '<tr><td>' + (index + 1) + '</td><td>' + date + '</td><td>' + number_format(sell.value) + '</td><td>' + sell.currency + '</td><td>'
                            + sell.issuer + '</td><td>' + number_format(buy.value) + '</td><td>' + buy.currency + '</td><td>' + buy.issuer + '</td><td>' + address + '</td></tr>';
                });
                $('#history_tx').html(tx_html);
                console.log(res);
            } else {
                $('#history_tx').html("<tr><td colspan=9>{{ trans('money.trade.nodata') }}</td></tr>");
            }
        });
        request.request();
    }
    function transactionListener (transaction_data) {
        console.log(transaction_data);
        getTradeHistory();
    }
    function ledgerListener (ledger_data) {
        console.log(ledger_data);
    }
    function number_format(value, offset) {
        if (typeof offset == 'undefined') {
            offset = 8;
        }
        var s = parseFloat(Math.floor((parseFloat(value) * 1000000).toFixed(0) * 10) / 10 / 1000000).toString();
        if (s.indexOf('.') == -1) s += '.';
        while (s.length < s.indexOf('.') + offset + 1) s += '0';
        s = s.substr(0, s.indexOf('.') + offset + 1);
        return s;
    }
</script>
@stop