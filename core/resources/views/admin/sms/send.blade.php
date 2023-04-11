@extends('admin.layouts.app')
@section('panel')
<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form method="POST" method="POST" id="message-form" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="fw-bold required">@lang('Device')</label>
                                <select name="device" class="form-control form-control-lg select2-basic">
                                    <option value="" selected disabled>@lang('Please Select One')</option>
                                    @foreach ($allDevice as $device)
                                    <option value="{{$device->id}}" data-sim='@json($device->sim)'>
                                        {{ __($device->device_name) }}-{{ __($device->device_model)}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="fw-bold required">@lang('Select SIM')</label>
                                <select name="sim" class="form-control select2-basic">
                                    <option value="" selected disabled>@lang('Please Select Device')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12" id="schedule">
                            <div class="form-group">
                                <label class="fw-bold required">@lang('Schedule')</label>
                                <select name="schedule" class="form-control select2-basic">
                                    <option value="1">@lang('Send Now')</option>
                                    <option value="2">@lang('Add Schedule')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 d-none" id="date">
                            <div class="form-group">
                                <label for="date" class="fw-bold required">@lang('Date')</label>
                                <div class="input-group">
                                    <input type="text" name="date" class="form-control date" autocomplete="off">
                                    <span class="input-group-text">
                                        <i class="las la-clock"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border-line-area mt-2 mb-2">
                        <h6 class="border-line-title font-weight-bold">@lang('Mobile Numbers')</h6>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="fw-bold required">@lang('Mobile Numbers')</label>
                                <input name="mobile_numbers" type="text" class="form-control form-control-lg"
                                    placeholder="+1 (143) 896-7781,+1 (191) 524-4782">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                    <label class="fw-bold">@lang('Mobile Numbers From Contact List')</label>
                                    <input type="checkbox" data-bs-toggle="tooltip" title="@lang('Select All')" class="select-all-contact" name="select_all_contact">
                                </div>
                                <select class="form-control form-control-lg contact-list" name="contact_list[]" multiple></select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                    <label class="fw-bold">@lang('Mobile Numbers From Group')</label>
                                    <input type="checkbox" data-bs-toggle="tooltip" title="@lang('Select All')" class="select-all-group" name="select_all_group">
                                </div>
                                <select class="form-control form-control-lg select2-basic group-list" name="group[]" multiple>
                                    <option value="" disabled>@lang('Select One')</option>
                                    @foreach ($groups as $group)
                                    <option value="{{ $group->id }}">{{ __($group->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="fw-bold">@lang('Upload File') </label> <strong class="uploadInFo"><i
                                        class="fas fa-info-circle text--primary"></i></strong>
                                <input type="file" class="form-control form-control-lg uploadFile" id="uploadFile"
                                    accept=".txt,.csv,.xlsx" name="file">
                                <small class="file-size float-end text--primary"></small>
                                <div class="mt-2">
                                    <small class="d-block">
                                        @lang('Supported files:') <b class="fw-bold">@lang('csv'), @lang('excel'),
                                            @lang('txt')</b>
                                    </small>
                                    <small>
                                        @lang('Download all of the template files from here')
                                        <a href="{{ asset('assets/admin/file_template/mobile/sample.csv') }}"
                                            title="@lang('Download csv file')" class="text--primary" download>
                                            <b>@lang('csv,')</b>
                                        </a>
                                        <a href="{{ asset('assets/admin/file_template/mobile/sample.xlsx') }}"
                                            title="@lang('Download excel file')" class="text--primary" download>
                                            <b>@lang('excel,')</b>
                                        </a>
                                        <a href="{{ asset('assets/admin/file_template/mobile/sample.txt') }}"
                                            title="@lang('Download txt file')" class="text--primary" download>
                                            <b>@lang('txt')</b>
                                        </a>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-line-area mt-2 mb-2">
                        <h6 class="border-line-title font-weight-bold">@lang('Write SMS')</h6>
                    </div>

                    <div class="form-group">
                        <label class="fw-bold">@lang('Template')</label>
                        <select name="template" class="form-control form-control-lg select2-basic">
                            <option selected disabled>@lang('Select One')</option>
                            @foreach ($templates as $template)
                            <option value="{{ $template->id }}" message="{{$template->message}}">
                                {{__($template->name) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="fw-bold">@lang('Sms')</label>
                        <textarea name="message" required class="form-control" cols="30" rows="6"></textarea>
                        <div class="message-count mt-2"></div>
                    </div>
                    <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="info-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('File Upload Information')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="la la-close" aria-hidden="true"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="alert alert-warning p-3 text-center" role="alert">
                        <p>
                            @lang('The file you wish to upload has to be formatted as we provided template files. Any
                            changes to these files will be considered as an invalid file format. Download links are
                            provided below.')
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


{{-- custom-loadder --}}
<div class="adz-modal">
    <div class="adz-modal__card">
        <span class="adz-modal__text">@lang('Loading....')</span>
        <div class="adz-progressbar">
            <div class="adz-progressbar__bg"></div>
            <div class="adz-progressbar__buffer"></div>
            <div class="adz-progressbar__line">
                <div class="adz-progressbar__indeterminate long"></div>
                <div class="adz-progressbar__indeterminate short"></div>
            </div>
        </div>
    </div>
</div>


@endsection

@push('breadcrumb-plugins')
<a href="{{ route('admin.sms.index') }}" class="btn btn-outline--primary btn-sm">
    <i class="las la-bars"></i> @lang('SMS History')
</a>
@endpush



@push('script-lib')
<script src="{{asset('assets/admin/js/vendor/datepicker.min.js')}}"></script>
<script src="{{asset('assets/admin/js/vendor/datepicker.en.js')}}"></script>
@endpush

@push('style-lib')
<link  rel="stylesheet" href="{{asset('assets/admin/css/vendor/datepicker.min.css')}}">
@endpush

@push('script')
<script>
    (function ($) {


        $("select[name=device]").on('change', function (e) {
            let sim = $("select[name=device]").find('option:selected').data('sim');
            let html = "<option value='' selected disabled>@lang('Please select one')</option>";
            sim.forEach(element => {
                html += `<option value="${element.slot}***${element.name}">${element.slot}-${element.name}</option>`
            });
            $("select[name=sim]").html(html)
        });



        $('.date').datepicker({
            language  : 'en',
            dateFormat: 'yyyy-mm-dd',
            timepicker: true,
        });

        $('.date, .time-picker').on('keypress keyup change paste', function (e) {
            return false;
        });

        $("select[name=schedule]").on('change', function (e) {
            let schedule = $(this).val();
            if (schedule == 1) {
                $("#schedule").addClass('col-lg-12')
                $("#schedule").removeClass('col-lg-6');
                $("#date").addClass('d-none');
            } else {
                $("#schedule").removeClass('col-lg-12')
                $("#schedule").addClass('col-lg-6');
                $("#date").removeClass('d-none');
            }
        });

        function getContactList() {
            $('.contact-list').select2({
                ajax: {
                    url     : "{{route('admin.contact.search')}}",
                    type    : "get",
                    dataType: 'json',
                    delay   : 1000,
                    data: function (params) {
                        return {
                            search: params.term,
                            page  : params.page,
                        };
                    },
                    processResults: function (response, params) {
                        params.page = params.page || 1;
                        let data = response.contacts.data;
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.mobile,
                                    id  : item.id
                                }
                            }),
                            pagination: {
                                more: response.more
                            }
                        };
                    },
                    cache: false
                },

            });
        }

        getContactList()


        $("select[name=template]").on('change', function (e) {
            let message = $(this).find('option:selected').attr('message');
            $("textarea[name=message]").val(message)
            messageCount();
        });


        $("textarea[name=message]").on('keyup keypress change keydown paste', function (e) {
            messageCount();
        })


        function messageCount() {
            let message = $("textarea[name=message]").val();
            let word    = message.split(" ");

            $('.message-count').removeClass('d-none');
            $(".message-count").html(`
                <small><span class='text--success'>${message.length}</span> Characters</small> <br>
                <small><span class="text--success">${word.length}</span> Words</small> <br>
            `);

        };

        $(".uploadInFo").on('click', function (e) {
            $("#info-modal").modal('show');
        });

        $(".uploadFile").on('change', function (e) {
            let file          = e.target.files[0];
            let fileExtention = file.name.split('.').pop();
            if (fileExtention != 'csv' && fileExtention != 'xlsx' && fileExtention != 'txt') {
                notify('error', "@lang('File type not suported')");
                document.querySelector('.uploadFile').value = '';
                return false;
            }
            let size = fileSize(file.size);
            $(".file-size").text(size);
        });

        ///get the file size on js file upload
        function fileSize(bytes) {

            let marker = 1024; // Change to 1000 if required
            let kiloBytes = marker; // One Kilobyte is 1024 bytes
            let megaBytes = marker * marker; // One MB is 1024 KB
            let gigaBytes = marker * marker * marker; // One GB is 1024 MB
            let teraBytes = marker * marker * marker * marker; // One TB is 1024 GB

            if (bytes < kiloBytes) return bytes + " Bytes";
            else if (bytes < megaBytes) return (bytes / kiloBytes).toFixed(2) + " KB";
            else if (bytes < gigaBytes) return (bytes / megaBytes).toFixed(2) + " MB";
            else return (bytes / gigaBytes).toFixed(2) + " GB";
        }

        $('#message-form').on('submit', function (e) {
            e.preventDefault();
            let formData = new FormData($(this)[0])
            $.ajax({
                url        : $(this).attr('action'),
                type       : "POST",
                data       : formData,
                cache      : false,
                contentType: false,
                processData: false,
                beforeSend : function (e) {
                    $(".adz-modal ").css('display','block');
                },
                complete   : function (e) {
                    $(".adz-modal ").css('display','none');
                    $("html").animate({ scrollTop: 0 }, "fast");
                },
                success: function (response) {
                    if (response.success) {
                        notify('success', response.message);
                        $("#message-form").trigger('reset');
                        $('.contact-list').find(`option[value=select_all_contact]`).remove();
                        $('.contact-list').attr('disabled',false);
                        $('.group-list').find(`option[value=select_all_group]`).remove();
                        $('.group-list').attr('disabled',false);
                        $('.select2-basic').select2();
                        getContactList();
                        $('.message-count').addClass('d-none');
                        $('#date').addClass('d-none');
                        $('#schedule').addClass('col-lg-12');

                    } else {
                        notify('error', response.errors && response.errors.length > 0 ? response.errors : response.message || "@lang('Somehting went to wrong')")
                    }
                },
                error: function (e) {
                    notify('error', "@lang('Something went to wrong')")
                }
            });
        });

        $('.select-all-contact').on('click',function (e) {
            if(this.checked){
                let message="@lang('Send SMS to all Contact')";
                @if(!$contactExits)
                    message="@lang('No Contact found for sending SMS')"
                @endif
                $('.contact-list').html(`<option value="select_all_contact" selected disabled>${message}</option>`);
                $('.contact-list').attr('disabled',true);
            }else{
                $('.contact-list').find(`option[value=select_all_contact]`).remove();
                $('.contact-list').attr('disabled',false);
            }
         });
        $('.select-all-group').on('click',function (e) {
            if(this.checked){
                let message="@lang('Send SMS to all Group')";
                @if($groups->count()<=0)
                    message="@lang('No Group found for sending SMS')"
                @endif
                $('.group-list').html(`<option value="select_all_group" selected disabled>${message}</option>`);
                $('.group-list').attr('disabled',true);
            }else{
                $('.group-list').find(`option[value=select_all_group]`).remove();
                $('.group-list').attr('disabled',false);
                let html="";
                @foreach ($groups as $group)
                html+=`<option value="{{ $group->id }}">{{ __($group->name) }}</option>`;
                @endforeach
                 $('.group-list').html(html);
                $('.group-list').select2();
            }
         });
    })(jQuery);

</script>
@endpush


@push('style')
<style>
    .select2-container--default .select2-selection--multiple {
        border-color: #ddd;
        min-height: calc(1.8rem + 1rem + 2px) !important;
        height: auto;
    }

    .select2-container .select2-selection--single {
        border-color: #ddd;
        min-height: calc(1.8rem + 1rem + 2px) !important;
        height: auto;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 45px;
    }
    .uploadInFo {
        cursor: pointer;
    }
    .datepickers-container{
        z-index: 9999999999;
    }

.adz-modal {
    width: 300px;
    border-radius: 4px;
    pointer-events: auto;
    box-shadow: 0 11px 15px -7px rgb(0 0 0 / 20%), 0 24px 38px 3px rgb(0 0 0 / 14%), 0 9px 46px 8px rgb(0 0 0 / 12%);
    top: 50%;
    left: 50%;
    z-index: 99999999999999999999;
    position:absolute;
    display: none;
}

.adz-modal__card {
    padding: 5px 24px 15px;
    box-shadow: 0 3px 1px -2px rgb(0 0 0 / 20%), 0 2px 2px 0 rgb(0 0 0 / 14%), 0 1px 5px 0 rgb(0 0 0 / 12%);
    background-color: #1867c0;
    border-radius: 4px;
}

.adz-modal__text {
    display: block;
    margin-bottom: 5px;
    color: hsla(0, 0%, 100%, .7);
}

.adz-progressbar {
    width: 100%;
    height: 4px;
    background: transparent;
    overflow: hidden;
    position: relative;
    transition: .2s cubic-bezier(.4, 0, .6, 1);
}

.adz-progressbar__bg {
    width: 100%;
    position: absolute;
    bottom: 0;
    top: 0;
    left: 0;
    opacity: 0.3;
    border-color: #fff !important;
    background-color: #fff !important;
    transition: inherit;
}

.adz-progressbar__buffer {
    width: 100%;
    height: inherit;
    position: absolute;
    left: 0;
    top: 0;
    transition: inherit;
}

.adz-progressbar__indeterminate {
    width: auto;
    height: inherit;
    animation-play-state: running;
    animation-duration: 2.2s;
    animation-iteration-count: infinite;
    position: absolute;
    bottom: 0;
    left: 0;
    top: 0;
    right: auto;
    will-change: left, right;
    border-color: #fff !important;
    background-color: #fff !important;
}

.adz-progressbar__indeterminate.long {
    animation-name: indeterminate-ltr;
}

.adz-progressbar__indeterminate.short {
    animation-name: indeterminate-short-ltr;
}

@keyframes indeterminate-ltr {
    0% {
        left: -90%;
        right: 100%;
    }

    60% {
        left: -90%;
        right: 100%;
    }

    100% {
        left: 100%;
        right: -35%;
    }
}

@keyframes indeterminate-short-ltr {
    0% {
        left: -200%;
        right: 100%;
    }

    60% {
        left: 107%;
        right: -8%;
    }

    100% {
        left: 107%;
        right: -8%;
    }
}
</style>
@endpush
