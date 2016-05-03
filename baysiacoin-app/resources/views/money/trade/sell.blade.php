<div class="panel-body">
    <div style="float: right;line-height: 5px;" id="sell_waiting">&nbsp;</div>
    <form class="bs-example form-horizontal" id="sell_form" method="post" action="{{ url('/money/trade') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <input type="hidden" name="type" value="sell" />
        <div class="form-group" style="text-align:center;">
            <div class="col-sm-12 m-b-s col-lg-12">
                <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-b-2x btn-default" id="s_mod_market">
                        <input type="radio" name="options"> {{ trans('stellar.offer_create.market_order') }}
                    </label>
                    <label class="btn btn-b-2x btn-default active" id="s_mod_limit">
                        <input type="radio" name="options"> {{ trans('stellar.offer_create.limit_order') }}
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label">{{ trans('money.trade.volume') }}</label>
            <div class="input-group col-lg-9">
                <input id="sellAmount" name="sellAmount" type="text" class="form-control" maxlength="15" />
                <span class="input-group-btn">
                    <span name="primaryCurrency" class="btn btn-default2">{{ empty(old('primaryCurrency')) ? COIN_BSC : old('primaryCurrency') }}</span>
                    <input type="hidden" name="primaryCurrency" value="{{ empty(old('primaryCurrency')) ? COIN_BSC : old('primaryCurrency') }}"/>
                    <input type="hidden" name="p_issuer" value="{{ empty(old('p_issuer')) ? '' : old('p_issuer') }}"/>
                </span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label">{{ trans('money.trade.price') }}</label>
            <div class="input-group col-lg-9">
                <input id="sellPrice" name="sellPrice" type="text" class="form-control">
                <span class="input-group-btn">
                    <span name="2ndCurrency" class="btn btn-default2">{{ empty(old('2ndCurrency')) ? COIN_JPY : old('2ndCurrency') }}</span>
                </span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label">{{ trans('money.trade.total') }}</label>
            <div class="input-group col-lg-9">
                <input name="sellTotal" type="text" class="form-control" disabled>
                <input id="sellTotal" name="sellTotal" type="hidden" />
                <span class="input-group-btn">
                    <span name="2ndCurrency" class="btn btn-default2">{{ empty(old('2ndCurrency')) ? COIN_JPY : old('2ndCurrency') }}</span>
                    <input type="hidden" name="2ndCurrency" value="{{ empty(old('2ndCurrency')) ? COIN_JPY : old('2ndCurrency') }}"/>
                    <input type="hidden" name="s_issuer" value="{{ empty(old('s_issuer')) ? '' : old('s_issuer') }}"/>
                </span>
            </div>
        </div>
        <div class="form-group" style="text-align:center;">
            <div class="col-lg-12">
                <a href="#modal-form" class="btn btn-success" data-toggle="modal" id="sell_submit">{{ trans('money.trade.sell') }}</a>
            </div>
        </div>
    </form>
</div>
