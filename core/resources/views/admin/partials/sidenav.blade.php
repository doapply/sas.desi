<div class="sidebar bg--dark">
    <button class="res-sidebar-close-btn"><i class="las la-times"></i></button>
    <div class="sidebar__inner">
        <div class="sidebar__logo">
            <a href="{{route('admin.dashboard')}}" class="sidebar__main-logo"><img
                    src="{{getImage(getFilePath('logoIcon') .'/logo.png')}}" alt="@lang('image')"></a>
        </div>

        <div class="sidebar__menu-wrapper" id="sidebar__menuWrapper">
            <ul class="sidebar__menu">
                <li class="sidebar-menu-item {{ menuActive('admin.dashboard') }}">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">
                        <i class="menu-icon las la-tachometer-alt"></i>
                        <span class="menu-title">@lang('Dashboard')</span>
                    </a>
                </li>
                <li class="sidebar-menu-item {{ menuActive('admin.contact.index') }}">
                    <a href="{{ route('admin.contact.index') }}" class="nav-link">
                        <i class="menu-icon las la-id-card"></i>
                        <span class="menu-title">@lang('Manage Contact')</span>
                    </a>
                </li>
                <li class="sidebar-menu-item {{ menuActive('admin.group*') }}">
                    <a href="{{ route('admin.group.index') }}" class="nav-link">
                        <i class="menu-icon las la-users"></i>
                        <span class="menu-title">@lang('Manage Group')</span>
                    </a>
                </li>
                
                <li class="sidebar-menu-item {{ menuActive('admin.template.index') }} ">
                    <a href="{{ route('admin.template.index') }}" class="nav-link ">
                        <i class="menu-icon las la-align-justify"></i>
                        <span class="menu-title">@lang('Manage Template')</span>
                    </a>
                </li>
                
                
                <li class="sidebar-menu-item {{menuActive('admin.device*')}}">
                    <a href="{{route('admin.device.index')}}" class="nav-link">
                        <i class="menu-icon las la-mobile"></i>
                        <span class="menu-title">@lang('Manage Device')</span>
                    </a>
                </li>
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{ menuActive('admin.sms*', 3) }}">
                        <i class="menu-icon la la-comment"></i>
                        <span class="menu-title">@lang('Manage SMS')</span>
                    </a>
                    <div class="sidebar-submenu {{ menuActive('admin.sms*', 2) }}">
                        <ul>
                            <li class="sidebar-menu-item {{ menuActive('admin.sms.send') }}">
                                <a href="{{ route('admin.sms.send') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Send SMS')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('admin.sms.index') }}">
                                <a href="{{ route('admin.sms.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('SMS History')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="sidebar-menu-item {{ menuActive('admin.batch.index') }}">
                    <a href="{{ route('admin.batch.index') }}" class="nav-link ">
                        <i class="menu-icon lab la-battle-net"></i>
                        <span class="menu-title">@lang('Manage Batch')</span>
                    </a>
                </li>
                <li class="sidebar-menu-item {{ menuActive('admin.developer.api.docs') }} ">
                    <a href="{{ route('admin.developer.api.docs') }}" class="nav-link">
                        <i class="menu-icon las la-code"></i>
                        <span class="menu-title">@lang('Developer Tools')</span>
                    </a>
                </li>
                <li class="sidebar__menu-header">@lang('General Setting')</li>
                <li class="sidebar-menu-item {{ menuActive('admin.setting.index') }}">
                    <a href="{{ route('admin.setting.index') }}" class="nav-link">
                        <i class="menu-icon las la-life-ring"></i>
                        <span class="menu-title">@lang('General Setting')</span>
                    </a>
                </li>
                <li class="sidebar-menu-item {{ menuActive('admin.setting.logo.icon') }}">
                    <a href="{{ route('admin.setting.logo.icon') }}" class="nav-link">
                        <i class="menu-icon las la-images"></i>
                        <span class="menu-title">@lang('Logo & Favicon')</span>
                    </a>
                </li>
                <li class="sidebar-menu-item {{ menuActive('admin.extensions.index') }}">
                    <a href="{{ route('admin.extensions.index') }}" class="nav-link">
                        <i class="menu-icon las la-cogs"></i>
                        <span class="menu-title">@lang('Extensions')</span>
                    </a>
                </li>
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('admin.setting.notification*',3)}}">
                        <i class="menu-icon las la-bell"></i>
                        <span class="menu-title">@lang('Notification Setting')</span>
                    </a>
                    <div class="sidebar-submenu {{menuActive('admin.setting.notification*',2)}} ">
                        <ul>
                            <li class="sidebar-menu-item {{menuActive('admin.setting.notification.email')}} ">
                                <a href="{{route('admin.setting.notification.email')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Email Setting')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('admin.setting.notification.global')}} ">
                                <a href="{{route('admin.setting.notification.global')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Global Template')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{menuActive('admin.setting.notification.templates')}} ">
                                <a href="{{route('admin.setting.notification.templates')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Notification Templates')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>


                <li class="sidebar__menu-header">@lang('Extra')</li>
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{ menuActive('admin.system*', 3) }}">
                        <i class="menu-icon la la-server"></i>
                        <span class="menu-title">@lang('System')</span>
                    </a>
                    <div class="sidebar-submenu {{ menuActive('admin.system*', 2) }}">
                        <ul>
                            <li class="sidebar-menu-item {{ menuActive('admin.system.info') }}">
                                <a href="{{ route('admin.system.info') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Application')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('admin.system.server.info') }}">
                                <a href="{{ route('admin.system.server.info') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Server')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('admin.system.optimize') }}">
                                <a href="{{ route('admin.system.optimize') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Cache')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="sidebar-menu-item {{ menuActive('admin.request.report') }}">
                    <a href="{{ route('admin.request.report') }}" class="nav-link"
                        data-default-url="{{ route('admin.request.report') }}">
                        <i class="menu-icon las la-bug"></i>
                        <span class="menu-title">@lang('Report & Request') </span>
                    </a>
                </li>
            </ul>
            <div class="text-center mb-3 text-uppercase">
                <span class="text--primary">{{__(systemDetails()['name'])}}</span>
                <span class="text--success">@lang('V'){{systemDetails()['version']}} </span>
            </div>
        </div>
    </div>
</div>
<!-- sidebar end -->

@push('script')
<script>
    if ($('li').hasClass('active')) {
        $('#sidebar__menuWrapper').animate({
            scrollTop: eval($(".active").offset().top - 320)
        }, 500);
    }
</script>
@endpush
