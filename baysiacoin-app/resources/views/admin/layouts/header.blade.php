<!-- variable-->
<div id="header">
  <div id="header-content">
      @unless ($login_user->hasRole(ROLE_ADMIN2))
        <div id="header-logo"><a href="/"></a></div>
        <ul class="sf-menu" id="main-menu">
            <li><a href="{{ url('/user/profile') }}"><i class="icon-home icon-white"></i></a></li>
            <li>
                <a href="#">{{ trans('admin.user.manage') }}</a>
                <ul>
                    <li>
                        <a href="{{ url('/manage/root/user') }}">{{ trans('admin.user.menu_user') }}</a>
                    </li>

                    <li>
                        <a href="{{ url('/manage/root/history/fund') }}">{{ trans('admin.history.fund.title') }}</a>
                    </li>
                    <li>
                        <a href="{{ url('/manage/root/history/withdraw') }}">{{ trans('admin.history.withdraw.title') }}</a>
                    </li>
                    @if (isset($login_user) && $login_user->hasRole(ROLE_ADMIN))
                        <li>
                            <a href="{{ url('/manage/root/history/alt_coin') }}">{{ trans('admin.history.coins.title') }}</a>
                        </li>
                        <li>
                            <a href="{{ url('/manage/root/history/tx') }}">{{ trans('admin.history.tx.title') }}</a>
                        </li>
                        <li>
                            <a href="{{ url('/manage/root/history/pending_orders') }}">{{ trans('admin.offers.title') }}</a>
                        </li>
                    @endif
                </ul>
            </li>
        </ul>
        @if (!empty($new_count))
            <ul class="sf-menu" id="main-menu" style="float:right;">
                <li>
                    <div style="position:absolute;background: none repeat scroll 0% 0% #FEA223;padding:1px 5px;top:-1px;right:1px;border-radius:5px;">
                        {{ $new_count or 0 }}
                    </div>
                    <a href="{{ url('/manage/root/user/new') }}" style="position:relative;">
                        <i class="fa fa-bell"></i>
                    </a>
                </li>
            </ul>
        @endif
    @else
        <div id="header-logo"><a href="/"></a></div>
        <ul class="sf-menu" id="main-menu" style="height: 40px;"></ul>
    @endunless
  </div>
</div>