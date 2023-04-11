@extends('admin.layouts.app')
@section('panel')
@if(@json_decode($general->system_info)->version > systemDetails()['version'])
<div class="row">
    <div class="col-md-12">
        <div class="card text-white bg-warning mb-3">
            <div class="card-header">
                <h3 class="card-title"> @lang('New Version Available')
                    <button class="btn btn--dark float-end">@lang('Version') {{json_decode($general->system_info)->version}}</button>
                </h3>
            </div>
            <div class="card-body">
                <h5 class="card-title text-dark">@lang('What is the Update ?')</h5>
                <p>
                <pre class="f-size--24">{{json_decode($general->system_info)->details}}</pre>
                </p>
            </div>
        </div>
    </div>
</div>
@endif
@if(@json_decode($general->system_info)->message)
<div class="row">
    @foreach(json_decode($general->system_info)->message as $msg)
    <div class="col-md-12">
        <div class="alert border border--primary" role="alert">
            <div class="alert__icon bg--primary"><i class="far fa-bell"></i></div>
            <p class="alert__message">@php echo $msg; @endphp</p>
            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
    </div>
    @endforeach
</div>
@endif

@if ($general->cron_error_message != null)
<div class="row gy-4">
    <div class="col-lg-12">
        <div class="alert alert-warning p-3">
           <p class="fw-bold">
          {{ __($general->cron_error_message) }}
           </p>
        </div>
    </div>
</div>
@endif

<div class="row gy-4">
    <div class="col-xxl-3 col-sm-6">
        <div class="card bg--primary has-link overflow-hidden box--shadow2">
            <a href="{{ route('admin.sms.index') }}" class="item-link"></a>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-4">
                        <i class="las la-envelope f-size--56"></i>
                    </div>
                    <div class="col-8 text-end">
                        <span class="text-white text--small">@lang('Total SMS')</span>
                        <h2 class="text-white">{{ __($sms['total']) }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-sm-6">
        <div class="card bg--success has-link box--shadow2">
            <a href="{{ route('admin.sms.index') }}?status=1" class="item-link"></a>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-4">
                        <i class="las la-comments f-size--56"></i>
                    </div>
                    <div class="col-8 text-end">
                        <span class="text-white text--small">@lang('Total Delived SMS')</span>
                        <h2 class="text-white">{{ __($sms['delivered']) }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-sm-6">
        <div class="card bg--warning  has-link box--shadow2">
            <a href="{{ route('admin.sms.index') }}?status=2" class="item-link"></a>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-4">
                        <i class="las la-envelope-open-text f-size--56"></i>
                    </div>
                    <div class="col-8 text-end">
                        <span class="text-white text--small">@lang('Total Pending SMS')</span>
                        <h2 class="text-white">{{ __($sms['pending']) }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-sm-6">
        <div class="card bg--17 has-link box--shadow2">
            <a href="{{ route('admin.sms.index') }}?status=3" class="item-link"></a>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-4">
                        <i class="las la-calendar f-size--56"></i>
                    </div>
                    <div class="col-8 text-end">
                        <span class="text-white text--small">@lang('Total Schedule SMS')</span>
                        <h2 class="text-white">{{ __($sms['schedule']) }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-sm-6">
        <div class="card bg---18 has-link box--shadow2">
            <a href="{{ route('admin.sms.index') }}?status=4" class="item-link"></a>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-4">
                        <i class="las la-comment-dots f-size--56"></i>
                    </div>
                    <div class="col-8 text-end">
                        <span class="text-white text--small">@lang('Total Processing SMS')</span>
                        <h2 class="text-white">{{ __($sms['processing']) }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-sm-6">
        <div class="card bg--danger has-link overflow-hidden box--shadow2">
            <a href="{{ route('admin.sms.index') }}?status=9" class="item-link"></a>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-4">
                        <i class="las la-comment-slash f-size--56"></i>
                    </div>
                    <div class="col-8 text-end">
                        <span class="text-white text--small">@lang('Total Failed SMS')</span>
                        <h2 class="text-white">{{ __($sms['failed']) }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-sm-6">
        <div class="card bg--custom has-link box--shadow2">
            <a href="{{ route('admin.sms.index') }}?sms_type=1" class="item-link"></a>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-4">
                        <i class="las la-share-square f-size--56"></i>
                    </div>
                    <div class="col-8 text-end">
                        <span class="text-white text--small">@lang('Total Sent SMS')</span>
                        <h2 class="text-white">{{ __($sms['total_send']) }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-sm-6">
        <div class="card bg--3 has-link box--shadow2">
            <a href="{{ route('admin.sms.index') }}?sms_type=2" class="item-link"></a>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-4">
                        <i class="las la-sms f-size--56"></i>
                    </div>
                    <div class="col-8 text-end">
                        <span class="text-white text--small">@lang('Total Received SMS')</span>
                        <h2 class="text-white">{{ __($sms['total_received']) }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row gy-4 mt-2">
    <div class="col-xxl-3 col-sm-6">
        <div class="widget-two box--shadow2 b-radius--5 bg--white">
            <i class="las la-address-card overlay-icon text--primary"></i>
            <div class="widget-two__icon b-radius--5 border border--primary text--primary">
                <i class="las la-address-card"></i>
            </div>
            <div class="widget-two__content">
                <h3>{{ __($contact['total']) }}</h3>
                <p>@lang('Total Contact')</p>
            </div>
            <a href="{{ route('admin.contact.index') }}" class="widget-two__btn border border--primary btn-outline--primary btn-sm">@lang('View All')</a>
        </div>
    </div>
    <div class="col-xxl-3 col-sm-6">
        <div class="widget-two box--shadow2 b-radius--5 bg--white">
            <i class="las la-user-check overlay-icon text--success"></i>
            <div class="widget-two__icon b-radius--5 border border--success text--success">
                <i class="las la-user-check"></i>
            </div>
            <div class="widget-two__content">
                <h3>{{ __($contact['active']) }}</h3>
                <p>@lang('Active Contact')</p>
            </div>
            <a href="{{ route('admin.contact.index') }}?status=1" class="widget-two__btn border border--success btn-outline--success btn-sm">@lang('View All')</a>
        </div>
    </div>
    <div class="col-xxl-3 col-sm-6">
        <div class="widget-two box--shadow2 b-radius--5 bg--white">
            <i class="las la-user-slash overlay-icon text--danger"></i>
            <div class="widget-two__icon b-radius--5 border border--danger text--danger">
                <i class="las la-user-slash"></i>
            </div>
            <div class="widget-two__content">
                <h3>{{ __($contact['banned']) }}</h3>
                <p>@lang('Inactive Contact')</p>
            </div>
            <a href="{{ route('admin.contact.index') }}?status=0" class="widget-two__btn border border--danger btn-outline--danger btn-sm">@lang('View All')</a>
        </div>
    </div>
    <div class="col-xxl-3 col-sm-6">
        <div class="widget-two box--shadow2 b-radius--5 bg--white">
            <i class="las la-align-justify overlay-icon text--primary"></i>
            <div class="widget-two__icon b-radius--5 border border--primary text--primary">
                <i class="las la-align-justify"></i>
            </div>
            <div class="widget-two__content">
                <h3>{{ __($template['total']) }}</h3>
                <p>@lang('Total Template')</p>
            </div>
            <a href="{{ route('admin.template.index') }}" class="widget-two__btn border border--primary btn-outline--primary btn-sm">@lang('View All')</a>
        </div>
    </div>
</div>

<div class="row gy-4 mt-2">
    <div class="col-xxl-3 col-sm-6">
        <div class="widget-two style--two box--shadow2 b-radius--5 bg--primary">
            <i class="las la-mobile overlay-icon text--white"></i>
            <div class="widget-two__icon b-radius--5 bg--primary">
                <i class="las la-mobile"></i>
            </div>
            <div class="widget-two__content">
                <h3 class="text-white">{{ __($device['total']) }}</h3>
                <p class="text-white">@lang('Total Device')</p>
            </div>
            <a href="{{route('admin.device.index')}}" class="widget-two__btn">@lang('View All')</a>
        </div>
    </div>
    <div class="col-xxl-3 col-sm-6">
        <div class="widget-two style--two box--shadow2 b-radius--5 bg--success-2">
            <i class="las la-sd-card overlay-icon text--white"></i>
            <div class="widget-two__icon b-radius--5 bg--success">
                <i class="las la-sd-card"></i>
            </div>
            <div class="widget-two__content">
                <h3 class="text-white">{{ __($device['connected']) }}</h3>
                <p class="text-white">@lang('Total Conncted Device')</p>
            </div>
            <a href="{{route('admin.device.index')}}?status=1" class="widget-two__btn btn-sm">@lang('View All')</a>
        </div>
    </div>
    <div class="col-xxl-3 col-sm-6">
        <div class="widget-two style--two box--shadow2 b-radius--5 bg--warning-2">
            <i class="las la-database overlay-icon text--white"></i>
            <div class="widget-two__icon b-radius--5 bg--warning">
                <i class="las la-database"></i>
            </div>
            <div class="widget-two__content">
                <h3 class="text-white">{{ __($device['disconnected']) }}</h3>
                <p class="text-white">@lang('Disconncted Device')</p>
            </div>
            <a href="{{route('admin.device.index')}}?status=0" class="widget-two__btn btn-sm">@lang('View All')</a>
        </div>
    </div>
    <div class="col-xxl-3 col-sm-6">
        <div class="widget-two style--two box--shadow2 b-radius--5 bg---success-3">
            <i class="las la-list-alt overlay-icon text--white"></i>
            <div class="widget-two__icon b-radius--5 bg--success">
                <i class="las la-list-alt"></i>
            </div>
            <div class="widget-two__content">
                <h3 class="text-white">{{ __($template['active']) }}</h3>
                <p class="text-white">@lang('Active Templates')</p>
            </div>
            <a href="{{route('admin.template.index')}}?status=1" class="widget-two__btn btn-sm">@lang('View All')</a>
        </div>
    </div>
</div>

<div class="row gy-4 mt-2">
    <div class="col-xxl-3 col-sm-6">
        <div class="widget-two box--shadow2 b-radius--5 bg--white">
            <i class="las la-users overlay-icon text--primary"></i>
            <div class="widget-two__icon b-radius--5 border border--primary text--primary">
                <i class="las la-users"></i>
            </div>
            <div class="widget-two__content">
                <h3>{{ __($group['total']) }}</h3>
                <p>@lang('Total Group')</p>
            </div>
            <a href="{{route('admin.group.index') }}" class="widget-two__btn border border--primary btn-outline--primary btn-sm">@lang('View All')</a>
        </div>
    </div>
    <div class="col-xxl-3 col-sm-6">
        <div class="widget-two box--shadow2 b-radius--5 bg--white">
            <i class="las la-users-cog overlay-icon text--success"></i>
            <div class="widget-two__icon b-radius--5 border border--success text--success">
                <i class="las la-users-cog"></i>
            </div>
            <div class="widget-two__content">
                <h3>{{ __($group['active']) }}</h3>
                <p>@lang('Total Active Group')</p>
            </div>
            <a href="{{ route('admin.group.index') }}?status=1" class="widget-two__btn border border--success btn-outline--success btn-sm">@lang('View All')</a>
        </div>
    </div>
    <div class="col-xxl-3 col-sm-6">
        <div class="widget-two box--shadow2 b-radius--5 bg--white">
            <i class="las la-times-circle overlay-icon text--danger"></i>
            <div class="widget-two__icon b-radius--5 border border--danger text--danger">
                <i class="las la-times-circle"></i>
            </div>
            <div class="widget-two__content">
                <h3>{{ __($group['inactive']) }}</h3>
                <p>@lang('Total Inactive Group')</p>
            </div>
            <a href="{{ route('admin.group.index') }}?status=0" class="widget-two__btn border border--danger btn-outline--danger btn-sm">@lang('View All')</a>
        </div>
    </div>
    <div class="col-xxl-3 col-sm-6">
        <div class="widget-two box--shadow2 b-radius--5 bg--white">
            <i class="las la-list-ul overlay-icon text--danger"></i>
            <div class="widget-two__icon b-radius--5 border border--danger text--danger">
                <i class="las la-list-ul"></i>
            </div>
            <div class="widget-two__content">
                <h3>{{ __($template['inactive']) }}</h3>
                <p>@lang('Inactive Template')</p>
            </div>
            <a href="{{ route('admin.template.index') }}?status=0" class="widget-two__btn border border--danger btn-outline--danger btn-sm">@lang('View All')</a>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="cronModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">@lang('Cron Job Setting Instruction')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 form-group">
                        <p class="cron-p-style cron-p-style alert-info text--dark p-3">
                            @lang('To automatic send SMS, you need to set the cron job and make sure the job is running properly. Set the cron time as minimum as possible. Once per minute is ideal.')
                        </p>
                    </div>
                    <div class="col-md-12 form-group">
                        <label>@lang('Send SMS')</label>
                        <div class="input-group">
                            <input type="text" class="form-control form-control-lg copyText" value="curl -s {{route('cron.send.sms')}}" readonly>
                            <button class="input-group-text btn--primary copyBtn border-0"> @lang('COPY')</button>
                        </div>
                    </div>
                    <div class="col-md-12 form-group">
                        <label>@lang('Resend SMS')</label>
                        <div class="input-group">
                            <input type="text" class="form-control form-control-lg copyText" value="curl -s {{ route('cron.resend.sms') }}" readonly>
                            <button class="input-group-text btn--primary copyBtn border-0"> @lang('COPY')</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


@push('breadcrumb-plugins')
@if($general->last_cron!=null)
    <span class="text--info">@lang('Last Cron Run:')<strong>{{diffForHumans($general->last_cron)}}</strong></span>
@endif
@endpush


@push('script')
    <script>
        'use strict';
        (function($) {

            $('.copyBtn').on('click', function() {
                var copyText = $(this).siblings('.copyText')[0];
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                document.execCommand("copy");
                copyText.blur();
                $(this).addClass('copied');
                setTimeout(() => {
                    $(this).removeClass('copied');
                }, 1500);
            });
            @if (Carbon\Carbon::parse($general->last_cron)->diffInMinutes() > 10)
                $(window).on('load', function(e) {
                    $("#cronModal").modal('show');
                });
            @endif

        })(jQuery);
    </script>
@endpush


@push('style')
<style>
    .bg--custom {
        background: #194b7a !important;
    }
    .bg---18 {
        background-color: #8a3233 !important;
    }

    .bg--success-2 {
        background-color: #136c3b !important;
    }

    .bg--warning-2 {
        background-color: #d27216 !important;
    }

    .bg---success-3 {
        background-color: #2a995c !important;
    }
</style>
@endpush
