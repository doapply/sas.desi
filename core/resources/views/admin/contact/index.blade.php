@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10 mb-4">
            <div class="card-body">
                <form action="">
                    <div class="align-items-center justify-content-end d-flex flex-wrap gap-3">
                        <div class="flex-fill">
                            <label>@lang('Mobile')</label>
                            <input type="text" autocomplete="off" name="mobile" value="{{ request()->mobile ?? null }}"
                                placeholder="@lang('Search with mobile number')"class="form-control">
                        </div>
                        <div class="flex-fill">
                            <label>@lang('Status')</label>
                            <select class="form-control form-control" name="status">
                                <option value="" selected>@lang('All')</option>
                                <option value="1">@lang('Active')</option>
                                <option value="0">@lang('Inactive')</option>
                            </select>
                        </div>
                        <div class="flex-fill">
                            <button class="btn btn--primary w-100 h-45 mt-4" type="submit">
                                <i class="fas fa-filter"></i>@lang('Filter')
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-body p-0">
                <div class="table-responsive--md table-responsive">
                    <table class="table--light style--two table">
                        <thead>
                            <tr>
                                <th>@lang('S.N.')</th>
                                <th>@lang('Mobile')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($contacts as $contact)
                            <tr>
                                <td>{{ __($contacts->firstItem() + $loop->index) }}</td>
                                <td>{{ __(@$contact->mobile) }}</td>
                                <td>
                                    @if ($contact->status)
                                    <span class="badge badge--success">@lang('Active')</span>
                                    @else
                                    <span class="badge badge--danger">@lang('Inactive')</span>
                                    @endif
                                </td>
                                <td>
                                  <button type="button" data-contact='@json($contact)'
                                        class="btn btn-sm btn-outline--primary editBtn me-1">
                                        <i class="la la-pen"></i> @lang('Edit')
                                    </button>
                                    @if($contact->status == 0)
                                        <button type="button"
                                                class="btn btn-sm btn-outline--success  confirmationBtn"
                                                data-action="{{ route('admin.contact.status', $contact->id) }}"
                                                data-question="@lang('Are you sure to active this contact?')">
                                            <i class="la la-eye"></i>@lang('Active')
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn"
                                        data-action="{{ route('admin.contact.status', $contact->id) }}"
                                        data-question="@lang('Are you sure to inactive this contact?')">
                                                <i class="la la-eye-slash"></i>@lang('Inactive')
                                        </button>
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
            @if ($contacts->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($contacts) }}
            </div>
            @endif
        </div>
    </div>
</div>

<div class="modal fade" id="contactModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('Add New Contact')</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="post" id="form">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="fw-bold required">@lang('Mobile Number')</label>
                        <input type="tel" class="form-control form-control-lg" name="mobile" value="{{ old('mobile') }}"
                            placeholder="@lang('+1 (452) 338-9111')">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn--primary w-100 h-45" id="btn-save">@lang('Submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- IMPORT MODAL --}}
<div class="modal fade" id="importModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('Import Contact')</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form method="post" action="{{ route('admin.contact.import') }}" id="importForm"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <x-file-upload-warning/>
                    </div>
                    <div class="form-group">
                        <label class="fw-bold required">@lang('Select File')</label>
                        <input type="file" class="form-control" name="file" accept=".txt,.csv,.xlsx">
                        <x-file-upload-link/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="Submit" class="btn btn--primary w-100 h-45">@lang('Upload')</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Export MODAL --}}
<div class="modal fade" id="exportModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('Export Filter')</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form method="post" action="{{ route('admin.contact.export') }}" id="importForm"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="fw-bold">@lang('Export Column')</label>
                        <div class="d-flex gap-4 flex-wrap">
                            @foreach ($columns as $column)
                            <div>
                                <input type="checkbox" name="columns[]" value="{{$column}}" id="colum-{{$column}}" {{
                                    $column=='created_at' || $column=='updated_at' || $column=='id' ? 'unchecked'
                                    : 'checked' }}>
                                <label class="form-check-label" for="colum-{{$column}}">
                                    {{ __(keyToTitle($column)) }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="fw-bold">@lang('Order By')</label>
                        <select name="order_by" class="form-control">
                            <option value="ASC">@lang('ASC')</option>
                            <option value="DESC">@lang('DESC')</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="fw-bold">@lang('Export Item')</label>
                        <select class="form-control form-control-lg export-item" name="export_item"
                            max-item="{{ $contacts->total() }}">
                            <option value="10">@lang('10')</option>
                            <option value="50">@lang('50')</option>
                            <option value="100">@lang('100')</option>
                            @if ($contacts->total() > 100)
                            <option value="{{$contacts->total()}}">{{ __($contacts->total()) }}</option>
                            @endif
                            <option value="custom">@lang('Custom')</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="Submit" class="btn btn--primary w-100 h-45 contactExport">@lang('Export')</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <x-confirmation-modal></x-confirmation-modal>

@endsection

@push('breadcrumb-plugins')
<div class="d-flex justify-content-center justify-content-sm-end align-items-center flex-wrap gap-2">
    <button type="button" class="btn btn-outline--primary btn-sm addBtn">
        <i class="las la-plus"></i> @lang('New')
    </button>
    <button type="button" class="btn btn-outline--info btn-sm importBtn">
        <i class="las la-cloud-upload-alt"></i> @lang('Import')
    </button>
    <button type="button" class="btn btn-outline--warning btn-sm exportBtn">
        <i class="las la-cloud-download-alt"></i> @lang('Export')
    </button>
</div>
@endpush

@push('script')
<script>
     "use strict";
    (function ($) {

        let contactModal = $("#contactModal");

        $('.addBtn').on('click', function (e) {
            let action = "{{ route('admin.contact.store') }}";
            contactModal.find(".modal-title").text("@lang('Add Contact')");
            contactModal.find('form').trigger('reset');
            contactModal.find('form').attr('action', action);
            contactModal.modal('show');
        });

        $('.editBtn').on('click', function (e) {
            let action = "{{ route('admin.contact.update', ':id') }}";
            let contact = $(this).data('contact');
            setFormValue(contact, 'form')
          
            contactModal.find(".modal-title").text("@lang('Edit Contact')");
            contactModal.find('form').attr('action', action.replace(':id', contact.id));
            contactModal.modal('show');
        });

        $(".importBtn").on('click', function (e) {
            let importModal = $("#importModal");
            importModal.modal('show');
        });

        $('#importForm').on('submit', function (event) {
           event.preventDefault();
            let formData = new FormData($(this)[0]);
            let time = 0;
            $.ajax({
                url: $(this).attr('action'),
                method: "POST",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#importModal').find('.modal-header').addClass('animate-border');
                },
                complete: function (e) {
                    $('#importModal').find('.modal-header').removeClass('animate-border');
                },
                success: function (response) {
                    if (response.success) {
                        notify('success', response.message);
                        $("#importModal").modal('hide');
                        setTimeout(() => {
                            location.reload()
                        }, 2000);
                    } else {
                        notify('error', response.errors && response.errors.length > 0 ? response.errors : response.message || "@lang('Something went the wrong')");
                    }
                },

            });
        });

        $(".exportBtn").on('click', function (e) {
            let modal = $("#exportModal");
            modal.modal('show')
        });

        $("#exportModal form").on('submit', function (e) {
            $("#exportModal").modal('hide');
        });

    })(jQuery);
</script>
@endpush

@push('style')
<style>
    .recommended {
        font-size: 10px;
    }
</style>
@endpush
