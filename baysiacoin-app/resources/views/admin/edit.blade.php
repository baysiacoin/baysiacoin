@extends('admin.layouts.master')

@section('header')
    <link rel="stylesheet" href="{{ asset('css/app.css') }} " type="text/css"/>
    <link rel="stylesheet" href="{{ asset('css/jquery/jquery.ui.all.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('css/flags.authy.css') }}" type="text/css" />
    <style type="text/css">
        .layout-main-right .layout-sidebar {
            border-right: 1px solid #ccc;
            box-shadow: -8px 0 15px -10px rgba(0, 0, 0, 0.2) inset;
            padding-left: 15px;
            padding-right: 30px;
        }

        .nav-layout-sidebar {
            border-top: 1px solid #e5e5e5;
            position: relative;
            z-index: 99;
        }

        .nav-layout-sidebar > li > a {
            border-bottom: 1px solid #e5e5e5;
            color: #666;
            outline: medium none;
            padding-bottom: 15px;
            padding-top: 15px;
        }

        .fa {
            display: inline-block;
            font-family: FontAwesome;
            font-style: normal;
            font-weight: normal;
            line-height: 1;
        }

        .nav-layout-sidebar > .active > a, .nav-layout-sidebar > .active > a:focus, .nav-layout-sidebar > .active > a:hover {
            background-color: #fff;
            color: #d74b4b;
            font-weight: 600;
        }

        .layout-main-right .nav-layout-sidebar {
            margin-left: 0;
            margin-right: -31px;
        }

        .nav-stacked > li + li {
            margin-top: 0;
        }

        .control-label {
            text-align: right !important;
        }
    </style>
@stop

@section('content')
    <h2>{{ trans('admin.edit.title') }}</h2>
    @if ( is_array(Session::get('error')) )
        <div class="alert alert-error">{{ head(Session::get('error')) }}</div>
    @elseif ( Session::get('error') )
        <div class="alert alert-error">{{ Session::get('error') }}</div>
    @endif @if ( Session::get('success') )
        <div class="alert alert-success">{{ Session::get('success') }}</div>
    @endif @if ( Session::get('notice') )
        <div class="alert">{{ Session::get('notice') }}</div>
    @endif

    <div class="layout layout-main-right layout-stack-sm">
        <div class="col-sm-3 col-sm-8 layout-sidebar">
            <ul id="myTab" class="nav nav-layout-sidebar nav-stacked">
                <li class="{{ $tab == 'profile' ? 'active' : '' }}"><a href="#profile_tab" data-toggle="tab">
                        <i class="fa fa-user"></i>&nbsp;&nbsp;&nbsp;{{ trans('admin.edit.profile.title') }}
                    </a></li>
                <li class="{{ $tab == 'bank' ? 'active' : '' }}"><a href="#bank" data-toggle="tab">
                        <i class="fa fa-dollar"></i>&nbsp;&nbsp;&nbsp;{{ trans('admin.edit.bank.title') }}
                    </a></li>
                @if ($login_user->hasRole(ROLE_ADMIN) || $login_user->hasRole(ROLE_BRANCH1) || $login_user->hasRole(ROLE_BRANCH2))
                    <li class="{{ $tab == 'wallet' ? 'active' : '' }}"><a href="#wallet" data-toggle="tab">
                            <i class="fa fa-dollar"></i>&nbsp;&nbsp;&nbsp;{{ trans('admin.edit.wallet.title') }}
                        </a></li>
                @endif
                <li class="{{ $tab == 'balance' ? 'active' : '' }}"><a href="#balance" data-toggle="tab">
                        <i class="fa fa-dollar"></i>&nbsp;&nbsp;&nbsp;{{ trans('admin.edit.balance.title') }}
                    </a></li>
            </ul>
        </div>
        <!-- /.col -->
        <div class="col-md-9 col-sm-8 layout-main">
            <div id="settings-content" class="tab-content stacked-content">
                <div class="tab-pane fade {{ $tab == 'profile' ? 'in active' : '' }}" id="profile_tab">
                    <h3 class="content-title">
                        <u>{{ trans('admin.edit.profile.sub_title') }}</u>
                    </h3>

                    <p>{{ trans('admin.edit.profile.necessary') }}</p>
                    <br>
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @elseif (!empty($profile_save_success = Session::pull('profile_save_success', '')))
                        <div class="alert alert-success">
                            <ul>
                                <li>{{ $profile_save_success }}</li>
                            </ul>
                        </div>
                    @endif
                    <form method="POST" class="form-horizontal"
                          action="{{ url('/manage/root/user/edit/' . $user->id . '/profile') }}"
                          enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">{{ trans('admin.edit.profile.identity_identification') }}</label>
                                <div class="col-md-8" style="margin-top:7px;">
                                    @if ( $user->verified == 0 )
                                        <div style="margin-bottom: 15px;color: red;font-size: 14px;font-weight: bold">
                                            {{ trans('admin.user.message.notverified') }}
                                            <a href="{{ url('/manage/root/user/verify') }}/{{ $user['id'] }}" class="edit_page"><span>{{ trans('admin.edit.profile.mail_confirm') }}</span></a>
                                        </div>
                                    @elseif($usersinfo->licensed == USER_LICENSE_CHECKED)
                                        <div style="margin-bottom: 15px;">
                                            {{ trans('admin.user.message.verified') }}
                                        </div>
                                    @elseif (!empty($usersinfo->identity_front) || !empty($usersinfo->identity_end))
                                        <div style="margin-bottom: 15px;">
                                            {{ trans('admin.user.message.unverified') }}&nbsp;&nbsp;
                                            <a href="{{ url('/manage/root/user/license') }}/{{ $user['id'] }}" class="edit_page"><span>{{ trans('admin.edit.profile.identity_confirm') }}</span></a>
                                        </div>
                                    @else
                                        <div style="margin-bottom: 15px;">
                                            {{ trans('admin.user.message.unverified') }}                                          
                                        </div>
                                    @endif

                                    <div class="fileupload fileupload-new" data-provides="fileupload" style="margin-bottom:15px;">
                                        @if(substr(strtolower($usersinfo->identity_front), -4) == '.pdf')
                                            表示: <a href="<?php echo url('/', $parameters = array(), $secure = null); ?>/upload/identity/{{ $usersinfo->licensed == USER_LICENSE_UNCHECKED ? 'tmp/' : '' }}{{$usersinfo->identity_front}}"
                                               target="_blank" style="text-decoration:underline;">{{ trans('admin.edit.profile.identity_front') }}</a>
                                        @else
                                            <div class="fileupload-new thumbnail"
                                                 style="display:inline-block;width:180px; height:180px; float:left;">
                                                <img src="<?php echo url('/', $parameters = array(), $secure = null); ?>/upload/identity/{{ $usersinfo->licensed == USER_LICENSE_UNCHECKED ? 'tmp/' : '' }}{{$usersinfo->identity_front}}"
                                                     alt="{{ trans('message.register.file_no') }}"/>
                                            </div>
                                        @endif
                                        @if(substr(strtolower($usersinfo->identity_end), -4) == '.pdf')
                                            <a href="<?php echo url('/', $parameters = array(), $secure = null); ?>/upload/identity/{{ $usersinfo->licensed == USER_LICENSE_UNCHECKED ? 'tmp/' : '' }}{{$usersinfo->identity_end}}"
                                               target="_blank" style="text-decoration:underline;">{{ trans('admin.edit.profile.identity_end') }}</a>
                                        @else
                                            <div class="fileupload-new thumbnail"
                                                 style="display:inline-block;width:180px; height:180px; float:left;">
                                                <img src="<?php echo url('/', $parameters = array(), $secure = null); ?>/upload/identity/{{ $usersinfo->licensed == USER_LICENSE_UNCHECKED ? 'tmp/' : '' }}{{$usersinfo->identity_end}}"
                                                     alt="{{ trans('message.register.file_no') }}"/>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @if (!empty($usersinfo->identity_front) || !empty($usersinfo->identity_end))
                                <div class="col-md-8 col-md-offset-4">
                                    <div style="margin-bottom: 15px;">
                                        <a href="{{ url('/manage/root/user/delete_pictures') }}/{{ $user['id'] }}" >
                                            <input type="button" value="{{ trans('admin.edit.profile.delete_pictures') }}"/>
                                        </a>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">{{ trans('admin.edit.profile.identity_front') }}&nbsp;</label>
                                <div class="col-sm-8">
                                  <input type="file" name="avatar" style="display:inline-block;" value="{{ $usersinfo->licensed == USER_LICENSE_UNCHECKED ? $usersinfo->identity_front : '' }}"/><br/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">{{ trans('admin.edit.profile.identity_end') }}</label>
                                <div class="col-sm-8">
                                    <input type="file" name="avatar2" style="display:inline-block;" value="{{ $usersinfo->licensed == USER_LICENSE_UNCHECKED ? $usersinfo->identity_end : '' }}"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">{{ trans('admin.edit.profile.company') }}</label>

                                <div class="col-sm-8">
                                    <input type="text" name="company" value="{{ $usersinfo->company }}"
                                           class="form-control"/>
                                </div>
                            </div>
                            @if (($locale = Session::get('locale', 'ja')) == 'ja')
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">{{ trans('admin.edit.profile.firstname') }}</label>

                                    <div class="col-sm-8">
                                        <input type="text" name="firstname" value="{{ $user->firstname }}"
                                               class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">{{ trans('admin.edit.profile.lastname') }}</label>

                                    <div class="col-sm-8">
                                        <input type="text" name="lastname" value="{{ $user->lastname }}"
                                               class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">{{ trans('admin.edit.profile.firstname1') }}</label>

                                    <div class="col-sm-8">
                                        <input type="text" name="firstname1" value="{{ $usersinfo->firstname1 }}"
                                               class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">{{ trans('admin.edit.profile.lastname1') }}</label>

                                    <div class="col-sm-8">
                                        <input type="text" name="lastname1" value="{{ $usersinfo->lastname1 }}"
                                               class="form-control"/>
                                    </div>
                                </div>
                            @elseif ($locale == 'en')
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">{{ trans('admin.edit.profile.firstname') }}</label>

                                    <div class="col-sm-8">
                                        <input type="text" name="lastname" value="{{ $user->lastname }}"
                                               class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">{{ trans('admin.edit.profile.lastname') }}</label>

                                    <div class="col-sm-8">
                                        <input type="text" name="firstname" value="{{ $user->firstname }}"
                                               class="form-control"/>
                                    </div>
                                </div>
                            @elseif ($locale == 'cn')
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">{{ trans('admin.edit.profile.firstname') }}</label>

                                    <div class="col-sm-8">
                                        <input type="text" name="firstname" value="{{ $user->firstname }}"
                                               class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">{{ trans('admin.edit.profile.lastname') }}</label>

                                    <div class="col-sm-8">
                                        <input type="text" name="lastname" value="{{ $user->lastname }}"
                                               class="form-control"/>
                                    </div>
                                </div>
                            @endif
                            <div class="form-group">
                                <label class="col-sm-4 control-label">{{ trans('admin.edit.profile.email') }}</label>

                                <div class="col-sm-8">
                                    <input type="text" name="email" value="{{ $user->email }}" class="form-control"/>
                                </div>
                            </div>
                            @if ($login_user->hasRole(ROLE_ADMIN))
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">{{ trans('admin.edit.profile.password')}}</label>

                                    <div class="col-sm-8">
                                        {{ $user->password }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">{{ trans('admin.edit.profile.birthday')}}</label>

                                    <div class="col-sm-8">
                                        <input type="text" id="birthday_datepicker" value="{{ $usersinfo->birthday }}"
                                               class="form-control" style="cursor:pointer" readonly/>
                                        <input type="hidden" id="birthday" name="birthday"/>
                                    </div>
                                </div>
                            @endif
                            <div class="form-group">
                                <label class="col-sm-4 control-label">{{ trans('admin.edit.profile.telnum') }}</label>

                                <div class="col-sm-8">
                                    <input type="text" name="telnum" value="{{ $usersinfo->telnum }}"
                                           class="form-control"/>
                                </div>
                            </div>
                            @if ($locale == 'en')
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">{{ trans('admin.edit.profile.buildingname') }}</label>

                                    <div class="col-sm-8">
                                        <input type="text" name="buildingname" value="{{ $usersinfo->buildingname }}"
                                               class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">{{ trans('admin.edit.profile.address') }}</label>

                                    <div class="col-sm-8">
                                        <input type="text" name="address" value="{{ $usersinfo->address }}"
                                               class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">{{ trans('admin.edit.profile.city') }}</label>

                                    <div class="col-sm-8">
                                        <input type="text" name="city" value="{{ $usersinfo->city }}" class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">{{ trans('admin.edit.profile.state') }}</label>

                                    <div class="col-sm-8">
                                        <input type="text" name="state" value="{{ $usersinfo->state }}"
                                               class="form-control"/>
                                    </div>
                                </div>                               
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">{{ trans('admin.edit.profile.country') }}</label>
                                    <div class="col-sm-8">
                                        <select id="authy-countries" name="country" data-value="{{ $usersinfo->country or 1 }}"></select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">{{ trans('admin.edit.profile.zipcode') }}</label>

                                    <div class="col-sm-8">
                                        <input type="text" name="zipcode" value="{{ $usersinfo->zipcode }}"
                                               class="form-control"/>
                                    </div>
                                </div>
                            @else
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">{{ trans('admin.edit.profile.zipcode') }}</label>

                                    <div class="col-sm-8">
                                        <input type="text" name="zipcode" value="{{ $usersinfo->zipcode }}"
                                               class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">{{ trans('admin.edit.profile.country') }}</label>
                                    <div class="col-sm-8">
                                        <select id="authy-countries" name="country" data-value="{{ $usersinfo->country or 1 }}"></select>
                                    </div>
                                </div>                                
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">{{ trans('admin.edit.profile.state') }}</label>

                                    <div class="col-sm-8">
                                        <input type="text" name="state" value="{{ $usersinfo->state }}" class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">{{ trans('admin.edit.profile.city') }}</label>

                                    <div class="col-sm-8">
                                        <input type="text" name="city" value="{{ $usersinfo->city }}" class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">{{ trans('admin.edit.profile.address') }}</label>

                                    <div class="col-sm-8">
                                        <input type="text" name="address" value="{{ $usersinfo->address }}"
                                               class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">{{ trans('admin.edit.profile.buildingname') }}</label>

                                    <div class="col-sm-8">
                                        <input type="text" name="buildingname" value="{{ $usersinfo->buildingname }}"
                                               class="form-control"/>
                                    </div>
                                </div>
                            @endif
                            <div class="form-group">
                                <label class="col-sm-4 control-label">
                                    {{ trans('message.profile.tfa') }}:
                                </label>
                                <div class="col-sm-4">
                                    <label class="switch">
                                        <input type="checkbox" id="tfa_use" name="tfa_use" value="1" {{ isset($user->tfa_flag) && $user->tfa_flag == 1 || isset($user->qr_flag) && ($user->qr_flag == 1)? 'checked' : '' }}>
                                        <span></span>
                                    </label>
                                    <div id="tfa_auth_board">
                                        <div class="checkbox i-checks">
                                            <label>
                                                <input type="radio" name="tfa_auth" value="1" {{ isset($user->qr_flag) && ($user->qr_flag == 1) ? 'checked' : '' }}>
                                                <i></i>{{ trans('message.profile.tfa_qrcode') }}
                                            </label>
                                        </div>
                                        <div class="checkbox i-checks">
                                            <label>
                                                <input type="radio" name="tfa_auth" value="2" {{ isset($user->tfa_flag) && ($user->tfa_flag == 1) ? 'checked' : '' }}>
                                                <i></i>{{ trans('message.profile.tfa_smscode') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if ($login_user->hasRole(ROLE_ADMIN))
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">{{ trans('admin.user.message.branch1') }}</label>
                                    <div class="col-md-8">
                                        <input type="text" name="branch1" value="{{ $usersinfo->branch1 }}"
                                               class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">{{ trans('admin.user.message.branch2') }}</label>
                                    <div class="col-md-8">
                                        <input type="text" name="branch2" value="{{ $usersinfo->branch2 }}"
                                               class="form-control"/>
                                    </div>
                                </div>
                            @elseif ($login_user->hasRole(ROLE_BRANCH1) && $user->id == $login_user->id)
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">{{ trans('admin.user.message.branch1') }}</label>
                                    <div class="col-md-8">
                                        <input type="text" name="branch1" value="{{ $usersinfo->branch1 }}"
                                               class="form-control" disabled />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">{{ trans('admin.user.message.branch2') }}</label>
                                    <div class="col-md-8">
                                        <input type="text" name="branch2" value="{{ $usersinfo->branch2 }}"
                                               class="form-control"/>
                                    </div>
                                </div>
                            @endif
                            <div class="form-group">
                                <label class="col-sm-4 control-label">{{ trans('admin.edit.profile.identity_send') }}</label>
                                <div class="col-md-8">
                                    <input type="checkbox" id="chk_fax_or_post" name="fax_or_post"
                                    <?php if (!empty($usersinfo->fax_or_post)) echo "checked='checked'" ?> />
                                    <label for="chk_fax_or_post">{{ trans('admin.edit.profile.identity_send_desc') }}</label>
                                </div>
                            </div>
                            @if($user->hasRole('Branch1') || $user->hasRole('Branch2'))
                                <div class="form-group">
                                    &nbsp;
                                </div>
                                <div id="bonus" class="form-group">
                                    <label class="col-sm-4 control-label">{{ trans('admin.user.message.bonus') }}</label>
                                    <div class="col-md-8">
                                        <input type="text" name="bonus" value="{{ $usersinfo['bonus1'] }}"
                                               class="form-control"/>
                                    </div>
                                </div>
                            @endif

                            <h3>{{ trans('admin.edit.profile.role') }}</h3>

                            <div class="form-group">
                                <div class="col-sm-offset-4 col-sm-10">
                                    @foreach($roles as $role)
                                        <div class="checkbox">
                                            <label> <input type="checkbox" id="{{ $role['name'] }}" name="roles[]" value="{{ $role['name'] }}"
                                                        @if( $user->hasRole($role['name']) )
                                                           checked
                                                        @endif
                                                    />
                                                {{ $role['name'] }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-8 col-md-push-4">
                                    <button type="submit"
                                            class="btn btn-primary">{{ trans('button.edit_save') }}</button>
                                    &nbsp;
                                    <button type="reset" onclick="redirect_back()"
                                            class="btn btn-default">{{ trans('button.cancel') }}</button>
                                    <br/>
                                    <br/>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="tab-pane fade {{ $tab == 'bank' ? 'in active' : '' }}" id="bank">
                    <h3 class="content-title">
                        <u>{{ trans('admin.edit.bank.sub_title') }}</u>
                    </h3>
                    <br>
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @elseif (isset($bank_save_success))
                        <div class="alert alert-success">
                            <ul>
                                <li>{{ $bank_save_success }}</li>
                            </ul>
                        </div>
                    @endif
                    <form method="POST" class="form-horizontal" action="{{ url('/manage/root/user/edit/' . $user->id . '/bank') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <label class="col-sm-1 control-label">{{ trans('admin.edit.bank.bankname') }}</label>
                            <div class="col-md-5">
                                <input type="text" name="bankname" value="{{ $usersinfo->bankname }}" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-1 control-label">{{ trans('admin.edit.bank.branchname') }}</label>
                            <div class="col-sm-5">
                                <input type="text" name="branchname" value="{{ $usersinfo->branchname }}" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-1 control-label">{{ trans('admin.edit.bank.accounttype') }}</label>
                            <div class="col-sm-5">
                                <select class="form-control" id="accounttype" name="accounttype">
                                    <option value="" disabled>{{ trans('message.register.please_select') }}</option>
                                    <option value="1">{{ trans('message.register.accounttype_general') }}</option>
                                    <option value="2">{{ trans('message.register.accounttype_current') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-1 control-label">{{ trans('admin.edit.bank.accountnumber') }}</label>
                            <div class="col-sm-5">
                                <input type="text" name="accountnumber" value="{{ $usersinfo->accountnumber }}"
                                       class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-1 control-label">{{ trans('admin.edit.bank.accountname') }}</label>
                            <div class="col-sm-5">
                                <input type="text" name="accountname" value="{{ $usersinfo->accountname }}"
                                       class="form-control"/>
                                <input type="hidden" name="bank_info" value="Bank" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8 col-md-push-1">
                                <button type="submit" class="btn btn-primary">{{ trans('button.edit_save') }}</button>
                                &nbsp;
                                <button type="reset" onclick="redirect_back()" class="btn btn-default">{{ trans('button.cancel') }}</button>
                                <br/>
                                <br/>
                            </div>
                        </div>
                    </form>
                </div>
                @if ($login_user->hasRole(ROLE_ADMIN))
                    <div class="tab-pane fade {{ $tab == 'wallet' ? 'in active' : '' }}" id="wallet">
                        <h3 class="content-title">
                            <u>{{ trans('admin.edit.wallet.sub_title') }}</u>
                        </h3>
                        <br>
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @elseif (isset($wallet_save_success))
                            <div class="alert alert-success">
                                <ul>
                                    <li>{{ $wallet_save_success }}</li>
                                </ul>
                            </div>
                        @endif
                        <form method="POST" class="form-horizontal" action="{{ url('/manage/root/user/edit/' . $user->id . '/wallet') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <label class="col-sm-1 control-label">{{ trans('admin.edit.wallet.username') }}</label>
                                <div class="col-md-5">
                                    <input type="text" name="wallet_username"
                                           value="{{ $userswallet->wallet_username }}" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-1 control-label">{{ trans('admin.edit.wallet.password') }}</label>
                                <div class="col-md-5">
                                    <input type="text" name="wallet_password"
                                           value="{{ $userswallet->wallet_password }}" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-1 control-label">{{ trans('admin.edit.wallet.address') }}</label>
                                <div class="col-md-5">
                                    <input type="text" name="wallet_address" value="{{ $userswallet->wallet_address }}"
                                           class="form-control" disabled/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-1 control-label">{{ trans('admin.edit.wallet.secret') }}</label>
                                <div class="col-md-5">
                                    <input type="text" name="wallet_secret" value="{{ $userswallet->wallet_secret }}"
                                           class="form-control" disabled/>
                                </div>
                            </div>
                            <div class="form-group">
                                @if ($user['verified'] == 0)
                                    <div class="col-md-8 col-md-push-1">
                                        <div style="margin-bottom: 15px;color: red;font-size: 14px;font-weight: bold">
                                            {{ trans('admin.user.message.notverified') }}
                                        </div>
                                    </div>
                                @elseif (empty($userswallet->wallet_address))
                                    <div class="col-md-8 col-md-push-1">
                                        <button type="button" id="generate" value="generate" class="btn btn-default">{{ trans('button.wallet_generate')}}</button>
                                    </div>
                                @elseif ($userswallet->active == WALLET_NOACTIVE)
                                    <div class="col-md-8 col-md-push-1">
                                        <button type="button" id="active" value="active" class="btn btn-default">{{ trans('button.active')}}</button>
                                        <br>
                                        <br>
                                    </div>
                                @else
                                    <div class="col-md-1 col-md-push-1">
                                        <select name="curr" class="form-control">
                                            @foreach($currencies as $currency)
                                                <option name="curr_item">{{ $currency }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-md-push-1">
                                        <select name="gateway" class="form-control">
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-md-push-1">
                                        <button type="button" id="trust" value="trust" class="btn btn-default">{{ trans('button.trust')}}</button>&nbsp;&nbsp;
                                        <button type="button" id="remove" value="remove" class="btn btn-default">{{ trans('button.remove')}}</button>
                                        <br/>
                                        <br/>
                                    </div>
                                @endif
                                <br>
                                <div class="col-md-8 col-md-push-1">
                                    <button type="submit" class="btn btn-primary" {{ $user['verified']==0 ? 'disabled' : '' }}> {{ trans('button.edit_save') }} </button>
                                    &nbsp;
                                    <button type="reset" onclick="redirect_back()" class="btn btn-default">{{ trans('button.cancel') }}</button>
                                    <br/>
                                    <br/>
                                </div>
                            </div>
                        </form>
                    </div>
                @elseif ($login_user->hasRole(ROLE_BRANCH1) || $login_user->hasRole(ROLE_BRANCH2))
                    <div class="tab-pane fade {{ $tab == 'wallet' ? 'in active' : '' }}" id="wallet">
                        <h3 class="content-title">
                            <u>{{ trans('admin.edit.wallet.sub_title') }}</u>
                        </h3>
                        <br>
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @elseif (isset($wallet_save_success))
                            <div class="alert alert-success">
                                <ul>
                                    <li>{{ $wallet_save_success }}</li>
                                </ul>
                            </div>
                        @endif
                        <form method="POST" class="form-horizontal"
                              action="{{ url('/manage/root/user/edit/' . $user->id . '/wallet') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="form-group">
                                <label class="col-sm-1 control-label">{{ trans('admin.edit.wallet.address') }}</label>
                                <div class="col-md-5">
                                    <input type="text" name="wallet_address" value="{{ $userswallet->wallet_address }}" class="form-control" disabled/>
                                </div>
                            </div>
                            <div class="form-group">
                                @if ($user['verified'] == 0)
                                <div class="col-md-5 col-md-push-1">
                                    <div style="margin-bottom: 15px;color: red;font-size: 14px;font-weight: bold">
                                        {{ trans('admin.user.message.notverified') }}
                                    </div>
                                </div>
                                @elseif (empty($userswallet->wallet_address))
                                <div class="col-md-5 col-md-push-1">
                                    <button type="button" id="generate" value="generate"
                                            class="btn btn-default">{{ trans('button.wallet_generate')}}</button>
                                    &nbsp;
                                </div>
                                @elseif ($userswallet->active == WALLET_NOACTIVE)
                                <div class="col-md-5 col-md-push-1">
                                    <button type="button" id="active" value="active"
                                            class="btn btn-default">{{ trans('button.active')}}</button>
                                    <br/>
                                    <br/>
                                </div>
                                @elseif (!empty($currencies))
                                <div class="col-md-3 col-md-push-1">
                                    <select name="curr" class="form-control">
                                        @foreach($currencies as $currency)
                                            <option>{{ $currency }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 col-md-push-1">
                                    <button type="button" id="trust" value="trust" style="display: inline;"
                                            class="btn btn-default">{{ trans('button.trust')}}</button>
                                    <br/>
                                    <br/>
                                </div>
                                @endif
                            </div>
                        </form>
                    </div>
                @endif
                <div class="tab-pane fade {{ $tab == 'balance' ? 'in active' : '' }}" id="balance">
                    <h3 class="content-title">
                        <u>{{ trans('admin.edit.balance.sub_title') }}</u>
                    </h3>

                    <p>{{ trans('admin.edit.balance.necessary') }}</p>
                    <br>
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @elseif (isset($balance_save_success))
                        <div class="alert alert-success">
                            <ul>
                                <li>{{ $balance_save_success }}</li>
                            </ul>
                        </div>
                    @endif
                    <form method="POST" class="form-horizontal"
                          action="{{ url('/manage/root/user/edit/' . $user->id . '/balance') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group">
                            <label class="col-sm-1 control-label">{{ trans('admin.edit.balance.balance') }}</label>
                            <div class="col-md-5">
                                @if ($login_user->hasRole(ROLE_ADMIN))
                                    <input type="text" name="balance" value="{{ $usersinfo['balance'] }}"
                                           class="form-control"/>
                                @else
                                    <input type="text" name="balance" value="{{ $usersinfo['balance'] }}"
                                           class="form-control" disabled/>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8 col-md-push-1">
                                @if ($login_user->hasRole(ROLE_ADMIN))
                                    <button type="submit" class="btn btn-primary"> {{ trans('button.edit_save') }} </button>
                                    &nbsp;
                                    <button type="reset" onclick="redirect_back()" class="btn btn-default">{{ trans('button.cancel') }}</button>
                                @endif
                                <br/>
                                <br/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('footer')
<script src="{{ asset('js/jquery.ajaxloader.js') }}"></script>
<script src="{{ asset('js/ui/jquery.ui.core.js') }}"></script>
<script src="{{ asset('js/authy.js') }}"></script>
<script src="{{ asset('js/ui/jquery.ui.datepicker.js') }}"></script>
@if (config('app.locale') != 'en')
    <script src="{{ asset('js/ui/i18n/jquery.ui.datepicker-ja.js') }}"></script>
@endif
<script type="text/javascript">
    function redirect_back() {
        location.href = "{{ url('/manage/root/user') }}";
    }
    $(document).ready(function () {
        $('[name=curr]').trigger('change');
        if ('{{ Session::get('locale', 'ja') }}' == 'ja' || '{{ Session::get('locale', 'ja') }}' == 'cn') {
            $( "#birthday_datepicker" ).datepicker(
            {
                showButtonPanel: true,
                changeMonth: true,
                changeYear: true,
                yearRange:　"c-100:c",
                dateFormat: "yy年 mm月 dd日",
                onSelect: function(dateText, inst) {
                    dateText = dateText.replace("年 ", "/");
                    dateText = dateText.replace("月 ", "/");
                    dateText = dateText.replace("日", "");
                    $( "#birthday" ).val(dateText);
                }
            });
        } else {
            $( "#birthday_datepicker" ).datepicker(
            {
                showButtonPanel: true,
                changeMonth: true,
                changeYear: true,
                yearRange:　"c-100:c",
                dateFormat: "yy/mm/dd",
                onSelect: function(dateText, inst) {
                    $( "#birthday" ).val(dateText);
                }
            });
        }
        if ('{{ $usersinfo->birthday }}' != '0000/00/00' && '{{ $usersinfo->birthday }}' != '') {
            $( "#birthday_datepicker" ).datepicker("setDate", new Date('{{ $usersinfo->birthday }}'));
            $( "#birthday" ).val('{{ $usersinfo->birthday }}');
        } else {
            $( "#birthday_datepicker" ).datepicker("setDate", '');
        }
    });
    $('[name=curr]').bind('change', function(e) {
        $('[name=gateway]').val('');
        $('[name=gateway]').prop('disabled', true);
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
                    gateways_html += '<option name="gateway_item" address="' + gateways[i].owner_address + '">' + gateways[i].name + '</option>';
                }
                if (!!gateways_html) {
                    $('[name=gateway]').html(gateways_html);
                    $('[name=gateway]').prop('disabled', false);
                } else
                    $('[name=gateway]').html('');
                $('[name=issuer_item]').click(function() {
                    $('[name=issuer]').val($(this).attr('address'));
                });
                $('[name=gateway_item]').click(function() {
                    var address = $(this).attr('address');
                    if (!address) address = "";
                    $('[name=gateway]').prop('address', address);
                });
                $('[name=gateway_item]:first').trigger('click');
            }
        });
    });
    $('select#accounttype').prop('value', '{{ $usersinfo->accounttype }}');
    ajaxLoader.init({
        'loader': 'dots',
        'theme': 'dark',
        'size': 10,
    });
    $('#generate').click(function () {
        /*$.ajaxSetup({
         headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         }
         });*/
        $('#ALP_ajax-overlay').show();
        $.getJSON("{{ url('/manage/root/stellar/generate/' . $user->id) }}", function (data) {
            $('#ALP_ajax-overlay').hide();
            if (data.result == 'Success') {
                location.reload();
            } else {
                alert(data.message);
            }
        });
    });

    $('#active').click(function () {
        /*$.ajaxSetup({
         headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         }
         });*/
        $('#ALP_ajax-overlay').show();
        $.getJSON("{{ url('/manage/root/stellar/active/' . $user->id) }}", function (data) {
            $('#ALP_ajax-overlay').hide();
            if (data.result == 'Success') {
                location.reload();
            } else {
                alert(data.message);
            }
        });
    });

    $('#trust').click(function () {
        /*$.ajaxSetup({
         headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         }
         });*/
        var curr = $('[name=curr]').val();
        var gateway = $('[name=gateway]').prop('address');
        console.log(curr);
        console.log(gateway);
        $('#ALP_ajax-overlay').show();
        $.getJSON("{{ url('/manage/root/stellar/trust/' . $user->id) }}/" + curr + '/' + gateway, function (data) {
            $('#ALP_ajax-overlay').hide();
            if (data.result == 'Success') {
                location.reload();
            } else {
                alert(data.message);
            }
        });
    });
    $('#remove').click(function () {
        /*$.ajaxSetup({
         headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         }
         });*/
        var curr = $('[name=curr]').val();
        var gateway = $('[name=gateway]').prop('address');
        console.log(curr);
        console.log(gateway);
        $('#ALP_ajax-overlay').show();
        $.getJSON("{{ url('/manage/root/stellar/trust/' . $user->id) }}/" + curr + '/' + gateway, "remove=true", function (data) {
            $('#ALP_ajax-overlay').hide();
            if (data.result == 'Success') {
                location.reload();
            } else {
                alert(data.message);
            }
        });
    });
    $('#Branch1').change(function () {
        if (this.checked) {
            $('#bonus').attr('style', 'display: show;');
        } else {
            if ($('#Branch2').prop('checked') == false) {
                $('#bonus').attr('style', 'display: none;');
            }
        }
    });
    $('#Branch2').change(function () {
        if (this.checked) {
            $('#bonus').attr('style', 'display: show;');
        } else {
            if ($('#Branch1').prop('checked') == false) {
                $('#bonus').attr('style', 'display: none;');
            }
        }
    });
    $('[name=qr_auth]').change(function() {
        if (!$(this).prop('checked')) {
            $(this).val(0);
        } else 	{
            $(this).val(1);
        }
    });
    $('[name=sms_auth]').change(function() {
        if (!$(this).prop('checked')) {
            $(this).val(0);
        } else 	{
            $(this).val(1);
        }
    });
    $('#tfa_use').change(function() {
        if ($(this).prop('checked')) {
            if ('{{ $user->qr_flag }}' == 1) {
                $('[name=tfa_auth][value="1"]').prop('checked', true);
            } else if ('{{ $user->tfa_flag }}' == 1) {
                $('[name=tfa_auth][value="2"]').prop('checked', true);
            } else {
                $('[name=tfa_auth][value="1"]').prop('checked', true);
            }
            $('#tfa_auth_board').fadeIn('slow');
        } else {
            $('#tfa_auth_board').fadeOut('fast');
        }
    });
    $('#tfa_use').trigger('change');
    function getCountryState() {
        return;
    }
</script>

@stop