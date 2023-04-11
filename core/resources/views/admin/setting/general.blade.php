@extends('admin.layouts.app')
@section('panel')
<div class="row mb-none-30">
    <div class="col-lg-12 col-md-12 mb-30">
        <div class="card">
            <div class="card-body">
                <form action="" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label> @lang('Site Title')</label>
                                <input class="form-control" type="text" name="site_name" required
                                    value="{{$general->site_name}}">
                            </div>
                        </div>
                        <div class="form-group col-sm-4">
                            <label> @lang('Timezone')</label>
                            <select class="select2-basic" name="timezone">
                                @foreach($timezones as $timezone)
                                <option value="'{{ @$timezone}}'">{{ __($timezone) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4 form-group">
                            <div class="form-group">
                                <label> @lang('Batch Prefix')</label>
                                <input class="form-control" type="text" name="batch_id_prefix" required
                                    value="{{$general->batch_id_prefix}}">
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label>@lang('Email Notification')</label>
                            <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success"
                                data-offstyle="-danger" data-bs-toggle="toggle" data-height="50"
                                data-on="@lang('Enable')" data-off="@lang('Disable')" name="en" @if($general->en)
                            checked @endif>
                        </div>
                        <div class="form-group col-sm-6">
                            <label>@lang('SMS Notification')</label>
                            <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success"
                                data-offstyle="-danger" data-bs-toggle="toggle" data-height="50"
                                data-on="@lang('Enable')" data-off="@lang('Disable')" name="sn" @if($general->sn)
                            checked @endif>
                        </div>
                    </div>
                    <div class="border-line-area mb-3 mt-3">
                        <h4 class="border-line-title">@lang('Pusher Setting')</h4>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label> @lang('Pusher App ID')</label>
                            <input type="text" class="form-control" placeholder="@lang('Pusher App ID')" name="pusher_app_id" value="{{ config('app.PUSHER_APP_ID')}}" required>
                        </div>
                        <div class="form-group col-sm-6">
                            <label> @lang('Pusher App Key')</label>
                            <input type="text" class="form-control" placeholder="@lang('Pusher App Key')"
                                name="pusher_app_key" value="{{config('app.PUSHER_APP_KEY')}}" required>
                        </div>
                        <div class="form-group col-sm-6">
                            <label> @lang('Pusher App Secret')</label>
                            <input type="text" class="form-control" placeholder="@lang('Pusher App Secret')"
                                name="pusher_app_secret" value="{{config('app.PUSHER_APP_SECRET')}}" required>
                        </div>
                        <div class="form-group col-sm-6">
                            <label> @lang('Pusher App Cluster')</label>
                            <input type="text" class="form-control" placeholder="@lang('Pusher App Cluster')"
                                name="pusher_app_cluster" value="{{config('app.PUSHER_APP_CLUSTER')}}" required>
                        </div>

                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script-lib')
<script src="{{ asset('assets/admin/js/spectrum.js') }}"></script>
@endpush

@push('style-lib')
<link rel="stylesheet" href="{{ asset('assets/admin/css/spectrum.css') }}">
@endpush

@push('script')
<script>
    (function ($) {
        "use strict";

        $('.colorPicker').spectrum({
            color: $(this).data('color'),
            change: function (color) {
                $(this).parent().siblings('.colorCode').val(color.toHexString().replace(/^#?/, ''));
            }
        });

        $('.colorCode').on('input', function () {
            var clr = $(this).val();
            $(this).parents('.input-group').find('.colorPicker').spectrum({
                color: clr,
            });
        });

        $('.select2-basic').select2({
            dropdownParent: $('.card-body')
        });

        $('select[name=timezone]').val("'{{ config('app.timezone') }}'").select2();
        $('.select2-basic').select2({
            dropdownParent: $('.card-body')
        });
        
    })(jQuery);

</script>
@endpush


@push('style')
    <style>
        .border-line-area {
            position: relative;
            text-align: center;
            z-index: 1;
        }

        .border-line-area::before {
            position: absolute;
            content: '';
            top: 50%;
            left: 0;
            width: 100%;
            height: 1px;
            background-color: #e5e5e5;
            z-index: -1;
        }

        .border-line-title {
            display: inline-block;
            padding: 3px 10px;
            background-color: #fff;
        }
        .select2-container--default .select2-selection--single{
            height: 45px !important;
            border-color: #dddddd;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 45px
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow b{
            top: 60% !important;
        }
    </style>
@endpush
