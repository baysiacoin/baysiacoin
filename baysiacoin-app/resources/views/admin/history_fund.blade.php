
@extends('admin.layouts.master')

@section('header')
<style type="text/css">
th,td{
	text-align : center;
}
</style>
@stop

@section('content')
	<h2>{{ trans('admin.history.fund.title') }}</h2>
    <div class="col-sm-7">
        <form class="form-inline" style="float: left" id="frm_condition" method="POST" action="{{url('/manage/root/history/fund')}}">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <div class="form-group">
                <label>{{trans('admin.edit.wallet.title')}}</label> :
                <select class="form-control" id="currency" name='search_currency'>
                    <option value="0">{{trans('admin.history.all')}}</option>
                    @foreach ($currencies as $curr)
                        <option value="{{ $curr }}">{{ $curr }}</option>
                    @endforeach
                </select>
                @if ($login_user->hasRole(ROLE_ADMIN))
                    <label>{{trans('admin.edit.wallet.gateway')}}</label> :
                    <select class="form-control" id="issuer" name='search_issuer' style="width: 180px;">
                        <option value="0">{{trans('admin.history.all')}}</option>
                    </select>
                @endif
                <label>{{trans('admin.history.status')}}</label> :
                <select class="form-control" id="paid" name='search_paid'>
                    <option value="0">{{trans('admin.history.all')}}</option>
                    <option value="{{DEPOSIT_REQUESTED}}">{{trans('admin.history.fund.pendding')}}</option>
                    <option value="{{DEPOSIT_CONFIRMED}}">{{trans('admin.history.fund.confirmed')}}</option>
                    <option value="{{DEPOSIT_FINISHED}}">{{trans('admin.history.fund.completed')}}</option>
                    <option value="{{DEPOSIT_FAILED}}">{{trans('admin.history.fund.failed')}}</option>
                </select>
                <label>{{trans('admin.history.username')}}</label> :
                <input type="text" class="form-control" id="txt_username" name="search_username" value="{{$search_username}}" />
                &nbsp;
                <button type="submit" class="btn btn-primary" name="do_filter" value="ok">{{trans('admin.history.search')}}</button>
                <button type="button" class="btn btn-primary" name="clear" id="btn_clear">{{trans('admin.history.clear')}}</button>
            </div>
        </form>
    </div>
    <div class="col-sm-4">
        <div class="alert alert-success" style="margin-bottom: 0px; padding: 7px;display: none;" id="result_box">
            <p id="result_text" style="margin-left: 30px;">Successfully changed.</p>
        </div>
    </div>
    <div class="clear"></div>
    <hr class="hr-margin-line">
    <hr class="hr-margin">
    <table class="table table-striped" id="list-fees">
        <thead>
            <tr>
                <th>No</th>
                <th>{{trans('admin.history.fund.transaction_id')}}</th>
                <th>{{trans('admin.history.username')}}</th>
                <th>{{trans('admin.history.amount')}}</th>
                <th>{{trans('admin.history.currency')}}</th>
                @if ($login_user->hasRole(ROLE_ADMIN))
                    <th>{{trans('admin.history.issuer_address')}}</th>
                @endif
                <th>{{trans('admin.history.tag')}}</th>
                <th>{{trans('admin.history.external_address')}}</th>
                <th>{{trans('admin.history.status')}}</th>
                <th>{{trans('admin.history.created_at')}}</th>
                <th>{{trans('admin.history.fund.process')}}</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = PER_PAGE * ($deposits->currentPage() - 1) ?>
            @foreach($deposits as $deposit)
            <tr>
                <td>{{ ++$i }}</td>
                <td>
                    <span style="display: block">{{$deposit->transaction_id}}</span>
                    ({{$deposit->name}})
                </td>
                <td style="text-align: center;"><a href="{{url('/manage/root/user/edit')}}/{{$deposit->getUser()['id']}}">{{$deposit->getUser()['username']}}</a><br>&nbsp;({{$deposit->getUser()['firstname']}} {{$deposit->getUser()['lastname']}})</td>
                <td style="text-align: right;">{{$deposit->amount}}</td>
                <td>
                    {{ $deposit->currency }}
                </td>
                @if ($login_user->hasRole(ROLE_ADMIN))
                    <td>
                        {{ $deposit->issuer }}
                    </td>
                @endif
                <td>
                    {{  $deposit->tag }}
                </td>
                <td>
                    {{  $deposit->external_address }}
                </td>
                <td>
                    @if($deposit->paid== DEPOSIT_FINISHED)
                        <b style="color:green">{{trans('admin.history.fund.completed')}}</b>
                    @elseif($deposit->paid == DEPOSIT_CONFIRMED)
                        <b style="color:red">{{trans('admin.history.fund.confirmed')}}</b><br>
                        {{--<!--a href="{{url('/manage/root/history/fund_complete')}}/{{$deposit->id}}" class="edit_page"><span>{{trans('admin.history.fund.complete')}}</span></a-->--}}
                    @elseif($deposit->paid == DEPOSIT_REQUESTED)
                        {{trans('admin.history.fund.pendding')}}
                    @else
                        {{trans('admin.history.fund.failed')}}
                    @endif
                </td>
                <td>{{$deposit->created_at}}</td>
                <td>
                    @if($deposit->paid != DEPOSIT_FINISHED)
                        <div class="btn btn-primary edit">
                            <span>{{ trans('admin.history.fund.edit') }}</span>
                        </div>
                        <div class="btn btn-primary complete" send="/{{$deposit->id}}" {{ $deposit->paid != DEPOSIT_CONFIRMED ? '' : 'disabled' }}>
                            <span>{{trans('admin.history.fund.update')}}</span>
                        </div>
                        <button class="btn btn-primary complete {{ $deposit->paid == DEPOSIT_CONFIRMED ? 'confirmed' : '' }}" send="/{{$deposit->id}}?send=true" {{ $deposit->paid != DEPOSIT_CONFIRMED ? 'disabled' : '' }} onclick="notyConfirm(this);">
                            <span>{{trans('admin.history.fund.send_update')}}</span>
                        </button>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot></tfoot>
    </table>
	<div class='row right' id='page_render'><?php echo str_replace('fund/?', 'fund?', $deposits->render()) ?></div>
@stop

@section('footer')
<script src="{{ asset('js/datatables/datatable.js') }}"></script>
<script src="{{ asset('js/datatables/datatables.js') }}"></script>
<script src="{{ asset('js/datatables/datatables.bootstrap.js') }}"></script>
<script type='text/javascript'>
    $(document).ready(function() {
        $("#btn_clear").click(function(){
            $("select").val('0');
            $("#txt_username").val("");
        });
        $('div.complete').click(function(){
            $('form#frm_condition').attr('action', "{{url('/manage/root/history/fund_complete')}}" + $(this).attr('send'));
            $('form#frm_condition').submit();
        });
    });
    function notyConfirm(obj){
        $('button.complete.confirmed').prop('disabled', true);
        noty({
            text: '{{ trans('admin.history.fund.ask_confirm') }}',
            layout: 'topRight',
            buttons: [
                {addClass: 'btn btn-success btn-clean', text: 'Ok', onClick: function($noty) {
                        $noty.close();
                        $('form#frm_condition').attr('action', "{{url('/manage/root/history/fund_complete')}}" + $(obj).attr('send'));
                        $('form#frm_condition').submit();
                    }
                },
                {addClass: 'btn btn-danger btn-clean', text: 'Cancel', onClick: function($noty) {
                        $noty.close();
                        $('button.complete.confirmed').prop('disabled', false);
    //                    noty({text: 'You clicked "Cancel" button', layout: 'topRight', type: 'error'});
                    }
                }
            ]
        })
    }
    $("#currency option[value='{{$search_currency}}']").attr('selected', 'selected');
    $("#paid option[value='{{$search_paid}}']").attr('selected', 'selected');
    @if ($login_user->hasRole(ROLE_ADMIN))
        $('[name=search_currency]').bind('change', function(e) {
            var value = $(this).val();
    //        alert(value);
            //$('#currency').html(value);
            $('[name=search_issuer]').val('');
            $('[name=search_issuer]').prop('disabled', true);

            if (value == '0') {
                $('[name=search_issuer]').prop('disabled', false);
                $('[name=search_issuer]').html('<option value="0">{{trans('admin.history.all')}}</option>');
                return;
            } /*else if (value == 'STR' || value == 'XRP') {
                $('#tag').css('display', 'block');
                $('#external_address').css('display', 'block');
            } else {
                $('#tag').css('display', 'none');
                $('#external_address').css('display', 'none');
            }*/
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ url('/money/gateway-list2/') }}" + "/" + value,
                success: function($msg) {
                    var gateways = JSON.parse($msg);
                    console.log(gateways);
                    var gateways_html = "";
                    for (var i = 0; i < gateways.length; i++) {
                        if (gateways[i] == null) continue;
                        gateways_html += '<option value="' + gateways[i].owner_address + '"><a name="issuer_item" class="cursor-pt" address="' + gateways[i].owner_address + '">' + gateways[i].name + '</a></option>';
                    }
                    gateways_html = '<option value="0">{{trans('admin.history.all')}}</option>' + gateways_html;
                    console.log(!gateways_html);
                    if (!!gateways_html) {
                        $('[name=search_issuer]').html(gateways_html);
                        $('[name=search_issuer]').prop('disabled', false);
                        $("#issuer option[value='{{$search_issuer}}']").attr('selected', 'selected');
                    } else {
                        $('[name=search_issuer]').val('');
                        $('[name=issuer_item]').html('');
                    }
                    /*$('[name=issuer_item]').click(function() {
                        $('[name=issuer]').val($(this).html());
                    });
                    $('[name=issuer_item]:first').trigger('click');*/
                }
            });
        });
        $('[name=search_currency]').trigger('change');
    @endif
</script>
@if ($login_user->hasRole(ROLE_ADMIN))
<script type="text/javascript">
    var nEditing = null;
    var table = $('#list-fees');
    var oTable = table.dataTable({
        "paging":   false,
        "ordering": false,
        "info":     false,
        "searching": false
    });

    function restoreRow(oTable, nRow) {
        var aData = oTable.fnGetData(nRow);
        var jqTds = $('>td', nRow);

        for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
            oTable.fnUpdate(aData[i], nRow, i, false);
        }
        oTable.fnDraw();
    }
    function editRow(oTable, nRow) {
        var aData = oTable.fnGetData(nRow);
        var jqTds = $('>td', nRow);
//        jqTds[0].innerHTML = '<input type="text" class="form-control input-small" value="' + aData[0] + '">';
//        jqTds[1].innerHTML = '<input type="text" class="form-control input-small" value="' + aData[1] + '">';
//        jqTds[2].innerHTML = '<input type="text" class="form-control input-small" value="' + aData[2] + '">';
        jqTds[3].innerHTML = '<input type="text" class="form-control input-small" value="' + aData[3] + '">';
        jqTds[4].innerHTML = '<input type="text" class="form-control input-small" value="' + aData[4] + '">';
        jqTds[5].innerHTML = '<input type="text" class="form-control input-small" value="' + aData[5] + '">';
        jqTds[6].innerHTML = '<input type="text" class="form-control input-small" value="' + aData[6] + '">';
        jqTds[7].innerHTML = '<input type="text" class="form-control input-small" value="' + aData[7] + '">';
    }
    function saveRow(oTable, nRow) {
        var jqTds = $('>td', nRow);
        console.log($(jqTds));
        var jqInputs = $('input', nRow);
        //using ajax to sync with backend
        var data = {
            tx_id: $(jqTds[1]).children('span').html(),//transaction id
            amount: jqInputs[0].value,
            currency: jqInputs[1].value,
            issuer: jqInputs[2].value,
            tag: jqInputs[3].value,
            external: jqInputs[4].value
        }
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: '{{ url('manage/root/history/update/fund') }}',
            data: data,
            dataType: 'JSON',
            success: function(msg) {
                if (msg.result == '{{ SUCCESS }}') {
                    oTable.fnUpdate(jqInputs[0].value, nRow, 3, false);
                    oTable.fnUpdate(jqInputs[1].value, nRow, 4, false);
                    oTable.fnUpdate(jqInputs[2].value, nRow, 5, false);
                    oTable.fnUpdate(jqInputs[3].value, nRow, 6, false);
                    oTable.fnUpdate(jqInputs[4].value, nRow, 7, false);
//                    oTable.fnUpdate('<a class="edit" href="">Edit</a>', nRow, 4, false);
//                    oTable.fnUpdate('<a class="delete" href="">Delete</a>', nRow, 5, false);
                    oTable.fnDraw();
                    if ($('#result_box').hasClass('alert-danger')) {
                        $('#result_box').removeClass('alert-danger');
                    }
                    $('#result_box').addClass('alert-success');
                    $('#result_text').html('{{ trans('admin.history.fund.success') }}');
                    $('#result_box').fadeIn(1000, function(){
                        setTimeout(function(){
                            $('#result_box').fadeOut('slow', 'swing');
                        }, 10000);
                    });
                    scrollToElement('result_box');
                } else {
                    restoreRow(oTable, nRow);
                    if ($('#result_box').hasClass('alert-success')) {
                        $('#result_box').removeClass('alert-success');
                    }
                    $('#result_box').addClass('alert-danger');
                    $('#result_text').html('{{ trans('admin.history.fund.fail') }}');
                    $('#result_box').fadeIn(1000, function(){
                        setTimeout(function(){
                            $('#result_box').fadeOut('slow', 'swing');
                        }, 10000);
                    });
                    scrollToElement('result_box');
                }
            },
            error: function(err) {
                restoreRow(oTable, nRow);
                if ($('#result_box').hasClass('alert-success')) {
                    $('#result_box').removeClass('alert-success');
                }
                $('#result_box').addClass('alert-danger');
                $('#result_text').html('{{ trans('admin.history.fund.fail') }}');
                $('#result_box').fadeIn(1000, function(){
                    setTimeout(function(){
                        $('#result_box').fadeOut('slow', 'swing');
                    }, 10000);
                });
                scrollToElement('result_box');
            }
        });
    }
    table.on('click', '.edit', function (e) {
        e.preventDefault();

        /* Get the row as a parent of the link that was clicked on */
        var nRow = $(this).parents('tr')[0];

        if (nEditing !== null && nEditing != nRow) {
            /* Currently editing - but not this row - restore the old before continuing to edit mode */
            restoreRow(oTable, nEditing);
            editRow(oTable, nRow);
            nEditing = nRow;
            this.innerHTML = '{{ trans('admin.history.fund.save') }}';
        } else if (nEditing == nRow && this.innerHTML == '{{ trans('admin.history.fund.save') }}') {
            /* Editing this row and want to save it */
            saveRow(oTable, nEditing);
            nEditing = null;
            this.innerHTML = '{{ trans('admin.history.fund.edit') }}';
        } else {
            /* No edit in progress - let's start one */
            editRow(oTable, nRow);
            nEditing = nRow;
            this.innerHTML = '{{ trans('admin.history.fund.save') }}';
        }
    });
</script>
@endif
@stop
