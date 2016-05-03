<div class="panel-body">
    <div style="float: right;line-height: 5px;" id="buy_waiting">&nbsp;</div>
    <form class="bs-example form-horizontal" id="buy_form" method="post" action="{{ url('/money/trade') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <input type="hidden" name="type" value="buy" />
        <div class="form-group" style="text-align:center;">
            <div class="col-sm-12 m-b-s col-lg-12">
                <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-b-2x btn-default" id="b_mod_market">
                        <input type="radio" name="options"> {{ trans('stellar.offer_create.market_order') }}
                    </label>
                    <label class="btn btn-b-2x btn-default active" id="b_mod_limit">
                        <input type="radio" name="options"> {{ trans('stellar.offer_create.limit_order') }}
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label">{{ trans('money.trade.volume') }}</label>
            <div class="input-group col-lg-9">
                <input id="buyAmount" name="buyAmount" type="text" class="form-control" maxlength="15" />
                <span class="input-group-btn">
                    <span name="primaryCurrency" class="btn btn-default2">{{ empty(old('primaryCurrency')) ? COIN_BSC : old('primaryCurrency') }}</span>
                    <input type="hidden" name="primaryCurrency" value="{{ empty(old('primaryCurrency')) ? COIN_BSC : old('primaryCurrency') }}"/>
                    <input type="hidden" name="p_issuer" value="{{ empty(old('p_issuer')) ? '' : old('p_issuer') }}" />
                </span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label">{{ trans('money.trade.price') }}</label>
            <div class="input-group col-lg-9">
                <input id="buyPrice" name="buyPrice" type="text" class="form-control">
                <span class="input-group-btn">
                    <span name="2ndCurrency" class="btn btn-default2">{{ empty(old('2ndCurrency')) ? COIN_JPY : old('2ndCurrency') }}</span>
                </span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label">{{ trans('money.trade.total') }}</label>
            <div class="input-group col-lg-9">
                <input name="buyTotal" type="text" class="form-control" disabled />
                <input id="buyTotal" name="buyTotal" type="hidden" />
                <span class="input-group-btn">
                    <span name="2ndCurrency" class="btn btn-default2">{{ empty(old('2ndCurrency')) ? COIN_JPY : old('2ndCurrency') }}</span>
                    <input type="hidden" name="2ndCurrency" value="{{ empty(old('2ndCurrency')) ? COIN_JPY : old('2ndCurrency') }}"/>
                    <input type="hidden" name="s_issuer" value="{{ empty(old('s_issuer')) ? '' : old('s_issuer') }}"/>
                </span>
            </div>
        </div>
        <div class="form-group" style="text-align:center;">
            <div class="col-lg-12">
                <a href="#modal-form" class="btn btn-success" data-toggle="modal" id="buy_submit">{{ trans('money.trade.buy') }}</a>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    var index = 0;
    $('[name="select_pri_currency"]').click(function(){
        $('[name=primaryCurrency]').html($(this).html());
    });
    $('[name="select_2nd_currency"]').click(function(){
        $('[name="2ndCurrency"]').html($(this).html());
    });
</script>