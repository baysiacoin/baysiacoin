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
    <h3 class="m-b-none">Gateway Settings</h3>
  </div>
  <section class="scrollable bg-white">
      <div class="wrapper-lg bg-light" id="gateway_board" style="display: {{ $page == 'gateway' || $page == 'currency' ? 'block' : 'none' }}">
          <ul class="nav nav-tabs m-b-n-xxs bg-light">
              <li id="curr_link" class="{{ $page=='currency' ? 'active' : '' }}">
                  <a href="#currency" data-toggle="tab" class="m-l">Currency</a>
              </li>
              <li id="gateway_link" class="{{ $page=='gateway' ? 'active' : '' }}">
                  <a href="#gateway" data-toggle="tab" class="m-l">Gateway</a>
              </li>
              <a href="javascript: back2Trust();" style="float:right">← Back to Trust</a>
          </ul>
          <div class="tab-content">
              <div class="tab-pane wrapper-lg {{ $page=='gateway' ? 'active' : '' }}" id="gateway">
                  @if ($page == 'gateway' && count($errors) > 0)
                      <div class="alert alert-danger">
                          <ul>
                              @foreach ($errors->all() as $error)
                                  <li>{{ $error }}</li>
                              @endforeach
                          </ul>
                      </div>
                  @elseif ($page == 'gateway' && !empty($gateway_settings_success  = Session::pull('gateway_settings_success', '')))
                      <div class="alert alert-success">
                          <ul>
                              <li>{{ $gateway_settings_success }}</li>
                          </ul>
                      </div>
                  @endif
                  <form class="form-horizontal" method="post" action="{{ url('/money/gateway') }}">
                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                      <input type="hidden" name="page" value="gateway">
                      <div class="form-group">
                          <div class="col-sm-6 col-sm-offset-2" style="min-height: 75px;">
                              <label id="gateway_alert" class="alert alert-success" style="width: 100%; display:none">Settings saved successfully.</label>
                          </div>
                      </div>
                      <div class="form-group">
                          <label class="col-sm-4 col-sm-offset-3">Select curency to trust gateways for that one.</label>
                      </div>
                      <div class="form-group">
                          <div class="col-sm-4 col-sm-offset-3">
                              {{--<input type="text" class="form-control" name="wallet_address">--}}
                              <select class="form-control" name="view_curr">
                                  <option disabled>Please select one currency...</option>
                                  {{--@foreach($all_currs as $currency)--}}
                                      {{--<option value="{{ $currency }}" {{ $currency == old('trust_curr') ? 'selected' : '' }}>{{ $currency }}</option>--}}
                                  {{--@endforeach--}}
                              </select>
                          </div>
                      </div>
                      <div class="line line-dashed b-b line-lg pull-in"></div>
                      {{--<div class="form-group">--}}
                          {{--<div class="col-sm-4 col-sm-offset-3 alert alert-danger">--}}
                              {{--<ul><li>This shows the gateways currently support the currency.</li></ul>--}}
                          {{--</div>--}}
                      {{--</div>--}}
                      <div class="form-group">
                          <div class="col-sm-offset-3">
                              <label class="text-blue col-sm-5">
                                  <h5>
                                      Authorized Gateways
                                  </h5>
                              </label>
                          </div>
                      </div>
                      <div class="form-group">
                          <div class="col-sm-4 col-sm-offset-3">
                              <div class="radio i-checks" id="auth_gateways">
                                  {{--@foreach($other_gateways as $gateway)--}}
                                  {{--<div class="checkbox i-checks" style="display:inline;">--}}
                                      {{--<label style="margin-bottom: 10px;">--}}
                                          {{--<input type="checkbox"/>--}}
                                          {{--<i></i> {{ $gateway['name'] }}<br>{{ $gateway['owner_address'] }}--}}
                                      {{--</label>--}}
                                  {{--</div>--}}
                                  {{--@endforeach--}}
                              </div>
                          </div>
                      </div>
                      <div class="form-group">
                          <div class="col-sm-offset-3">
                              <label class="text-blue col-sm-5">
                                  <h5>
                                      Unauthorized Gateways
                                  </h5>
                              </label>
                          </div>
                      </div>
                      <div class="form-group">
                          <div class="col-sm-4 col-sm-offset-3">
                              <div class="radio i-checks" id="unauth_gateways">
                              </div>
                          </div>
                      </div>
                      <div class="line line-dashed b-b line-lg pull-in"></div>
                      <div class="form-group">
                          <label class="col-sm-3 control-label"></label>
                          <label class="col-sm-3">Add Gateways Manually.</label>
                      </div>
                      <div class="form-group">
                          <div class="col-sm-4 col-sm-offset-3">
                              <span class="text-danger">{{ trans($errors->first('gateway_name')) }}</span>
                              <input type="text" class="form-control" name="new_gateway" value="{{ old('new_gateway') }}" placeholder="Gateway Name" />
                          </div>
                      </div>
                      <div class="line line-dashed b-b line-lg pull-in"></div>
                      <div class="form-group">
                          <div class="col-sm-3 col-sm-offset-3">
                              <button class="btn btn-primary">Submit</button>
                          </div>
                      </div>
                  </form>
              </div>
              <div class="tab-pane wrapper-lg {{ $page=='currency' ? 'active' : '' }}" id="currency">
                  @if ($page == 'currency' && count($errors) > 0)
                      <div class="alert alert-danger">
                          <ul>
                              @foreach ($errors->all() as $error)
                                  <li>{{ $error }}</li>
                              @endforeach
                          </ul>
                      </div>
                  @elseif ($page == 'currency' && !empty($gateway_settings_success  = Session::pull('gateway_settings_success', '')))
                      <div class="alert alert-success">
                          <ul>
                              <li>{{ $gateway_settings_success }}</li>
                          </ul>
                      </div>
                  @endif
                  <form class="form-horizontal" method="post" action="{{ url('/money/gateway') }}">
                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                      <input type="hidden" name="page" value="currency">
                      <div class="form-group">
                          <div class="col-sm-6 col-sm-offset-2" style="min-height: 75px;">
                              <label id="curr_alert" class="alert alert-success" style="width: 100%; display:none">Settings saved successfully.</label>
                          </div>
                      </div>
                      <div class="form-group">
                          <div class="col-sm-8 col-sm-offset-2">
                              <fieldset class="fieldset">
                                  <legend>Currencies issued by gateways</legend>
                                  <div class="form-group">
                                      <div class="col-sm-offset-1">
                                          <div class="form-group" style="margin-bottom:0px;">
                                              <div class="col-sm-12">
                                                  <label>
                                                      <h5>
                                                          Please select currencies to use for setting trust.
                                                      </h5>
                                                  </label>
                                              </div>
                                          </div>
                                          <div class="form-group">
                                              <div class="col-sm-6">
                                                  <div class="radio i-checks">
                                                      <div class="checkbox i-checks">
                                                          <label>
                                                              <input type="checkbox" checked disabled />
                                                              <i></i> {{ COIN_BSC }}
                                                          </label>
                                                      </div>
                                                  </div>
                                              </div>
                                              @foreach($all_currs as $currency)
                                                  <div class="col-sm-6">
                                                      <div class="radio i-checks">
                                                          <div class="checkbox i-checks">
                                                              <label>
                                                                  <input type="checkbox" name="curr2use" value="{{ $currency }}"/>
                                                                  <i></i> {{ $currency }}
                                                              </label>
                                                          </div>
                                                      </div>
                                                  </div>
                                              @endforeach
                                          </div>
                                      </div>
                                  </div>
                              </fieldset>
                          </div>
                      </div>
                      <div class="line line-dashed b-b line-lg pull-in"></div>
                      <div class="form-group">
                          <div class="col-sm-8 col-sm-offset-2">
                              <fieldset class="fieldset">
                                  <legend>Add Currency</legend>
                                  <div class="form-group">
                                      <div class="col-sm-offset-1">
                                          <div class="form-group" style="margin-bottom:0px;">
                                              <div class="col-sm-12">
                                                  <label>
                                                      <h5>
                                                          Please add other currencies manually after choosing currency usage.
                                                      </h5>
                                                  </label>
                                              </div>
                                          </div>
                                          <div class="form-group">
                                              <div class="col-sm-4">
                                                  <div class="radio i-checks">
                                                      <div class="checkbox i-checks">
                                                          <label>
                                                              <input type="radio" name="curr_type" value="{{ CURR_PERSONAL_USAGE }}" {{ (empty(old('curr_type')) || old('curr_type') == CURR_PERSONAL_USAGE) ? 'checked' : '' }} />
                                                              <i></i> Personal
                                                          </label>
                                                      </div>
                                                      <div class="checkbox i-checks">
                                                          <label>
                                                              <input type="radio" name="curr_type" value="{{ CURR_BUSINESS_USAGE }}" {{ old('curr_type') == CURR_BUSINESS_USAGE ? 'checked' : '' }} />
                                                              <i></i> Business
                                                          </label>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                          <div id="business" style="display: block;">
                                              <div class="form-group" style="margin-bottom:0px;">
                                                  <div class="col-sm-12">
                                                      <label>
                                                          <h5>
                                                              Please enter some information below.<br>
                                                          </h5>
                                                          <h5>
                                                              This will be sent to not only to us but also some financial experts via e-mail to ensure transparency.
                                                          </h5>
                                                      </label>
                                                  </div>
                                              </div>
                                              <span class="text-danger">{{ trans($errors->first('name')) }}</span>
                                              <div class="form-group">
                                                  <div class="col-sm-8">
                                                      <i class="fa fa-user icon-muted icon-embeded"></i>
                                                      <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Your Name" />
                                                  </div>
                                              </div>
                                              <span class="text-danger">{{ trans($errors->first('subject')) }}</span>
                                              <div class="form-group">
                                                  <div class="col-sm-8">
                                                      <i class="fa fa-tag icon-muted icon-embeded"></i>
                                                      <input type="text" class="form-control" name="subject" value="{{ old('subject') }}" placeholder="Subject" />
                                                  </div>
                                              </div>
                                              <span class="text-danger">{{ trans($errors->first('comment')) }}</span>
                                              <div class="form-group">
                                                  <div class="col-sm-8">
                                                      <i class="fa fa-comment icon-muted icon-embeded"></i>
                                                      <textarea class="form-control" name="comment" style="resize: none;" rows="6" placeholder="Message">{{ old('comment') }}</textarea>
                                                  </div>
                                              </div>
                                              <div class="form-group">
                                                  <div class="col-sm-8">
                                                      <div id="proRangeSlider">
                                                          <div class="col-sm-2">
                                                              <h5>
                                                                  Approvals<br>
                                                              </h5>
                                                          </div>
                                                          <div class="col-sm-9">
                                                              <input type="range" name="count" min="1" max="30" defaultValue="1" class="range orange" id="rangeSlider1" value="1" oninput="proRangeSlider(this.id, 'output1', ' people')">
                                                              <output id="output1">1 people</output>
                                                          </div>
                                                          <div class="col-sm-2">
                                                              <h5>
                                                                  Period<br>
                                                              </h5>
                                                          </div>
                                                          <div class="col-sm-9">
                                                              <input type="range" name="period" value="2" min="2" max="10"  defaultValue="2" id="rangeSlider2" class="blue range" oninput="proRangeSlider(this.id, 'output2', ' days')">
                                                              <output id="output2">2 days</output>
                                                          </div>
                                                          <div class="col-sm-2">
                                                              <h5>
                                                                  Approvals Ratio<br>
                                                              </h5>
                                                          </div>
                                                          <div class="col-sm-9">
                                                              <input type="range" name="ratio" value="10" min="10" max="100" defaultValue="10" id="rangeSlider3" class="red range" oninput="proRangeSlider(this.id, 'output3', ' %')">
                                                              <output id="output3">10 %</output>
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                          <span class="text-danger">{{ trans($errors->first('new_curr')) }}</span>
                                          <div class="form-group">
                                              <div class="col-sm-8">
                                                  <i class="fa fa-yen icon-muted icon-embeded"></i>
                                                  <input type="text" class="form-control" name="new_curr" value="{{ old('new_curr') }}" placeholder="Currency Code (e.g&nbsp;&nbsp; JPY)" />
                                              </div>
                                          </div>
                                          <div class="line line-dashed b-b line-lg pull-in"></div>
                                          <div class="form-group">
                                              <div class="col-sm-3">
                                                  <button class="btn btn-primary">Issue</button>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </fieldset>
                          </div>
                      </div>
                  </form>
              </div>
          </div>
      </div>
      <div class="panel-body" id="trust_board" style="display: {{ $page=='trust' ? 'block' : 'none' }}">
          <div class="tab-pane wrapper-lg {{ $page=='trust' ? 'active' : '' }}" id="trust">
              @if ($page == 'trust' && count($errors) > 0)
                  <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                  </div>
              @elseif ($page == 'trust' && !empty($gateway_settings_success  = Session::pull('gateway_settings_success', '')))
                  <div class="alert alert-success">
                    <ul>
                        <li>{{ $gateway_settings_success }}</li>
                    </ul>
                  </div>
              @endif
              <div class="col-sm-10">
                  <form class="form-horizontal" method="post" action="{{ url('/money/gateway') }}">
                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                      <input type="hidden" name="page" value="trust">
                      <div style="height: 30px;font-size: 20px;">Trust Settings</div>
                      <div class="form-group" style="padding-bottom: 0px;margin-bottom:0px;">
                          <label class="col-sm-4 col-sm-offset-3" style="display:inline-block">Please choose one to trust currency from.</label>
                      </div>
                      <div class="form-group">
                          <div class="col-sm-6 col-sm-offset-3">
                              <div class="radio i-checks">
                                  <div class="checkbox i-checks">
                                      <label>
                                          <input type="radio" name="trust_way" value="native" checked />
                                          <i></i> Native Gateways
                                      </label>
                                      <label>&nbsp;</label>
                                      <label>
                                          <input type="radio" name="trust_way" value="direct"/>
                                          <i></i> Baysia Address
                                      </label>
                                  </div>
                                  &nbsp;&nbsp;&nbsp;&nbsp;
                              </div>
                          </div>
                      </div>
                      <div class="form-group" style="padding-bottom: 0px;margin-bottom:0px;">
                          <label class="col-sm-4 col-sm-offset-3">Please select currency and gateway you want to trust.</label>
                      </div>
                      <div class="form-group">
                          <div class="col-sm-offset-3">
                              <div class="col-sm-3" style="padding: 5px 10px;">
                                  <select class="form-control" name="trust_curr">
                                      <option disabled>Please select one currency...</option>
                                      @foreach($all_currs as $currency)
                                          <option value="{{ $currency }}" {{ $currency == old('trust_curr') ? 'selected' : '' }}>{{ $currency }}</option>
                                      @endforeach
                                      <option id="add">+Edit</option>
                                  </select>
                              </div>
                              <div class="col-sm-3" style="padding: 5px 10px;">
                                  <select class="form-control" name="gateway_name">
                                      <option disabled>Please select one gateway...</option>
                                  </select>
                                  <input class="form-control" name="gateway_address" style="display:none;" placeholder="Baysia Address"/>
                              </div>
                              <div class="col-sm-3" style="padding: 5px 10px;">
                                  <button class="btn btn-primary">Trust</button>
                              </div>
                          </div>
                      </div>
                      <div class="line line-dashed b-b line-lg pull-in"></div>
                      <div style="height: 30px;font-size: 20px;">Trusted Given</div>
                      <div class="form-group">
                          <label class="col-sm-3 control-label"></label>
                          <div class="col-sm-7" id="trust_given">
                            <img src="{{ asset('images/waiting.gif') }}">
                          </div>
                      </div>
                      <div class="line line-dashed b-b line-lg pull-in"></div>
                      <div style="height: 30px;font-size: 20px;">Trust Received</div>
                      <div class="form-group">
                          <label class="col-sm-3 control-label"></label>
                          <div class="col-sm-7" id="trust_received">
                              <img src="{{ asset('images/waiting.gif') }}">
                          </div>
                      </div>
                  </form>
              </div>
          </div>
      </div>
  </section>
</section>
@stop

@section('footer')
<script language="javascript" src="{{asset('js/stellar/stellar-lib.js')}}"></script>
<script language="javascript">
    $('ul.nav-main li').removeClass('active');
    $('ul.nav-main > li:eq(5)').addClass('active');
    setPreviousURL();
    $('ul.dropdown-menu > li > a').click(function(){
        $('button.dropdown-toggle').html($(this).html()+' <span class="caret"></span>');
        $('#currency').val($(this).html());
    });
    (function() {
        {{--var gateways = JSON.parse('{{ json_encode($gateways) }}'.replace(/&quot;/ig,'"'));--}}
        var gateways = JSON.parse('{!! json_encode($gateways) !!}');
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
        remote.connect(function () {
            remote.on('transaction_all', transactionListener);
        });
        //account: "{{-- $wallet_address --}}",
        var getTrustLines = function() {
            var request = remote.requestAccountLines('{{ $wallet_address }}');
            request.callback(function (err, res) {
                console.log(err);
                if (!err) {
                    console.log(res);
                    var trust_received_html = '';
                    var trust_given_html = '';
                    for (var i = 0; i < res.lines.length; i++) {
                        var gateway_name;
                        for (var j = 0; j < gateways.length; j++) {
                            if (res.lines[i].account == gateways[j].owner_address) {
                                gateway_name = gateways[j].name;
                                break;
                            } else if (j == gateways.length - 1) {
                                gateway_name = '';
                            }
                        }
                        if (res.lines[i].balance < 0 || res.lines[i].balance == 0 && res.lines[i].limit_peer > 0) {
                            trust_given_html += '<tr><td>' + res.lines[i].currency + '</td><td>' + number_format(res.lines[i].balance, 6) + '</td><td>' + res.lines[i].limit_peer + '</td><td>' + res.lines[i].account + '</td><td>';
                        } else if (res.lines[i].balance > 0 || res.lines[i].balance == 0 && res.lines[i].limit > 0) {
                            trust_received_html += '<tr><td>' + res.lines[i].currency + '</td><td>' + number_format(res.lines[i].balance, 6) + '</td><td>' + res.lines[i].limit + '</td><td>' + res.lines[i].account + '</td><td>' + gateway_name + '</td></tr>';
                        }


                    }
                    if (!!trust_received_html) {
                        trust_received_html = '<table width="100%"><tr><th>Currency</th><th>Balance</th><th>Limit Amount</th><th>From Account</th><th>Gateway Name</th></tr>' + trust_received_html + '</table>';
                        $('#trust_received').html(trust_received_html);
                    } else {
                        $('#trust_received').html('<b>No trust lines received</b>');
                    }
                    if (!!trust_given_html) {
                        trust_given_html = '<table width="100%"><tr><th>Currency</th><th>Balance</th><th>Limit Amount</th><th>From Account</th></tr>' + trust_given_html + '</table>';
                        $('#trust_given').html(trust_given_html);
                    } else {
                        $('#trust_given').html('<b>No trust lines given</b>');
                    }
                }
            });
            request.request();
        }
        function transactionListener (transaction_data) {
            //            var array = $.map(transaction_data, function(value, index) {
            //            return [value];
            //            });
            //            var array = $.map(transaction_data, function(arr) {
            //            return arr;
            //            });
            console.log(transaction_data);
            getTrustLines();
        }
        $('[name=trust_way]').change(function() {
            if ($(this).val() == 'native') {
                $('[name=gateway_address]').css('display', 'none');
                $('[name=gateway_name]').css('display', 'block');
                $('[name=gateway_name]').closest('div').removeClass().addClass('col-sm-3');
            } else if ($(this).val() == 'direct') {
                $('[name=gateway_address]').css('display', 'block');
                $('[name=gateway_address]').closest('div').removeClass().addClass('col-sm-5');
                $('[name=gateway_name]').css('display', 'none');
            }
        });
        $('[name=curr_type]').change(function(){
            if ($(this).val() == '{{ CURR_PERSONAL_USAGE }}') {
                $('#business').css('display', 'none');
            } else {
                $('#business').css('display', 'block');
            }
        });
        $('input[name=trust_way][type=radio]:checked').trigger('change');
        $('[name=curr_type]:checked').trigger('change');
        $('[name=curr_type]').unbind('change');
        $('[name=curr_type]').change(function(){
            if ($(this).val() == '{{ CURR_PERSONAL_USAGE }}') {
                $('#business').fadeOut('slow');
            } else {
                $('#business').fadeIn('slow');
            }
            $('.alert').fadeOut('slow');
            $('[class=text-danger]').fadeOut('slow');
        });
        getTrustLines();
        getGatewayList($('[name=trust_curr]').val());
        //getGatewayList3($('[name=view_curr]').val());
        checkUseOfCurr();
    })();
    $('[name=trust_curr]').bind('change', function(e) {
        if ($(this).val() == '+Edit') {
            $('#gateway_board').css('display', 'block');
            $('#trust_board').css('display', 'none');
            if (!$('#gateway_link').hasClass('active') && !$('#curr_link').hasClass('active')) {
                $('#curr_link').addClass('active');
                $('#currency').addClass('active');
            }
            $(this).val('{{ $all_currs[0] or '' }}');
        } else {
            getGatewayList($(this).val());
        }
    });
    $('[name=view_curr]').bind('change', function() {
        getGatewayList3($(this).val());
    });
    $('[name=curr2use]').change(function() {
        if ($(this).prop('checked') == false) {
            removeUseOfCurr($(this).val());
        } else {
            setUseOfCurr($(this).val());
        }
    });
    function getGatewayList(curr) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'GET',
            url: "{{ url('/money/gateway-list/') }}" + "/" + curr,
            success: function(msg) {
                var gateways = JSON.parse(msg);
                console.log(gateways);
                var trust_gateways;
                for (var i = 0; i < gateways.length; i++) {
                    if (gateways[i] == null) continue;
                    trust_gateways += '<option address="' + gateways[i].owner_address + '" value="' + gateways[i].name + '">' + gateways[i].name + '</option>';
                }
                if (!!trust_gateways) {
                    trust_gateways = '<option disabled>' + 'Please select one gateway...' + '</option>' + trust_gateways;
                    $('[name=gateway_name]').html(trust_gateways);
                } else {
                    $('[name=gateway_name]').html('<option value="null">&nbsp;</option>');
                }
            },
            error: function(err) {
//                console.log(err);
            }
        });
    }
    function getGatewayList3(curr) {
        $('#auth_gateways').html('<img src="{{ asset('images/waiting.gif') }}" />');
        $('#unauth_gateways').html('<img src="{{ asset('images/waiting.gif') }}" />');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'GET',
            url: "{{ url('/money/gateway-list3/') }}" + "/" + curr,
            success: function(msg) {
                console.log(msg);
                var gateways = JSON.parse(msg);
                var auth_gateways = "";
                var unauth_gateways = "";
                gateways.forEach(function(d) {
                    if (d == null) return;
                    var html_code = '<div class="checkbox i-checks" style="display:inline;"><label style="margin-bottom: 10px;"><input type="checkbox" name="gateway2use" value="' + d.owner_address + '"' + (d.trust == true ? ' checked' : '') + '/><i></i>' + d.name + '<br>' + d.owner_address + '</label></div>';
                    if (d.authorized == 1) {
                        auth_gateways += html_code;
                    } else {
                        unauth_gateways += html_code;
                    }
                });
                /*for (var i = 0; i < gateways.length; i++) {
                    if (gateways[i] == null) continue;
                    curr_gateways += '<div class="checkbox i-checks" style="display:inline;"><label style="margin-bottom: 10px;"><input type="checkbox"/><i></i>' +  gateways[i].name + '<br>' + gateways[i].owner_address + '</label></div>';
                }*/
                if (!!auth_gateways) {
                    $('#auth_gateways').html(auth_gateways);
                } else {
                    $('#auth_gateways').html('');
                }
                if (!!unauth_gateways) {
                    $('#unauth_gateways').html(unauth_gateways);
                } else {
                    $('#unauth_gateways').html('');
                }

                $('[name=gateway2use]').change(function(e) {
                    console.log(e);
                    var remove = 0;
                    var obj = $(this);
                    if (obj.prop('checked') == false) {
                        remove = 1;
                    }
                    $.post("{{ url('money/trust') }}", "remove=" + remove + "&curr=" + $('[name=view_curr]').val() + "&gateway=" + $(this).val(), function(msg) {
                        gatewayAlert(msg.result);
                        if (msg.result == 'Success') {

                        } else {
                            obj.prop('checked', !obj.prop('checked'));
                        }
                    }, "json");
                });
            },
            error: function(err) {
                console.log(err);
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
    function back2Trust() {
        $('#gateway_board').css('display', 'none');
        $('#trust_board').css('display', 'block');
    }
    function setUseOfCurr(curr) {
        var currAvailListArr = [],
            currAvailList = {},
            currList = [],
            jsonPair = {};
        if (!localStorage) {
            return false;
        }
        currAvailListArr = JSON.parse(localStorage.getItem('curr_avail_list'));
        if (currAvailListArr == null)
            currAvailListArr = [];
        currAvailList = currAvailListArr.filter(function(value, index, arr) {
            if (JSON.parse(value).account == '{{ $wallet_address }}') {
                arr.splice(index, 1);
                return true;
            }
        });
        currAvailList = currAvailList.length == 0 ? null : JSON.parse(currAvailList);
        currList = currAvailList ? currAvailList.curr_list : null;
        currList = currList == null ? [] : currList.split(',');
        if (currList.indexOf(curr) != -1)
            return true;
        else if (currList.length == 0) {
            currList = [curr];
        } else
            currList.push(curr);
        currList = currList.sort();
        jsonPair = {account: '{{ $wallet_address }}', curr_list: currList.length == 0 ? null : currList.toString()};
        currAvailListArr.push(JSON.stringify(jsonPair));
        localStorage.setItem('curr_avail_list', JSON.stringify(currAvailListArr));
        checkUseOfCurr();
        currAlert();
        console.log('set', localStorage.getItem('curr_avail_list'));
    }
    function removeUseOfCurr(curr) {
        var currAvailListArr = [],
            currAvailList = {},
            currList = [],
            jsonPair = {};
        if (!localStorage) {
            return false;
        }
        currAvailListArr = JSON.parse(localStorage.getItem('curr_avail_list'));
        if (currAvailListArr == null)
            currAvailListArr = [];
        currAvailList = currAvailListArr.filter(function(value, index, arr) {
            if (JSON.parse(value).account == '{{ $wallet_address }}') {
                arr.splice(index, 1);
                return true;
            }
        });
        currAvailList = currAvailList.length == 0 ? null : JSON.parse(currAvailList);
        currList = currAvailList ? currAvailList.curr_list : null;
        currList = currList == null ? [] : currList.split(',');

        if (currList.indexOf(curr) != -1) {
            currList.splice(currList.indexOf(curr), 1);
        } else
            return true;
        jsonPair = {account: '{{ $wallet_address }}', curr_list: currList.length == 0 ? null : currList.toString()};
        currAvailListArr.push(JSON.stringify(jsonPair));
        localStorage.setItem('curr_avail_list', JSON.stringify(currAvailListArr));
        checkUseOfCurr();
        currAlert();
        console.log('remove', localStorage.getItem('curr_avail_list'));
    }
    function checkUseOfCurr(){
        var currAvailListArr = {},
            currAvailList  = {},
            currList = [],
            currList_html = "";
        currAvailListArr = JSON.parse(localStorage.getItem('curr_avail_list'));
        if (currAvailListArr == null)
            currAvailListArr = [];
        currAvailList = currAvailListArr.filter(function(value, index, arr) {
            if (JSON.parse(value).account == '{{ $wallet_address }}') {
                arr.splice(index, 1);
                return true;
            }
        });
        console.log(currAvailList);
        currAvailList = currAvailList.length == 0 ? null : JSON.parse(currAvailList);
        currList = currAvailList ? currAvailList.curr_list : null;
        currList_html = "";
        currList = currList == null ? [] : currList.split(',');
        $('[name=curr2use]').each(function() {
            var obj = $(this);
            var curr = $(this).val();
            currList.some(function(d, index) {
//                console.log(d);
                if (d == curr) {
                    obj.prop('checked', true);
                    return true;
                }
            });
        });
        currList.forEach(function(d) {
            if ("" == d) return;
            currList_html  += '<option value="' + d + '">' + d + '</option>';
        });
        $('[name=view_curr]').html(currList_html);
        getGatewayList3($('[name=view_curr]').val());
    }
    function currAlert() {
        $('#curr_alert').fadeIn('fast', function() {
            setTimeout(function() {
                $('#curr_alert').fadeOut('fast', 'swing');
            }, 2000);
        });
    }
    function gatewayAlert(type) {
        if (type == 'Success') {
            $('#gateway_alert').removeClass('alert-danger');
            $('#gateway_alert').addClass('alert-success');
            $('#gateway_alert').html('Trust settings saved successfully.');
        } else if (type == 'Fail') {
            $('#gateway_alert').removeClass('alert-success');
            $('#gateway_alert').addClass('alert-danger');
            $('#gateway_alert').html('Trust settings failed to save.');
        }
        $('#gateway_alert').fadeIn('fast', function() {
            setTimeout(function() {
                $('#gateway_alert').fadeOut('fast', 'swing');
            }, 2000);
        });
    }
</script>
@stop