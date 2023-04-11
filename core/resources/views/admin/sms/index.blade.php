@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card mb-4">
            <div class="card-body">
                <form action="">
                    <div class="row align-items-center">
                        <div class="col-lg-2 form-group">
                            <label>@lang('Mobile Number')</label>
                            <input type="text" autocomplete="off" name="mobile_number" value="{{ request()->mobile_number ?? null }}" placeholder="@lang('Search with mobile number')" value="" class="form-control">
                        </div>
                        <div class="col-lg-2 form-group">
                            <label>@lang('Device')</label>
                            <select name="device_id" class="form-control" id="status">
                                <option value="" selected>@lang('All Device')</option>
                                @foreach ($allDevice as $device)
                                <option value="{{$device->id}}" data-sim='@json($device->sim)' >
                                    {{ __($device->device_name) }}-{{ __($device->device_model)}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 form-group">
                            <label>@lang('Status')</label>
                            <select name="status" class="form-control" id="status">
                                <option value="" selected>@lang('All')</option>
                                <option value="1">@lang('Delivered')</option>
                                <option value="2">@lang('Pending')</option>
                                <option value="3">@lang('Scheduled')</option>
                                <option value="4">@lang('Processing')</option>
                                <option value="9">@lang('Faild')</option>
                            </select>
                        </div>
                        <div class="col-lg-2 form-group">
                            <label>@lang('Date')</label>
                            <input name="date" value="{{ request()->date ?? null }}" class="form-control search-date" autocomplete="off" placeholder="@lang('Start Date-End Date')" type="text">
                        </div>
                        <div class="col-lg-2 form-group">
                            <label>@lang('Sms Type')</label>
                            <select name="sms_type" class="form-control">
                                <option value="" selected >@lang('All')</option>
                                <option value="1">@lang('Send')</option>
                                <option value="2">@lang('Receeived')</option>
                            </select>
                        </div>
                        <div class="col-lg-2 form-group">
                            <button class="btn btn--primary w-100 h-45 mt-4" type="submit">
                                <i class="fas fa-filter"></i>
                                @lang('Filter')
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
                <div class="table-responsive--md  table-responsive">
                    <table class="table table--light style--two" id="message-table">
                        <thead>
                            <tr>
                                <th>@lang('S.N.')</th>
                                <th>@lang('Device')</th>
                                <th>@lang('Mobile Number')</th>
                                <th>@lang('Sms')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Sms Type')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($messages as $message)
                            <tr data-id="{{ $message->id }}">
                                <td>{{ __($messages->firstItem()+$loop->index) }}</td>
                                <td>
                                    @if ($message->device)
                                    <span>
                                        {{__($message->device->device_name)}} - {{__($message->device->device_model)}}
                                    </span>
                                    <br>
                                    <span class="badge badge--success">{{__($message->device_slot_number)}}-{{__($message->device_slot_name)}}</span>
                                    @endif
                                </td>
                                <td>{{__(@$message->mobile_number)}}</td>
                                <td data-limit="@lang('Sms')">
                                    <span> {{ __(strLimit($message->message,50)) }}</span>
                                    @if (strlen($message->message) > 50)
                                    <span class="text--primary message" message="{{$message->message}}">
                                        @lang('Read More')
                                    </span>
                                    @endif
                                </td>
                                <td>
                                    @if($message->status==1)
                                    <span class="badge badge--success">
                                        @if ($message->sms_type==1)
                                        @lang('Delivered')
                                        @else
                                        @lang('Received')
                                        @endif
                                    </span>
                                    @elseif($message->status==2)
                                    <span class="badge badge--warning">@lang('Pending')</span>
                                    @elseif($message->status==3)
                                    <span class="badge badge--primary" data-bs-toggle="tooltip" title="{{" The SMS will be sent at ".showDateTime($message->schedule)}}">@lang('Scheduled')</span>
                                    @elseif($message->status==4)
                                    <span class="badge badge--primary">@lang('Processing')</span>
                                    @elseif($message->status==9)
                                    <span class="badge badge--danger">@lang('Faild') <a href="javascript:void(0)" class="fail-message text--danger" data-details='@json($message->failReason)'><i class="las la-info-circle"></i></a></span>
                                    @else
                                    <span class="badge badge--dark">@lang('Initial')</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($message->sms_type==1)
                                    <span class="badge badge--success">@lang('Send')</span>
                                    @else
                                    <span class="badge badge--primary">@lang('Received')</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($messages->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($messages) }}
            </div>
            @endif
        </div>
    </div>
</div>

<div class="modal fade" id="modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('Add New Device')</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<div id="fail-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Fail Reason')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>

@endsection

@push('breadcrumb-plugins')
<a href="{{ route('admin.sms.send') }}" class="btn btn-outline--primary"> <i class="las la-paper-plane"></i>
    @lang('Send Sms')
</a>
@endpush

@push('script-lib')
<script src="{{ asset('assets/admin/js/vendor/datepicker.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/vendor/datepicker.en.js') }}"></script>
<script src="{{ asset('assets/admin/js/vendor/pusher.min.js') }}"></script>
@endpush

@push('style-lib')
<link rel="stylesheet" href="{{ asset('assets/admin/css/vendor/datepicker.min.css') }}">
@endpush

@push('script')
<script src="{{ asset('assets/admin/js/broadcasting.js') }}"></script>

<script>
    "use strict";
    (function ($) {
        let modal = $("#modal");

        $('#message-table').on('click','.message',function (e) {
            let message = $(this).attr('message');
            modal.find('.modal-title').text("@lang('Sms')")
            $(modal).find('.modal-body').html(`
                <p>${message}</p>
            `)
            modal.modal('show')
        });

        let date = new Date(new Date().toLocaleString("en-US", {
            timeZone: "{{ date_default_timezone_get() }}"
        }));

        $('.search-date').datepicker({
            language: 'en',
            dateFormat: 'yyyy-mm-dd',
            range: true,
            maxDate: date
        });

    })(jQuery);


    //message received channel
    pusher.connection.bind('connected', () => {
        const SOCKET_ID = pusher.connection.socket_id;
        const CHANNEL_NAME = "private-message-received";
        pusher.config.authEndpoint = makeAuthEndPointForPusher(SOCKET_ID, CHANNEL_NAME);
        let channel = pusher.subscribe(CHANNEL_NAME);
        channel.bind('pusher:subscription_succeeded', function () {
            channel.bind('message-received', function (data) {
                let message = data.data.original.data.message;
                messageReceived(message);
            })
        });
    });


    function messageReceived(message) {
        let table = document.querySelector('#message-table');
        let tbody = document.querySelector('tbody');

        let loadingHtml = ` <tr>
        <td colspan="100%" class="text-center">
            <div class="spinner-border" role="status"></div>
        </td>
        </tr>`;

        let tbodyHtml = tbody.innerHTML;
        tbody.innerHTML = loadingHtml + tbodyHtml;
        let sms=message.message.length > 50  ?  `${message.message.substring(0,50)}
        <span class="text--primary message" message="${message.message}">
            @lang('Read More')
         </span>` : message.message;
        let newMessage = `
        <tr data-id=${message.id}>
            <td data-label="@lang('S.N.')">1</td>
            <td data-label="@lang('Device')">
                <span>${message.device_name}</span> <br>
                <span class="badge badge--success">${message.device_slot_number}-${message.device_slot_name}</span>
            </td>
            <td data-label="@lang('Mobile Number')">${message.mobile_number}</td>
            <td data-limit="@lang('Sms')">
               ${sms}
            </td>
            <td data-label="@lang('Status')">
                <span class="badge badge--success">@lang('Received')</span>
            </td>

            <td data-label="@lang('Sms request')">
                <span class="badge badge--primary">@lang('Received')</span>
            </td>
        </tr>
        `

        setTimeout(() => {
            tbody.innerHTML = newMessage + tbodyHtml;
            let row = table.querySelectorAll('tbody tr');
            for (let i = 0; i < row.length; i++) {
                let td = row[i].querySelector('td');
                if (td) {
                    td.innerText = i + 1;
                }
                if (!row[i].getAttribute('data-id')) {
                    row[i].remove();
                }
            }
        }, 1000);
    }

    @if (request()->sms_type)
        $('select[name=sms_type]').val("{{request()->sms_type}}")
    @endif

    @if (request()->device_id)
        $('select[name=device_id]').val("{{request()->device_id}}")
    @endif

    $('.fail-message').on('click',function(e){
       let modal=$("#fail-modal");
       let details=$(this).data('details');
       let html="";
        if(details){
             html=`<p class="mb-2 text-center fw-bold">${details.error_keyword}</p> <p class="alert alert-danger fw-bold p-2 text-dark">${details.error_explanation}</p>`

        }else{
             html=`<p class="mb-2 text-center fw-bold">Unknown error </p>`
        }
       modal.find('.modal-body').html(html)
       modal.modal('show')
    });

</script>
@endpush



@push('style')
<style>
    .instruction {
        list-style: auto;
        padding: 0px 40px;
    }

    .message {
        cursor: pointer;
        font-size: 12px !important;
    }

    .spinner-border {
        width: 1.5rem;
        height: 1.5rem;
    }
    .fail-message{
        cursor: pointer;
    }
</style>
@endpush
