@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
                <div class="table-responsive--md  table-responsive" id="device-table">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('S.N.')</th>
                                <th>@lang('Name')</th>
                                <th>@lang('Model')</th>
                                <th>@lang('Device ID')</th>
                                <th>@lang('Android Verison')</th>
                                <th>@lang('App Version')</th>
                                <th>@lang('Status')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allDevice as $device)
                            <tr id="device-{{$device->device_id}}">
                                <td>{{ __($allDevice->firstItem()+$loop->index) }}</td>
                                <td>
                                    <strong>{{ __($device->device_name) }}</strong>
                                </td>
                                <td>{{__(@$device->device_model)}}</td>
                                <td>{{__(@$device->device_id)}}</td>
                                <td>{{__(@$device->android_version)}}</td>
                                <td>{{__(@$device->app_version)}}</td>
                                <td class="status">
                                    @if ($device->status)
                                    <span class="badge badge--success">@lang('Connected')</span>
                                    @else
                                    <span class="badge badge--danger">@lang('Disconnected')</span>
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
            @if ($allDevice->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($allDevice) }}
            </div>
            @endif
        </div>
    </div>
</div>




<div class="modal fade" id="modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ModalTitle">@lang('ADD YOUR DEVICE')</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <div class="modal-body p-0">
                <ul class="list-group mb-3 list-group-flush list-group-numbered mt-0">
                    <li class="list-group-item">@lang('Install our SMSLab android app on your mobile device.')</li>
                    <li class="list-group-item">@lang('Scan the below QR code to connect your device.')</li>
                </ul>
                <div class="text-center pb-3">
                    <img src="{{ $qrCodeImgSrc }}" class="b--2 border--primary" alt="">
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('custom-loader')
<div id="custom-loader" class="d-none">
    <span class="spinner-grow bg--primary"> </span>
    <span class="spinner-grow bg--primary ms-2"> </span>
</div>
@endpush

@push('breadcrumb-plugins')
<button type="button" class="btn btn-outline--primary btn-sm addBtn">
    <i class="las la-plus"></i> @lang('Add Device')
</button>
@endpush
@push('script')

<script src="{{ asset('assets/admin/js/vendor/pusher.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/broadcasting.js') }}?v8"></script>

<script>
    "use strict";

    (function ($) {
        $('.addBtn').on('click', function (e) {
            $("#modal").modal('show');
        });
    })(jQuery);


    const pusherConnection = (eventName) => {
        pusher.connection.bind('connected', () => {
            const SOCKET_ID = pusher.connection.socket_id;
            const CHANNEL_NAME = `private-${eventName}`;
            pusher.config.authEndpoint = makeAuthEndPointForPusher(SOCKET_ID, CHANNEL_NAME);
            let channel = pusher.subscribe(CHANNEL_NAME);
            channel.bind('pusher:subscription_succeeded', function () {
                channel.bind(eventName, function (data) {
                    if (eventName == 'device-logout') {
                        deviceLogOut(data.deviceId)
                    } else {
                        newDeviceHtml(data.device)
                    }
                })
            });
        });
    };

    //device logout event lisiten
    pusherConnection('device-logout');

    //device add eveent lisiten
    pusherConnection('device-add');

    pusherConnection('message-send');

    const newDeviceHtml = device => {
        document.getElementById("custom-loader").classList.remove('d-none');
        document.querySelector('button[data-bs-dismiss=modal]').click();
        setTimeout(() => {
            let table = document.querySelector("#device-table");
            let exitsDevice = table.querySelector(`#device-${device.device_id}`)
            if (!exitsDevice) {
                let html = `
                    <tr id="device-${device.device_id}">
                        <td data-label="S.N">1</td>
                        <td data-label="Name">
                            <strong> ${device.device_name}</strong>
                        </td>
                        <td data-label="Model"> ${device.device_model}</td>
                        <td data-label="Device ID"> ${device.device_id}</td>
                        <td data-label="Android Version">${device.android_version}</td>
                        <td data-label="App Version">${device.app_version}</td>
                        <td data-label="Status" class="status">
                            <span class="badge badge--success">Connected</span>
                        </td>
                    </tr>
            `
                html = html + table.querySelector('tbody').innerHTML;
                table.querySelector('tbody').innerHTML = html;
                let rows = table.querySelectorAll('tbody tr');
                Array.from(rows).forEach((element, i) => {
                    if (!element.id) {
                        element.remove();
                    }
                    element.querySelector('td').innerText = i + 1;
                });
            } else {
                let elements = document.getElementById(`device-${device.device_id}`);
                elements.querySelector('.status').innerHTML = `<span class="badge badge--success">Connected</span>`;
            }
            document.getElementById("custom-loader").classList.add('d-none');
        }, 1000);

    }

    const deviceLogOut = deviceId => {
        document.getElementById("custom-loader").classList.remove('d-none');
        let table = document.querySelector("#device-table");
        let elements = table.querySelector(`#device-${deviceId}`);
        elements.querySelector('.status').innerHTML = `<span class="badge badge--danger">Disconnected</span>`;
        setTimeout(() => {
            document.getElementById("custom-loader").classList.add('d-none');
        }, 1000);
    }

</script>
@endpush