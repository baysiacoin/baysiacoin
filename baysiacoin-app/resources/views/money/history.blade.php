@extends('layouts.user')

@section('header')
<link rel="stylesheet" href="{{ asset('css/jquery/jquery.ui.all.css') }}" type="text/css" />
@stop

@section('content')
<section class="scrollable padder">
  <div class="m-b-md">
    <h3 class="m-b-none">{{ trans('money.history.title') }}</h3>
  </div>
  <section class="panel panel-default">
    <header class="panel-heading">
      {{ trans('money.history.title') }}
    </header>
    <form method="post" id="frm_condition" action=" {{ url('/money/history/fund') }} " >
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <div class="row wrapper">
        <div class="col-sm-5 m-b-xs">
          <select class="input-sm form-control input-s-sm inline v-middle" id="apply_sel">
            <option value="0">{{ trans('money.history.item_deposit') }}</option>
            <option value="1">{{ trans('money.history.item_order') }}</option>
            <option value="2">{{ trans('money.history.item_transaction') }}</option>
            <option value="3">{{ trans('money.history.item_withdraw') }}</option>
            <option value="4">{{ trans('money.history.item_transfer') }}</option>
          </select>
          <!--button class="btn btn-sm btn-default">適用</button-->
        </div>
        <div class="col-sm-4 m-b-xs">
          <div class="btn-group" data-toggle="buttons">
            <label id="date_filter" class="btn btn-sm btn-default {{ $date_filter == 'day' ? 'active' : '' }}" date_filter="day">
              <input type="radio" id="option1"> {{ trans('money.history.filter_day') }}
            </label>
            <label id="date_filter" class="btn btn-sm btn-default {{ $date_filter == 'week' ? 'active' : '' }}" date_filter="week">
              <input type="radio" id="option2" value="week"> {{ trans('money.history.filter_week') }}
            </label>
            <label id="date_filter" class="btn btn-sm btn-default {{ $date_filter == 'month' ? 'active' : '' }}" date_filter="month">
              <input type="radio" id="option2" value="month"> {{ trans('money.history.filter_month') }}
            </label>
            <label id="date_filter" class="btn btn-sm btn-default {{ $date_filter == 'year' ? 'active' : '' }}" date_filter="year">
              <input type="radio" id="option2" value="year"> {{ trans('money.history.filter_year') }}
            </label>
          </div>
        </div>
        <div class="col-sm-3">
          <div class="input-group" id="div_search" style="display:none;">
            <input type="text" class="input-sm form-control" name="wallet_address_filter" placeholder="{{ trans('money.history.search_key') }}" >
            <span class="input-group-btn">
              <button class="btn btn-sm btn-default" type="button">{{ trans('money.history.btn_search') }}</button>
            </span>
          </div>
        </div>
      </div>
    </form>
    <div class="table-responsive">
      <table class="table table-striped b-t b-light" style="text-align: center">
        <thead>
        @if (($type = isset($type) ? $type : 0) == 0 || $type == 3)
          <tr>
            <th class="th-sortable" data-toggle="class" style="text-align: center">{{ trans('money.history.col_no') }}
              <!--span class="th-sort">
                <i class="fa fa-sort-down text"></i>
                <i class="fa fa-sort-up text-active"></i>
                <i class="fa fa-sort"></i>
              </span-->
            </th>
            <th style="text-align: center">{{ trans('money.history.col_wallet_address') }}</th>
            <th style="text-align: center">{{ trans('money.history.col_tx_id') }}</th>
            <th style="text-align: center">{{ trans('money.history.col_amount') }}</th>
            <th class="th-sortable" data-toggle="class" style="text-align: center">{{ trans('money.history.col_datetime') }}
              <!--span class="th-sort">
                <i class="fa fa-sort-down text"></i>
                <i class="fa fa-sort-up text-active"></i>
                <i class="fa fa-sort"></i>
              </span-->
            </th>
            <th style="text-align: center">{{ trans('money.history.col_result') }}</th>
          </tr>
        @elseif ($type == 1)
          <tr>
            <th class="th-sortable" data-toggle="class" style="text-align: center">{{ trans('money.history.col_no') }}
                      <!--span class="th-sort">
                <i class="fa fa-sort-down text"></i>
                <i class="fa fa-sort-up text-active"></i>
                <i class="fa fa-sort"></i>
              </span-->
            </th>
            <th class="th-sortable" data-toggle="class" style="text-align: center">{{ trans('money.history.col_datetime') }}
            <th style="text-align: center">{{ trans('money.trade.sell') }}</th>
            <th style="text-align: center">{{ trans('money.trade.buy') }}</th>
            <th style="text-align: center">{{ trans('money.history.col_status') }}</th>
          </tr>
        @elseif ($type == 2 || $type == 4)
          <tr>
            <th class="th-sortable" data-toggle="class" style="text-align: center">{{ trans('money.history.col_no') }}
                      <!--span class="th-sort">
                <i class="fa fa-sort-down text"></i>
                <i class="fa fa-sort-up text-active"></i>
                <i class="fa fa-sort"></i>
              </span-->
            </th>
            <th class="th-sortable" data-toggle="class" style="text-align: center">{{ trans('money.history.col_datetime') }}</th>
            <th style="text-align: center">{{ trans('money.history.col_trans_amount') }}</th>
            <th style="text-align: center">{{ $type == 2 ? trans('money.history.col_from_to') : trans('money.history.col_wallet_address') }}</th>
          </tr>
        @endif

        </thead>
        <tbody>
        @if (isset($results))
          {{--@if ($type == 4)--}}
            {{--@foreach ($results as $result)--}}
              {{--<tr>--}}
                {{--<td>{{ isset($i) ? ++$i : ($i = 1) }}</td>--}}
                {{--<td>{{ $result->receiver_wallet_address }}</td>--}}
                {{--<td>{{ $result->transaction_id }}</td>--}}
                {{--<td>--}}
                  {{--{{ $result->amount }}&nbsp;--}}
                  {{--@if ($result->wallet_id == 1) {{ COIN_JPY }}--}}
                  {{--@elseif($result->wallet_id == 3) {{ COIN_BSC }}--}}
                  {{--@endif--}}
                {{--</td>--}}
                {{--<td>{{ $result->updated_at }}</td>--}}
                {{--<td>--}}
                  {{--@if ($result->paid == 1)--}}
                    {{--{{ trans('money.history.status_requested') }}--}}
                  {{--@elseif ($result->paid == 2)--}}
                    {{--{{ trans('money.history.status_confirmed') }}--}}
                  {{--@elseif ($result->paid == 3)--}}
                    {{--{{ trans('money.history.status_completed') }}--}}
                  {{--@elseif ($result->paid == 11)--}}
                    {{--{{ trans('money.history.status_failed') }}--}}
                  {{--@endif--}}
                {{--</td>--}}
              {{--</tr>--}}
            {{--@endforeach--}}
          @if ($type == 1 || $type == 2 || $type == 4)

          @else
            @foreach ($results as $result)
              <tr>
                <td>{{ isset($i) ? ++$i : ($i = 1) }}</td>
                <td>{{ $result->issuer }}</td>
                <td>{{ $result->transaction_id }}</td>
                <td>{{ $result->amount }}&nbsp;
                  @if ($result->wallet_id == 1) {{ COIN_JPY }}
                  @elseif($result->wallet_id == 3) {{ COIN_BSC }}
                  @endif
                </td>
                <td>{{ $result->updated_at }}</td>
                <td>
                  @if ($result->paid == 1)
                    {{ trans('money.history.status_requested') }}
                  @elseif ($result->paid == 2)
                    {{ trans('money.history.status_confirmed') }}
                  @elseif ($result->paid == 3)
                    {{ trans('money.history.status_completed') }}
                  @elseif ($result->paid == 11)
                    {{ trans('money.history.status_failed') }}
                  @endif</td>
              </tr>
            @endforeach
          @endif
        @endif
        </tbody>
      </table>
    </div>
    <footer class="panel-footer">
      <div class="row">
        <div class="col-sm-4 hidden-xs">
            <select class="input-sm form-control input-s-sm inline v-middle" id="apply_sel">
                <option value="0">{{ trans('money.history.item_deposit') }}</option>
                <option value="1">{{ trans('money.history.item_order') }}</option>
                <option value="2">{{ trans('money.history.item_transaction') }}</option>
                <option value="3">{{ trans('money.history.item_withdraw') }}</option>
                <option value="4">{{ trans('money.history.item_transfer') }}</option>
            </select>
            {{--<button class="btn btn-sm btn-default">適用</button>--}}
            <!--
              <select class="input-sm form-control input-s-sm inline v-middle">
                <option value="0">Bulk action</option>
                <option value="1">Delete selected</option>
                <option value="2">Bulk edit</option>
                <option value="3">Export</option>
              </select>
              <button class="btn btn-sm btn-default">Apply</button>                  
            -->
        </div>
        <div class="col-sm-4 text-center">
          <small class="text-muted inline m-t-sm m-b-sm">{{ trans('money.history.item_show') }}</small>
        </div>
        <div class="col-sm-4 text-right text-center-xs" id='page_render'>
          @if ($type == 0)
          <?php echo str_replace('fund/?', 'fund?', $results->render()) ?>
          @elseif ($type == 1)
          @elseif ($type == 2)
          @elseif ($type == 3)
            <?php echo str_replace('withdraw/?', 'withdraw?', $results->render()) ?>
          @elseif ($type == 4)
          @endif
          <!--ul class="pagination pagination-sm m-t-none m-b-none">
            <!--li><a href="#"><i class="fa fa-chevron-left"></i></a></li>
            <li><a href="#">1</a></li>
            <li><a href="#">2</a></li>
            <li><a href="#">3</a></li>
            <li><a href="#">4</a></li>
            <li><a href="#">5</a></li>
            <li><a href="#"><i class="fa fa-chevron-right"></i></a></li>
          </ul-->
        </div>
        <!--div class='row right' id='page_render'><!--?php echo str_replace('fund/?', 'fund?', $deposits->render()) ?></div-->
      </div>
    </footer>
  </section>
</section>
@stop

@section('footer')
  <script language="javascript" src="{{asset('js/stellar/stellar-lib.js')}}"></script>
  <script language="javascript">
    var filtered_data = [];
    var history_data = "";
    var count_per_page = 10;
    var pageTo = 1;
    var timeout = 3000; //3s
    var usrAccount = "{{ $wallet_address }}";
    $('ul.nav-main li').removeClass('active');
	  $('ul.nav-main > li:eq(2)').addClass('active');
    $('select#apply_sel').change(function(){
        $('select#apply_sel').val($(this).val());
        var val = $(this).val();
        if (val == 0) location.href = '{{url('/money/history/fund')}}';
        else if (val == 1) location.href = "{{url('/money/history/trade/order')}}";
        else if (val == 2) location.href = "{{url('/money/history/trade/transaction')}}";
        else if (val == 3) location.href =  '{{url('/money/history/withdraw')}}';
        else if (val == 4) location.href =  '{{url('/money/history/transfer')}}';
    });

    var getAccount_Tx = function(type) {
      if (typeof(type) == "undefined") {
        type = 1;// waiting orders
      }

      var Remote = stellar.Remote;
      var remote = new Remote({
        // see the API Reference for available options
        trusted: true,
        local_signing: true,
        local_fee: true,
        fee_cushion: 1.5,
        servers: [
          {
            host: 'baysia.asia'
            , port: 9001
            , secure: true
          }
        ]
      });
      remote.connect(function () {
//          remote.on('transaction_all', transactionListener);
//          remote.on('ledger_closed', ledgerListener);
      });
      var request = remote.requestAccountTx({
        account: usrAccount,
        ledger_index_min: "-1",
        ledger_index_max: "-1",
        limit: "200"
      });
      request.callback(function (err, res) {
        console.log(err);
        if (!err) {
          //console.log('res', res);
          filtered_data = [];

          for (var i = 0; i < res.transactions.length; i++) {
            var meta = res.transactions[i].meta;
            var tx = res.transactions[i].tx;
             
            var cancel_valid = meta.AffectedNodes.some(function(d){
              return d.DeletedNode && d.DeletedNode.LedgerEntryType == 'Offer';
            });            
            // if (meta.TransactionResult != 'tesSUCCESS') continue;
            if (type == 1 && ("OfferCreate" != tx.TransactionType && ("OfferCancel" != tx.TransactionType || !cancel_valid) /* || (usrAccount != tx.Account )*/)) continue;
            else if (type == 2 && ("Payment" != tx.TransactionType || (usrAccount != tx.Account && usrAccount != tx.Destination) )) continue;
            else if (type == 4 && ("Payment" != tx.TransactionType || usrAccount != tx.Account)) continue;
            filtered_data.push(res.transactions[i]);
          }

          //Store data to local storage.
          set_data_stored(filtered_data, '{{ $type }}');
          //display data stored
          flush_filtered_data(pageTo, '{{ $type }}');
          show_pagination(pageTo);

        }
      });
      //Displays stored data when connection times out.
      $(this).bind("connection_timeout", search_data);
      //After 3s if there is no data received shows the cached data.
      setTimeout(function(){
        $(this).trigger('connection_timeout');
      }, timeout);
      request.request();     
    }

    //Filtering module
    var search_data = function() {
      var account = $('[name=wallet_address_filter]').val();
      if ('{{ $type }}' == 2)
      var tmp_data = [];
      filtered_data = [];
      filtered_data = get_data_stored('{{ $type }}');
      if (filtered_data.length == 0) {
        return;
      }
      if (!account) {
        //alert('a');
        flush_filtered_data(1, '{{ $type }}');
        show_pagination(1);
        return;        
      }
      for (var i = 0; i < filtered_data.length; i++) {
        if (account == filtered_data[i].tx.Account || account == filtered_data[i].tx.Destination) {
          tmp_data.push(filtered_data[i]);
        }
      }
      filtered_data = tmp_data;
      flush_filtered_data(1, '{{ $type }}');
      show_pagination(1);
      // return false;
    }

    var flush_filtered_data = function(pageTo, type) {
      //var meta = data.transactions[i].meta;
      if (typeof(pageTo) == "undefined") {
        pageTo = 1;
      }
      if (typeof(type) == "undefined") {
        type = 1;//waiting orders
      }

//      alert(pageTo);
      if (pageTo < 1) return;
      history_data = "";
      //var index = 0;      
      if (filtered_data.length == 0) {
          $('table tbody').html('<tr><td colspan="4">{{ trans('money.trade.nodata') }}</td></tr>');
          return;
      }
      var data_pos = count_per_page * (pageTo - 1);
      // console.log(filtered_data);
      for (var i = 0; i < count_per_page; i++) {
        if (typeof(filtered_data[data_pos + i]) == "undefined") break;
        var timestamp = stellar.utils.toTimestamp(filtered_data[data_pos + i].tx.date);
        var date = new Date(timestamp);
        
        if (type == 1) {   
          var created = false;
          var action = "{{ trans('money.history.status_created') }}";
          var taker_gets = [];
          var taker_pays = [];
          var _affected = new offerExercised();

          if (filtered_data[data_pos + i].tx.TransactionType == 'OfferCreate') {
            taker_gets = filtered_data[data_pos + i].tx.TakerGets;
            taker_pays = filtered_data[data_pos + i].tx.TakerPays;            

            created = filtered_data[data_pos + i].meta.AffectedNodes.some(function(d, index) {
              /*if (i== 5 && index == 3) {
                // console.log(index, d.CreatedNode && (d.CreatedNode.LedgerEntryType == 'Offer') && (d.CreatedNode.NewFields.Account == usrAccount));
                console.log(d.CreatedNode && d.CreatedNode.NewFields && d.CreatedNode.NewFields.Account ? d.CreatedNode.NewFields.Account : "");
                console.log(usrAccount);
              }*/
              return d.CreatedNode && d.CreatedNode.LedgerEntryType == 'Offer'/* && d.CreatedNode.NewFields.Account == usrAccount*/;
            });
            
            if (created) {
              //console.log(i);
              history_data += '<tr><td>' + (data_pos + i + 1) + '</td><td>' + date.toLocaleString() + '</td><td style="text-align: right;">' + (!!taker_gets.value ? number_format(taker_gets.value, 6) : number_format(taker_gets / 1000000, 6)) + '&nbsp;' + (!!taker_gets.currency ? taker_gets.currency : 'BSC')
                  + '</td><td style="text-align: right;">' + (!!taker_pays.value ? number_format(taker_pays.value, 6) : number_format(taker_pays / 1000000, 6)) + '&nbsp;' + (!!taker_pays.currency ? taker_pays.currency : 'BSC') + '</td><td>' + action + '</td></tr>';              
            } else {
              //console.log(i, filtered_data[data_pos + i]);                                
              filtered_data[data_pos + i].meta.AffectedNodes.some(function(affNode, index) {
                var node = affNode.ModifiedNode || affNode.DeletedNode;

                if (!node || node.LedgerEntryType !== 'Offer') {
                  return;
                }

                if (!node.PreviousFields || !node.PreviousFields.TakerPays || !node.PreviousFields.TakerGets) {
                  return;
                }
            
                var counterparty   = node.FinalFields.Account,
                  pay, get;

                if ( typeof node.PreviousFields.TakerPays === "object" ) {
                  pay = {
                    currency : node.PreviousFields.TakerPays.currency,
                    issuer   : node.PreviousFields.TakerPays.issuer,
                    value    : node.PreviousFields.TakerPays.value - node.FinalFields.TakerPays.value
                  }
                  
                } else {                
                  pay = {
                    currency : "BSC",
                    issuer   : null,
                    value    : (node.PreviousFields.TakerPays - node.FinalFields.TakerPays) / 1000000.0, // convert from drops
                  }
                }

                if ( typeof node.PreviousFields.TakerGets === "object" ) {
                  get = {
                    currency : node.PreviousFields.TakerGets.currency,
                    issuer   : node.PreviousFields.TakerGets.issuer,
                    value    : node.PreviousFields.TakerGets.value - node.FinalFields.TakerGets.value
                  }
                  
                } else {                
                  get = {
                    currency : "BSC",
                    issuer   : null,
                    value    : (node.PreviousFields.TakerGets - node.FinalFields.TakerGets) / 1000000.0
                  }
                }

                _affected.account = filtered_data[data_pos + i].tx.Account;
                _affected.buy = new exercised(pay.currency, pay.issuer, pay.value);
                _affected.sell = new exercised(get.currency, get.issuer, get.value);
                _affected.counterparty = counterparty;

                return true;
              });
              
              //console.log(i, _affected);

              if (!_affected.account || !_affected.sell || !_affected.buy || !_affected.counterparty) continue;

              if (_affected.account == usrAccount) {
                action = '{{ trans('money.history.status_crossed') }}';
              } else {
                action = '{{ trans('money.history.status_crossed_by') }}';
              }
              history_data += '<tr><td>' + (data_pos + i + 1) + '</td><td>' + date.toLocaleString() + '</td><td style="text-align: right;">' + number_format(_affected.sell.value, 6)  + '&nbsp;' + _affected.sell.currency
                    + '</td><td style="text-align: right;">' + number_format(_affected.buy.value, 6)  + '&nbsp;' + _affected.buy.currency + '</td><td>' + action + '</td></tr>';
            } 
          } else if (filtered_data[data_pos + i].tx.TransactionType == 'OfferCancel') {
            filtered_data[data_pos + i].meta.AffectedNodes.some(function(d){
              if (d.DeletedNode && d.DeletedNode.LedgerEntryType == 'Offer') {
                taker_gets = d.DeletedNode.FinalFields.TakerGets;
                taker_pays = d.DeletedNode.FinalFields.TakerPays;
                action = '{{ trans('money.history.status_cancelled') }}';
                history_data += '<tr><td>' + (data_pos + i + 1) + '</td><td>' + date.toLocaleString() + '</td><td style="text-align: right;">' + (!!taker_gets.value ? number_format(taker_gets.value, 6) : number_format(taker_gets / 1000000, 6)) + '&nbsp;' + (!!taker_gets.currency ? taker_gets.currency : 'BSC')
                      + '</td><td style="text-align: right;">' + (!!taker_pays.value ? number_format(taker_pays.value, 6) : number_format(taker_pays / 1000000, 6)) + '&nbsp;' + (!!taker_pays.currency ? taker_pays.currency : 'BSC') + '</td><td>' + action + '</td></tr>';    
              }
            });            
          }          
        } else if (type == 2) {
          var account = filtered_data[data_pos + i].tx.Account;
          var destination = filtered_data[data_pos + i].tx.Destination;
          var amount = typeof(filtered_data[data_pos + i].tx.Amount.value) != "undefined" ? filtered_data[data_pos + i].tx.Amount.value : filtered_data[data_pos + i].tx.Amount / 1000000;
          var currency = typeof(filtered_data[data_pos + i].tx.Amount.currency) != "undefined" ? filtered_data[data_pos + i].tx.Amount.currency : 'BSC' ;
//          console.log(amount);
//          console.log(currency);
          if (usrAccount == account) {
            history_data += '<tr><td>' + (data_pos + i + 1) + '</td><td>' + date.toLocaleString() + '</td><td style="text-align: right;">Sent ' + amount + '&nbsp;' + currency + '&nbsp;' + '</td><td>' + destination + '</td></tr>';
          } else if (usrAccount == destination) {
            history_data += '<tr><td>' + (data_pos + i + 1) + '</td><td>' + date.toLocaleString() + '</td><td style="text-align: right;">Received ' + amount + '&nbsp;' + currency + '&nbsp;' + '</td><td>' + account + '</td></tr>';
          }
        } else if (type == 4) {
          var account = filtered_data[data_pos + i].tx.Account;
          var destination = filtered_data[data_pos + i].tx.Destination;
          var amount = typeof(filtered_data[data_pos + i].tx.Amount.value) != "undefined" ? filtered_data[data_pos + i].tx.Amount.value : filtered_data[data_pos + i].tx.Amount / 1000000;
          var currency = typeof(filtered_data[data_pos + i].tx.Amount.currency) != "undefined" ? filtered_data[data_pos + i].tx.Amount.currency : 'BSC' ;
          console.log(amount);
          console.log(currency);
          if (usrAccount == account) {
            history_data += '<tr><td>' + (data_pos + i + 1) + '</td><td>' + date.toLocaleString() + '</td><td style="text-align: right;">Sent ' + amount + '&nbsp;' + currency + '&nbsp;' + '</td><td>' + destination + '</td></tr>';
          }
        }
        //console.log(date);
        //console.log("dateUTC=" + date.toUTCString());
        //console.log("dateLocaleDate=" + date.toLocaleString());
      }
      //console.log(date);
      //console.log("dateUTC=" + date.toUTCString());
      //console.log("dateLocaleDate=" + date.toLocaleString());
      //alert(history_data);
      $('table tbody').html(history_data);
    }
    var show_pagination = function(pageTo) {
      //      alert(pageTo);
      var count = filtered_data.length;
      var share = parseInt(count / count_per_page);
      var restCount = parseInt(count % count_per_page);
      var pageCount =  (restCount == 0) ? share : share + 1;
      if (pageTo < 1) {
        pageTo = 1;
      } else if (pageTo > pageCount) {
        pageTo = pageCount;
      }
      if (pageCount > 1) {
        var paginationText = '<ul class="pagination"><li' + ((pageTo == 1) ?  ' class="disabled"' : '') + '><a href="#"'  + ((pageTo == 1) ?  ' ' : ' onclick="javascript: page_before();"') + '>&laquo;</a></li>';
        for (var i = 0; i < pageCount; i++) {
          paginationText += '<li' + ((pageTo == i + 1) ? ' class="active"' : '') + '><a href="#" onclick="javascript: page_go(this);">' + (i + 1) + '</a></li>';
        }
        paginationText += '<li' + ((pageTo == pageCount) ?  ' class="disabled"' : '') +'><a href="#"'  + ((pageTo == pageCount) ?  ' ' : ' onclick="javascript: page_next();"') + '>&raquo;</a></li></ul>'
        $('#page_render').html(paginationText);
      } else
        $('#page_render').html('');
    }

    var page_go = function(obj) {
      pageTo = obj.innerHTML;
      flush_filtered_data(pageTo, '{{ $type }}');
      show_pagination(pageTo);
    }
    var page_next = function() {
//      alert(pageTo);
      flush_filtered_data(++pageTo, '{{ $type }}');
      show_pagination(pageTo);
    }
    var page_before = function() {
//      alert(pageTo);
      flush_filtered_data(--pageTo, '{{ $type }}');
      show_pagination(pageTo);
    }

    $(document).ready(function() {
      setPreviousURL();
      if (2 == '{{ $type }}' || 4 == '{{ $type }}') {
        $('#div_search').attr('style', '');
      }
      /* Loading data from live.stellar.org*/
      if ('{{ $type }}' == 1 || '{{ $type }}' == 2 ||  '{{ $type }}' == 4) {
        $('table tbody').html('<tr><td colspan="4"><img src="{{ asset('images/waiting.gif') }}"></td></tr>');
        if (!!usrAccount) {
          getAccount_Tx('{{ $type }}');          
        } else {
          $('table tbody').html('<tr><td colspan="4">{{ trans('money.trade.nodata') }}</td></tr>');
        }
      }  
    });
    $('button').click(function() {
      if ('{{ $type }}' == 2 || '{{ $type }}' == 4) {
        search_data();
      }
    });
    $('select#apply_sel').val({{ $type }});
    $('label#date_filter').click(function(){
      if ('{{ $type }}' == 1 || '{{ $type }}' == 2 || '{{ $type }}' == 4) {
        return;
      }
      var type = $('select#apply_sel').val();
      if (type == 0) {
        var url = '{{url('/money/history/fund')}}';
      } else if (type == 1) {
        var url = '{{url('/money/history/trade/order')}}';
      } else if (type == 2) {
        var url = '{{url('/money/history/trade/transaction')}}';
      } else if (type == 3) {
        var url = '{{url('/money/history/withdraw')}}';
      } else if (type == 4) {
        var url = '{{url('/money/history/transfer')}}';
      }
      $('form#frm_condition').attr('action', url + "?date_filter=" + $(this).attr('date_filter'));
      $('form#frm_condition').submit();
    });
    function get_data_stored(type) {
      if (!window.localStorage) {
        return;
      }
      var json;
      if (type == 1) {
        json = window.localStorage.getItem("offer_data_stored");
      } else if (type == 2) {
        json = window.localStorage.getItem("tx_data_stored");
      } else if (type == 4) {
        json = window.localStorage.getItem("transfer_data_stored");
      }
      if (json == null) {
        return '';
      }
      json = JSON.parse(json);
      // expire 30 days
      if (Date.now() - json.timestamp > 2592000000) {
        return '';
      }
      return json.code;
    }
    function set_data_stored(data, type) {
      if (!window.localStorage) {
        return;
      }
      var json = {code: data, timestamp: Date.now()};
      if (type == 1) {
        window.localStorage.setItem("offer_data_stored", JSON.stringify(json));
      } else if (type == 2) {
        window.localStorage.setItem("tx_data_stored", JSON.stringify(json));
      } else if (type == 4) {
        window.localStorage.setItem("transfer_data_stored", JSON.stringify(json));
      }
      return true;
    }
    function number_format(value, offset) {
      var s = parseFloat(Math.floor(parseFloat(value) * 1000000) / 1000000).toString();
      if (s.indexOf('.') == -1) s += '.';
      while (s.length < s.indexOf('.') + offset) s += '0';
      s = s.substr(0, s.indexOf('.') + offset + 1);
      return s;
    }
    function exercised(currency, issuer, value) {
      this.currency = currency;
      this.issuer = issuer;
      this.value = value;
    }
    function offerExercised(account, sell, buy, counterparty) {
      this.account = account;
      this.sell = sell;
      this.buy = buy;
      this.counterparty = counterparty;
    }
    </script>
@stop
