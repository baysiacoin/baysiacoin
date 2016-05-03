@extends('layouts.user')

@section('header')
<link rel="stylesheet" href="{{ asset('css/jquery/jquery.ui.all.css') }}" type="text/css" />
<link rel="stylesheet" href="{{ asset('css/flags.authy.css') }}" type="text/css" />
@stop

@section('content')
<section class="scrollable padder bg-white">
	<div class="m-b-md">
		<h3 class="m-b-none">{{ trans('menu.my_profile') }}</h3>
	</div>
	@if ( isset($from_facebook) && $from_facebook == true )
	<div class="alert alert-danger">
		{{ trans('message.register.alert_facebook_password') }}<br>
		{{ trans('message.register.alert_facebook_password_first') }} {{$fbInitPwd or ''}} {{ trans('message.register.alert_facebook_password_second') }}
	</div>
	@endif
	<ul class="nav nav-tabs m-b-n-xxs bg-light">
        <li class="{{ $page=='profile' ? 'active' : '' }}">
            <a href="#profile" data-toggle="tab" class="m-l">{{ trans('menu.edit_profile')}}</a>
        </li>
		<li class="{{ $page=='bank' ? 'active' : '' }}">
            <a href="#bank" data-toggle="tab">{{ trans('menu.edit_bank') }}</a>
        </li>
		<li class="{{ $page=='password' ? 'active' : '' }}">
            <a href="#password" data-toggle="tab">{{ trans('menu.edit_password') }}</a>
        </li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane wrapper-lg {{ $page=='profile' ? 'active' : '' }}" id="profile">
			@if ($page == 'profile' && count($errors) > 0)
				<div class="alert alert-danger">
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@elseif ($page== 'profile' && !empty($profile_save_success = Session::pull('profile_save_success', '')))
				<div class="alert alert-success">
					<ul>
						<li>{{ $profile_save_success }}</li>
					</ul>
				</div>
			@endif
			<form class="form-horizontal" method="post" action="{{ url('/user/profile') }}" enctype="multipart/form-data">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<input type="hidden" name="page" value="profile">
				<div class="form-group">
					<label class="col-sm-3 control-label" for="input-id-1">{{ trans('message.register.company') }}:</label>
					<div class="col-sm-5">
						<font class="text-danger">{{ trans($errors->first('company')) }}</font>
						<input type="text" class="form-control" name="company" value="{{ $company or '' }}">
					</div>
				</div>
				<div class="line line-dashed b-b line-lg pull-in"></div>
				@if (($locale = Session::get('locale', 'ja')) == 'ja')
					<div class="form-group">
						<div class="col-sm-3 control-label"></div>
						<div class="text-danger">{{ trans($errors->first('firstname')) }} &nbsp; {{ trans($errors->first('lastname'))}}</div>
						<label class="col-sm-3 control-label">{{ trans('message.register.fullname') }}:</label>
						<div class="col-sm-2">
							<input type="text" class="form-control" name="firstname" value="{{ $firstname or ''}}" placeholder="{{ trans('message.register.firstname_ph') }}">
						</div>
						<div class="col-sm-3">
							<input type="text" class="form-control" name="lastname" value="{{ $lastname or '' }}" placeholder="{{ trans('message.register.lastname_ph') }}">
						</div>
					</div>
					<div class="line line-dashed b-b line-lg pull-in"></div>
					<div class="form-group">
						<div class="col-sm-3 control-label"></div>
						<div class="text-danger">{{ trans($errors->first('firstname1')) }} &nbsp; {{ trans($errors->first('lastname1'))}}</div>
						<label class="col-sm-3 control-label">{{ trans('message.register.yomigana') }}:</label>
						<div class="col-sm-2">
							<input type="text" class="form-control" name="firstname1" value="{{ $firstname1 or ''}}" placeholder="{{ trans('message.register.firstname1_ph') }}">
						</div>
						<div class="col-sm-3">
							<input type="text" class="form-control" name="lastname1" value="{{ $lastname1 or '' }}" placeholder="{{ trans('message.register.lastname1_ph') }}">
						</div>
					</div>
				@elseif ($locale == 'en')
					<div class="form-group">
						<div class="col-sm-3 control-label"></div>
						<div class="text-danger">{{ trans($errors->first('lastname')) }} &nbsp; {{ trans($errors->first('firstname'))}}</div>
						<label class="col-sm-3 control-label">{{ trans('message.register.fullname') }}:</label>
						<div class="col-sm-3">
							<input type="text" class="form-control" name="lastname" value="{{ $lastname or '' }}" placeholder="{{ trans('message.register.firstname_ph') }}">
						</div>
						<div class="col-sm-3">
							<input type="text" class="form-control" name="firstname" value="{{ $firstname or '' }}" placeholder="{{ trans('message.register.lastname_ph') }}">
						</div>
					</div>
				@elseif ($locale == 'cn')
					<div class="form-group">
						<div class="col-sm-3 control-label"></div>
						<div class="text-danger">{{ trans($errors->first('firstname')) }} &nbsp; {{ trans($errors->first('lastname'))}}</div>
						<label class="col-sm-3 control-label">{{ trans('message.register.fullname') }}:</label>
						<div class="col-sm-3">
							<input type="text" class="form-control" name="firstname" value="{{ $firstname or ''}}" placeholder="{{ trans('message.register.firstname_ph') }}">
						</div>
						<div class="col-sm-3">
							<input type="text" class="form-control" name="lastname" value="{{ $lastname or '' }}" placeholder="{{ trans('message.register.lastname_ph') }}">
						</div>
					</div>
				@endif
				<div class="line line-dashed b-b line-lg pull-in"></div>
				<div class="form-group">
					<label class="col-sm-3 control-label">{{ trans('message.register.gender') }}:</label>
					<div class="col-sm-4">
						<div class="radio i-checks">
							<font class="text-danger">{{ trans($errors->first('gender')) }}</font>
							<div class="radio i-checks" style="padding-top: 0px">
								<label>
								    <input type="radio" name="gender" value="0" {{ $gender == 0 ? 'checked' : '' }}>
								    <i></i>{{ trans('message.register.gender_man') }}
								</label>
							</div>
							<div class="radio i-checks" style="padding-top: 0px">
								<label>
									<input type="radio" name="gender" value="1" {{ $gender == 1 ? 'checked' : '' }}>
									<i></i>{{ trans('message.register.gender_woman') }}
								</label>
							</div>
						</div>
					</div>
				</div>
				<div class="line line-dashed b-b line-lg pull-in"></div>
				<div class="form-group">
					<label class="col-sm-3 control-label">{{ trans('message.register.birthday') }}:</label>
					<div class="col-sm-5">
						<font class="text-danger">{{ trans($errors->first('birthday')) }}</font>
						<input type="text" class="form-control"  style="cursor:pointer" readonly id="birthday_datepicker" placeholder="{{ trans('message.register.birthday_ph') }}"/>
						<br>
						{{ trans('message.register.birthday_caution') }}<br>
						<input type="hidden" id="birthday" name="birthday" />
					</div>
				</div>				
				<div class="line line-dashed b-b line-lg pull-in"></div>
				<div class="form-group">
					<label class="col-sm-3 control-label">{{ trans('message.register.email') }}:</label>
					<div class="col-sm-5">
						<label class="control-label">{{ $email or ''}}</label>
					</div>
				</div>
				<div class="line line-dashed b-b line-lg pull-in"></div>
				<div class="form-group">
					<label class="col-sm-3 control-label">{{ trans('message.register.baysiacoin_address') }}:</label>
					<div class="col-sm-5">
						<label class="control-label">{{ $wallet_address or ''}}</label>						
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">{{ trans('message.register.baysiacoin_secret') }}:</label>
					<div class="col-sm-5" id="">
						<span id="pwd_anony"><span class="fa fa-key"></span>&nbsp;&nbsp;&nbsp;<label class="control-label">*************</label></span>
						<span class="font-bold ui-helper-hidden" id="pwd_guide"></span>
						<input type="text" class="form-control ui-helper-hidden"  id="pwd" value="" placeholder="{{ trans('placeholder.enter_pwd') }}">
						<label class="control-label" id="help_sms" style="display: none;float:right;"><a style="cursor:pointer;" id="req_sms">{{ trans('message.profile.req_sms') }}</a></label>
					</div>
					<div class="col-sm-5 col-sm-offset-3">
						<label class="control-label"><a id="reveal" style="cursor:pointer;">{{ trans('message.profile.reveal') }}</a></label>
					</div>
				</div>
				<div class="line line-dashed b-b line-lg pull-in"></div>
				<div class="form-group">
					<label class="col-sm-3 control-label">{{ trans('message.register.telnum') }}:</label>
					<div class="col-sm-5">
						<font class="text-danger" name="telnum">{{ trans($errors->first('telnum')) }}</font>
						<input type="text" class="form-control" name="telnum" value="{{ $telnum or ''}}" placeholder="">
					</div>
				</div>
				<div class="line line-dashed b-b line-lg pull-in"></div>

				@if ($locale == 'en')
					<div class="form-group">
						<label class="col-sm-3 control-label">{{ trans('message.register.country') }}:</label>
						<div class="col-sm-5">
							<select id="authy-countries" name="country" data-value="+{{ $country or 1 }}"></select>
						</div>
					</div>
					<div class="line line-dashed b-b line-lg pull-in"></div>
					<div class="form-group">
						<label class="col-sm-3 control-label">{{ trans('message.register.buildingname') }}:</label>
						<div class="col-sm-5">
							<font class="text-danger">{{ trans($errors->first('buildingname')) }}</font>
							<input type="text" class="form-control" name="buildingname" value="{{ $buildingname or '' }}">
						</div>
					</div>
					<div class="line line-dashed b-b line-lg pull-in"></div>
					<div class="form-group">
						<label class="col-sm-3 control-label">{{ trans('message.register.address') }}:</label>
						<div class="col-sm-5">
							<font class="text-danger">{{ trans($errors->first('address')) }}</font>
							<input type="text" class="form-control" name="address" value="{{ $address or '' }}">
						</div>
					</div>
					<div class="line line-dashed b-b line-lg pull-in"></div>
					<div class="form-group">
						<label class="col-sm-3 control-label">{{ trans('message.register.city') }}:</label>
						<div class="col-sm-5">
							<font class="text-danger">{{ trans($errors->first('city')) }}</font>
							<input type="text" class="form-control" name="city" value="{{ $city or ''}}">
						</div>
					</div>
					<div class="line line-dashed b-b line-lg pull-in"></div>
					<div class="form-group">
						<label class="col-sm-3 control-label">{{ trans('message.register.state') }}:</label>
						<div class="col-sm-5">
							<font class="text-danger">{{ trans($errors->first('state')) }}</font>
							<select data-required="true" class="form-control" name="state" id="state">
								<option value="" disabled>{{ trans('message.register.please_select') }}</option>
							</select>
						</div>
					</div>
					<div class="line line-dashed b-b line-lg pull-in"></div>
					<div class="form-group">
						<label class="col-sm-3 control-label">{{ trans('message.register.zipcode') }}:</label>
						<div class="col-sm-5">
							<font class="text-danger" name="zipcode">{{ trans($errors->first('zipcode')) }}</font>
							<input type="text" class="form-control" name="zipcode" value="{{ $zipcode or '' }}" placeholder="">
						</div>
					</div>
				@else
					<div class="form-group">
						<label class="col-sm-3 control-label">{{ trans('message.register.zipcode') }}:</label>
						<div class="col-sm-5">
							<font class="text-danger" name="zipcode">{{ trans($errors->first('zipcode')) }}</font>
							<input type="text" class="form-control" name="zipcode" value="{{ $zipcode or '' }}" placeholder="">
						</div>
					</div>
					<div class="line line-dashed b-b line-lg pull-in"></div>
					<div class="form-group">
						<label class="col-sm-3 control-label">{{ trans('message.register.country') }}:</label>
						<div class="col-sm-5">
							<select id="authy-countries" name="country" data-value="{{ $country or 1 }}"></select>
						</div>
					</div>
					<div class="line line-dashed b-b line-lg pull-in"></div>
					<div class="form-group">
						<label class="col-sm-3 control-label">{{ trans('message.register.state') }}:</label>
						<div class="col-sm-5">
							<font class="text-danger">{{ trans($errors->first('state')) }}</font>
							<select data-required="true" class="form-control" name="state" id="state">
								<option value="" disabled>{{ trans('message.register.please_select') }}</option>
							</select>
						</div>
					</div>
					<div class="line line-dashed b-b line-lg pull-in"></div>
					<div class="form-group">
						<label class="col-sm-3 control-label">{{ trans('message.register.city') }}:</label>
						<div class="col-sm-5">
							<font class="text-danger">{{ trans($errors->first('city')) }}</font>
							<input type="text" class="form-control" name="city" value="{{ $city or ''}}">
						</div>
					</div>
					<div class="line line-dashed b-b line-lg pull-in"></div>
					<div class="form-group">
						<label class="col-sm-3 control-label">{{ trans('message.register.address') }}:</label>
						<div class="col-sm-5">
							<font class="text-danger">{{ trans($errors->first('address')) }}</font>
							<input type="text" class="form-control" name="address" value="{{ $address or '' }}">
						</div>
					</div>
					<div class="line line-dashed b-b line-lg pull-in"></div>
					<div class="form-group">
						<label class="col-sm-3 control-label">{{ trans('message.register.buildingname') }}:</label>
						<div class="col-sm-5">
							<font class="text-danger">{{ trans($errors->first('buildingname')) }}</font>
							<input type="text" class="form-control" name="buildingname" value="{{ $buildingname or '' }}">
						</div>
					</div>
				@endif
				<div class="line line-dashed b-b line-lg pull-in"></div>
				<div class="form-group">
					<label class="col-sm-3 control-label">
						{{ trans('message.profile.tfa') }}:
					</label>
					<div class="col-sm-4">
						<label class="switch">
							<input type="checkbox" id="tfa_use" name="tfa_use" value="1" {{ isset($tfa_flag) && $tfa_flag == 1 || isset($qr_flag) && ($qr_flag == 1)? 'checked' : '' }}>
							<span></span>
						</label>
						<div id="tfa_auth_board">
							<div class="checkbox i-checks">
								<label>
									<input type="radio" name="tfa_auth" value="1" {{ isset($qr_flag) && ($qr_flag == 1) ? 'checked' : '' }}>
									<i></i>{{ trans('message.profile.tfa_qrcode') }}
								</label>
							</div>
							<div class="checkbox i-checks">
								<label>
									<input type="radio" name="tfa_auth" value="2" {{ isset($tfa_flag) && ($tfa_flag == 1) ? 'checked' : '' }}>
									<i></i>{{ trans('message.profile.tfa_smscode') }}
								</label>
							</div>
						</div>
					</div>
				</div>
				<div class="line line-dashed b-b line-lg pull-in"></div>
				@if (empty($usersinfo->identity_front) || empty($usersinfo->identity_end))
				<div class="line line-dashed b-b line-lg pull-in"></div>
				<div class="form-group">
					<label class="col-sm-6 col-sm-offset-2"><b>※&nbsp;{{ trans('message.register.identity_attach') }} &nbsp;&nbsp;(*.bmp, *.png, *.jpg, *.jpeg, *.pdf &nbsp;&nbsp;: 20M)</b></label><br/>
				</div>
				<div class="line line-dashed b-b line-lg pull-in"></div>
				<div class="form-group" data-provides="fileupload">
					<label class="col-sm-3 control-label"></label>
					@if(substr(strtolower($usersinfo->identity_front), -4) == '.pdf')
						<a href="<?php echo url('/', $parameters = array(), $secure = null); ?>/upload/identity/{{ $usersinfo->licensed == USER_LICENSE_UNCHECKED ? 'tmp/' : '' }}{{$usersinfo->identity_front}}" target="_blank">{{ trans('admin.edit.profile.identity_front') }}</a>
					@else
						<div class="fileupload-new thumbnail" style="display:inline-block;width:180px; height:180px; float:left;">
                            <?php
                                $identityFPath = ($usersinfo->licensed == USER_LICENSE_UNCHECKED) ? IDENTITY_UPLOAD_PATH . 'tmp' . DIRECTORY_SEPARATOR : IDENTITY_UPLOAD_PATH;
                                $identityFUrl = url('/', $parameters = array(), $secure = null) . '/upload/identity/';
                                $identityFUrl .= ($usersinfo->licensed == USER_LICENSE_UNCHECKED) ? 'tmp/' : '';
                                if ( !empty($usersinfo->identity_front) && file_exists($identityFPath . $usersinfo->identity_front) ) {
                            ?>
							<img src="<?php echo $identityFUrl . $usersinfo->identity_front ?>" alt="{{ trans('message.register.file_no') }}" />
							<?php } else { ?>
							{{ trans('message.register.file_no') }}
							<?php } ?>
						</div>
					@endif

					@if(substr(strtolower($usersinfo->identity_end), -4) == '.pdf')
						<a href="<?php echo url('/', $parameters = array(), $secure = null); ?>/upload/identity/{{ $usersinfo->licensed == USER_LICENSE_UNCHECKED ? 'tmp/' : '' }}{{$usersinfo->identity_end}}" target="_blank">{{ trans('admin.edit.profile.identity_end') }}</a>
					@else
						<div class="fileupload-new thumbnail" style="display:inline-block;width:180px; height:180px; float:left;">
                            <?php
                                $identityFPath = ($usersinfo->licensed == USER_LICENSE_UNCHECKED) ? IDENTITY_UPLOAD_PATH . 'tmp' . DIRECTORY_SEPARATOR : IDENTITY_UPLOAD_PATH;
                                $identityFUrl = url('/', $parameters = array(), $secure = null) . '/upload/identity/';
                                $identityFUrl .= ($usersinfo->licensed == USER_LICENSE_UNCHECKED) ? 'tmp/' : '';
                                if ( !empty($usersinfo->identity_end) && file_exists($identityFPath . $usersinfo->identity_end) ) {
                            ?>
							<img src="<?php echo $identityFUrl . $usersinfo->identity_end ?>" alt="{{ trans('message.register.file_no') }}" />
							<?php } else { ?>
							{{ trans('message.register.file_no') }}
							<?php } ?>
						</div>
					@endif					
				</div>
				<div class="form-group">
					<div class="col-sm-3 control-label"></div>
					<div class="col-sm-5">
						<div><font class="text-danger">{{ trans($errors->first('avatar'))}}</font></div>
						{{ trans('message.register.identity_front') }}&nbsp;:&nbsp;
						{{--				{{ $identity_front }}--}}
						<input type="file" name="avatar" size="30" accept=".bmp,.png,.jpg,.jpeg,.pdf" style="display:inline-block;"><br/><br/>
						<div><font class="text-danger">{{ trans($errors->first('avatar2'))}}</font></div>
						{{ trans('message.register.identity_end') }}&nbsp;:&nbsp;
						{{--				{{ $identity_end }}--}}
						<input type="file" name="avatar2" size="30" accept=".bmp,.png,.jpg,.jpeg,.pdf" style="display:inline-block;"><br><br>{{ trans('message.register.identity_companypaper') }}<br/><br>
						{{ trans('message.register.identity_license') }}<br><br>
						{{ trans('message.register.identity_by_postal') }}<br>{{ trans('message.register.identity_mailing') }}
						<input type="hidden" name="identity_front" value="{{--{{ $identity_front }}--}}">
						<input type="hidden" name="identity_end" value="{{--{{ $identity_end }}--}}">
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 col-sm-offset-3">
						<div class="checkbox i-checks">
							<label>
								<input type="checkbox" name="fax_or_post" {{ isset($fax_or_post) && $fax_or_post == 0 ? '' : 'checked' }} value="{{ $fax_or_post or 0}}" />
								<i></i>{{ trans('message.register.identity_copy_by_email') }}
							</label>
						</div>
					</div>
				</div>
				@endif
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-5">
						<div class="text-danger" id="message_errors_profile"></div>
						<button type="submit" class="btn btn-sm btn-primary">{{ trans('button.edit_save') }}</button>
					</div>
				</div>
			</form>
		</div>
		<div class="tab-pane wrapper-lg {{ $page=='bank' ? 'active' : '' }}" id="bank">
			@if ($page == 'bank' && count($errors) > 0)
				<div class="alert alert-danger">
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
            @elseif ($page == 'bank' && !empty($profile_save_success  = Session::pull('profile_save_success', '')))
                <div class="alert alert-success">
					<ul>
						<li>{{ $profile_save_success }}</li>
					</ul>
				</div>
            @endif
			<form class="form-horizontal" method="post" action="{{ url('/user/profile') }}">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<input type="hidden" name="page" value="bank">
				<div class="form-group">
					<label class="col-sm-3 control-label">{{ trans('message.register.bankname') }}:</label>
					<div class="col-sm-5">
						<font class="text-danger">{{ trans($errors->first('bankname')) }}</font>
						<input type="text" class="form-control" name="bankname" value="{{ $bankname or '' }}">
					</div>
				</div>
				<div class="line line-dashed b-b line-lg pull-in"></div>
				<div class="form-group">
					<label class="col-sm-3 control-label" >{{ trans('message.register.branchname') }}:</label>
					<div class="col-sm-5">
						<font class="text-danger">{{ trans($errors->first('branchname')) }}</font>
						<input type="text" class="form-control" name="branchname" value="{{ $branchname or '' }}">
					</div>
				</div>
				<div class="line line-dashed b-b line-lg pull-in"></div>
				<div class="form-group">
					<label class="col-sm-3 control-label">{{ trans('message.register.accounttype') }}:</label>
					<div class="col-sm-5">
						<font class="text-danger">{{ trans($errors->first('accounttype')) }}</font>
						<select class="form-control" id="accounttype" name="accounttype">
							{{--<option value="" disabled>{{ trans('message.register.please_select') }}</option>--}}
							<option value="1">{{ trans('message.register.accounttype_general') }}</option>
							<option value="2">{{ trans('message.register.accounttype_current') }}</option>
						</select>
					</div>
				</div>
				<div class="line line-dashed b-b line-lg pull-in"></div>
				<div class="form-group">
					<label class="col-sm-3 control-label">{{ trans('message.register.accountnumber') }}:</label>
					<div class="col-sm-5">
						<font class="text-danger">{{ trans($errors->first('accountnumber')) }}</font>
						<input type="text" class="form-control" name="accountnumber" value="{{ $accountnumber or ''}}">
					</div>
				</div>
				<div class="line line-dashed b-b line-lg pull-in"></div>
				<div class="form-group">
					<label class="col-sm-3 control-label">{{ trans('message.register.accountname') }}:</label>
					<div class="col-sm-5">
						<font class="text-danger">{{ trans($errors->first('accountname')) }}</font>
						<input type="text" class="form-control" name="accountname" value="{{ $accountname or '' }}">
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-5">
						<div class="text-danger" id="message_errors_bank"></div>
						<button type="submit" class="btn btn-sm btn-primary">{{ trans('button.edit_save') }}</button>
					</div>
				</div>
			</form>
		</div>
		<div class="tab-pane wrapper-lg {{ $page=='password' ? 'active' : '' }}" id="password">
			<form class="form-horizontal" method="post" action="{{ url('/user/profile') }}">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<input type="hidden" name="page" value="password">
				@if ($page == 'password' && count($errors) > 0)
					<div class="alert alert-danger">
						<ul>
							@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
                @elseif ($page == 'password' && !empty($profile_save_success = Session::pull('profile_save_success', '')))
                <div class="alert alert-success">
					<ul>
						<li>{{ $profile_save_success }}</li>
					</ul>
				</div>
                @endif
				<div class="form-group">
					<label class="col-sm-3 control-label">{{ trans('message.profile.old_password') }}:</label>
					<div class="col-sm-5">
						<font class="text-danger">{{ trans($errors->first('old_password')) }}</font>
						<input type="password" class="form-control" name="old_password">
					</div>
				</div>
				<div class="line line-dashed b-b line-lg pull-in"></div>
				<div class="form-group">
					<label class="col-sm-3 control-label">{{ trans('message.profile.new_password') }}:</label>
					<div class="col-sm-5">
						<font class="text-danger">{{ trans($errors->first('new_password')) }}</font>
						<input type="password" class="form-control" name="new_password">
					</div>
				</div>
				<div class="line line-dashed b-b line-lg pull-in"></div>
				<div class="form-group">
					<label class="col-sm-3 control-label">{{ trans('message.profile.new_password_confirm') }}:</label>
					<div class="col-sm-5">
						<input type="password" class="form-control" name="new_password_confirmation">
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-5">
						<div class="text-danger" id="message_errors_password"></div>
						<button type="submit" class="btn btn-sm btn-primary">{{ trans('button.edit_save') }}</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</section>
@stop

@section('footer')
<script src="{{ asset('js/ui/jquery.ui.core.js') }}"></script>
<script src="{{ asset('js/authy.js') }}"></script>
<script src="{{ asset('js/ui/jquery.ui.datepicker.js') }}"></script>
@if (Session::get('locale', 'ja') == 'ja' || Session::get('locale', 'ja') == 'cn')
<script src="{{ asset('js/ui/i18n/jquery.ui.datepicker-ja.js') }}"></script>
@endif

<script type="text/javascript">
$(function()
{
	setPreviousURL();
	/*$.get("http://ipinfo.io", function(res) {
	    //console.log(response.city);
	    $('[name=country]').val(res.city);
	}, "jsonp");*/
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
	if ('{{ $birthday }}' != '0000/00/00' && '{{ $birthday }}' != '') {
		$( "#birthday_datepicker" ).datepicker("setDate", new Date('{{ $birthday }}'));
		$( "#birthday" ).val('{{ $birthday }}');	
	} else {
		$( "#birthday_datepicker" ).datepicker("setDate", '');
	}
	$('#tfa_use').trigger('change');
});
$('[name=fax_or_post]').change(function() {
	if (!$(this).prop('checked')) {
		$(this).val(0);
	} else 	{
		$(this).val(1);
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
$('select#accounttype').prop('value', '{{ $accounttype or '' }}');
$('ul.nav-main li').removeClass('active');
$('ul.nav-main > li:eq(8)').addClass('active');
$('#tfa_use').change(function() {
	if ($(this).prop('checked')) {
		if ('{{ $qr_flag }}' == 1) {
			$('[name=tfa_auth][value="1"]').prop('checked', true);
		} else if ('{{ $tfa_flag }}' == 1) {
			$('[name=tfa_auth][value="2"]').prop('checked', true);
		} else {
			$('[name=tfa_auth][value="1"]').prop('checked', true);
		}
		$('#tfa_auth_board').fadeIn('slow');
	} else {
		$('#tfa_auth_board').fadeOut('fast');
	}
});
$('#reveal').hover(function() {
	$(this).css('color', '#177BBB');
}, function(){
	$(this).css('color', '#788288');
});
$('#req_sms').hover(function() {
	$(this).css('color', '#177BBB');
}, function(){
	$(this).css('color', '#788288');
});
$('#req_sms').click(function() {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.post('{{ url('user/sms-request') }}', function(msg) {
		if (msg.result == 'Success') {
			alert('{{ trans('message.tfa_auth.send_success') }}');
		} else if (msg.reason == 'PHONE_NOT_SET') {
			alert('{{ trans('message.tfa_auth.phone_required') }}');
		} else if (msg.reason == 'COUNTRY_NOT_SET') {
			alert('{{ trans('message.tfa_auth.country_required') }}');
		} else if (msg.reason == 'FAIL_TO_REGISTER') {
			alert('{{ trans('message.tfa_auth.sms_service_not_work') }}');
		} else {
			alert('{{ trans('message.tfa_auth.try_later') }}');
		}
	}, 'json');
});
$('#reveal').click(function() {
	var content = $(this).html();
	if (content == '{{ trans('message.profile.reveal') }}') {
		$(this).html('{{ trans('message.profile.hide') }}');
		$('#pwd_anony').css('display', 'none');
		if (!$('#pwd_guide').hasClass('text-danger')) {
			$('#pwd_guide').html('{{ trans('message.profile.pwd_guide') }}');
//			$('#req_sms').trigger('click');
		}
		$('#help_sms').fadeIn('fast');
		$('#pwd_guide').fadeIn('fast');
		$('#pwd').fadeIn('fast');
		$('#req_sms').fadeIn('fast');
	} else if (content == '{{ trans('message.profile.hide') }}') {
		$(this).html('{{ trans('message.profile.reveal') }}');
		$('#req_sms').css('display', 'none');
		$('#pwd_guide').css('display', 'none');
		$('#pwd').css('display', 'none');
		$('#pwd_anony').fadeIn('fast');
	}
});
$('#pwd').keydown(function(e) {
	if (e.keyCode == 13) {
		e.preventDefault();
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.post('{{ url('user/verify-sms') }}', {code: $(this).val()}, function(msg){
			console.log(msg);
			if (msg.result == 'Success') {
				$('#pwd_guide').addClass('text-danger');
				$('#pwd_guide').html('{{ trans('message.profile.pwd_warning2') }}');
				$('#pwd').val(msg.secret);
				$('#pwd').prop('readonly', true);
			} else {
				$('#pwd_guide').addClass('text-danger');
				$('#pwd_guide').html('{{ trans('message.profile.pwd_warning1') }}');
			}
		}, 'json');
	}
});
function getCountryState(isoCode) {
	var previousState = '{{ $usersinfo['state'] or '' }}';
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.getJSON("{{ url('user/country-state') }}" + "/" + isoCode, function(msg) {
		if (msg) {
			var data = [], state_html = "";
			data = Object.keys(msg).map(function(k){
				return msg[k];
			});
			data.forEach(function(d){
				if (previousState && previousState == d) {
					state_html += '<option selected value="' + d + '">' + d + '</option>';
				} else {
					state_html += '<option value="' + d + '">' + d + '</option>';
				}
			});
			state_html = '<option value="" disabled>{{ trans('message.register.please_select') }}</option>' + state_html;
			$('#state').html(state_html);
		}
	});
}
/*$('[name=telnum]').change(function(){
//	var regExp = /^\d{3}-\d{4}-\d{4}|\d{2}-\d{4}-\d{4}|\d{4}-\d{2}-\d{4}|\d{3}-\d{3}-\d{4}|\d{5}-\d{1}-\d{4}$/;
	var regExp = /^[0-9][0-9]*-?[0-9]*[0-9]$/;
	var telnum = $(this).val();
	if (!!telnum) {
		//telnum = telnum.replace(/-/gi, '');
		//telnum = telnum.substr(0, 3) + "-" + telnum.substr(3, 4) + "-" + telnum.substr(7, 4);
		if (!regExp.test(telnum)) {
			{{--@if ($locale == 'en')
				$('[name=telnum]').html('The telephone-Number format is invalid.');
			@elseif ($locale == 'ja')
				$('[name=telnum]').html('電話番号の形式を合わせてください。');
			@elseif ($locale == 'cn')
				$('[name=telnum]').html('电话号码格式无效。');
			@endif--}}
			$(this).val('');			
			return;
		}
		$(this).val(telnum);
		$('[name=telnum]').html('');
	}	
});*/
/*$('[name=zipcode]').change(function(){
	var regExp = /^\d{3}-\d{4}$/;
	var zipcode = $(this).val();
	if (!!zipcode) {
		zipcode = zipcode.replace(/-/gi, '');
		zipcode = zipcode.substr(0, 3) + "-" + zipcode.substr(3, 4);
		if (!regExp.test(zipcode)) {
			{{--@if ($locale == 'en')--}}
				{{--$('[name=zipcode]').html('The zipcode format is invalid.');--}}
			{{--@elseif ($locale == 'ja')--}}
				{{--$('[name=zipcode]').html('郵便番号の形式を合わせてください。');--}}
			{{--@elseif ($locale == 'cn')--}}
				{{--$('[name=zipcode]').html('邮政编码格式无效。');--}}
			{{--@endif--}}
			$(this).val('');
			return;
		}
		$(this).val(zipcode);
		$('[name=zipcode]').html('');
	}	
});*/

</script>
@stop