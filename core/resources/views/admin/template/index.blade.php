@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
                <div class="table-responsive--md  table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('S.N.')</th>
                                <th>@lang('Name')</th>
                                <th>@lang('Message')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($templates as $template)
                            <tr>
                                <td>{{ __($templates->firstItem()+$loop->index) }}</td>
                                <td>{{__(@$template->name)}}</td>
                                <td>{{__(strLimit(@$template->message,50))}}</td>
                                <td>
                                    @if ($template->status)
                                    <span class="badge badge--success">@lang('Active')</span>
                                    @else
                                    <span class="badge badge--danger">@lang('Inactive')</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" data-template='@json($template)'
                                        class="btn btn-sm btn-outline--primary editBtn me-1">
                                        <i class="la la-pen"></i> @lang('Edit')
                                    </button>
                                    
                                     @if($template->status == 0)
                                        <button type="button"
                                                class="btn btn-sm btn-outline--success  confirmationBtn"
                                                data-action="{{ route('admin.template.status', $template->id) }}"
                                                data-question="@lang('Are you sure to active this template?')">
                                            <i class="la la-eye"></i>@lang('Active')
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn"
                                        data-action="{{ route('admin.template.status', $template->id) }}"
                                        data-question="@lang('Are you sure to inactive this template?')">
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
            @if ($templates->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($templates) }}
            </div>
            @endif
        </div>
    </div>
</div>

<div class="modal fade" id="templateModal">
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
                        <label class="fw-bold required">@lang('Name')</label>
                        <input required type="text" class="form-control form-control-lg" name="name" value="{{old('Name')}}" placeholder="@lang('Template Name')">
                    </div>
                    <div class="form-group">
                        <label class="fw-bold required">@lang('Sms')</label>
                        <textarea required name="message" class="form-control" placeholder="@lang('Template Sms')" cols="30" rows="10"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn--primary w-100 h-45" id="btn-save">@lang('Submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <x-confirmation-modal></x-confirmation-modal>


@endsection

@push('breadcrumb-plugins')
<button type="button" class="btn btn-outline--primary btn-sm addBtn">
    <i class="las la-plus"></i> @lang('New Template')
</button>

@endpush
@push('script')

<script>
 "use strict";
    (function ($) {

        let templateModal = $("#templateModal");

        $('.addBtn').on('click', function (e) {
            let action = "{{route('admin.template.store')}}";
            templateModal.find(".modal-title").text("@lang('New Template')");
            templateModal.find('form').trigger('reset');
            templateModal.find('form').attr('action', action);
            templateModal.modal('show');
        });

        $('.editBtn').on('click', function (e) {
            let action = "{{route('admin.template.update',':id')}}";
            let template = $(this).data('template');
            setFormValue(template, 'form')
            templateModal.find(".modal-title").text("@lang('Edit Template')");
            templateModal.find('form').attr('action', action.replace(':id', template.id));
            templateModal.modal('show');
        });

    })(jQuery);
</script>
@endpush
