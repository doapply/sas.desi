@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-body p-0">
                <div class="table-responsive--md table-responsive">
                    <table class="table--light style--two table">
                        <thead>
                            <tr>
                                <th>@lang('S.N.')</th>
                                <th>@lang('Name')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Total Contact')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($groups as $group)
                            <tr>
                                <td>{{ __($groups->firstItem() + $loop->index) }}</td>
                                <td> {{ __(@$group->name) }}</td>
                                <td>
                                    @if ($group->status)
                                    <span class="badge badge--success">@lang('Active')</span>
                                    @else
                                    <span class="badge badge--danger">@lang('Inactive')</span>
                                    @endif
                                </td>
                                <td>
                                    {{ __($group->total_contact) }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-end">
                                       <button type="button" data-group='@json($group)' class="btn btn-sm btn-outline--primary editBtn ">
                                            <i class="la la-pen"></i> @lang('Edit')
                                        </button>
                                        <a class="btn btn-sm btn-outline--success" href="{{ route('admin.group.contact.view',$group->id) }}">
                                            <i class="las la-eye"></i> @lang('View Contact')
                                        </a>
                                         @if($group->status == 0)
                                        <button type="button"
                                                class="btn btn-sm btn-outline--success  confirmationBtn"
                                                data-action="{{ route('admin.group.status', $group->id) }}"
                                                data-question="@lang('Are you sure to active this group?')">
                                            <i class="la la-eye"></i>@lang('Active')
                                        </button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn"
                                            data-action="{{ route('admin.group.status', $group->id) }}"
                                            data-question="@lang('Are you sure to inactive this group?')">
                                                    <i class="la la-eye-slash"></i>@lang('Inactive')
                                            </button>
                                       @endif
                                    </div>
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
            @if ($groups->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($groups) }}
            </div>
            @endif
        </div>
    </div>
</div>

<div class="modal fade" id="groupModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('Add New Contact')</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <form method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="fw-bold required">@lang('Name')</label>
                        <input required type="text" class="form-control form-control-lg" name="name" value="{{ old('name') }}" placeholder="@lang('Name')">
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
<button type="button" class="btn btn-outline--primary btn-sm addBtn ">
    <i class="las la-plus"></i> @lang('New Group')
</button>
@endpush

@push('script')
<script>
    "use strict";
    (function ($) {

        let groupModal = $("#groupModal");

        $('.addBtn').on('click', function (e) {
            let action = "{{ route('admin.group.store') }}";
            groupModal.find(".modal-title").text("@lang('Add Group')");
            groupModal.find('form').trigger('reset');
            groupModal.find('form').attr('action', action);
            groupModal.modal('show');
        });

        $('.editBtn').on('click', function (e) {
            let action = "{{ route('admin.group.update', ':id') }}";
            let group  = $(this).data('group');
            groupModal.find('input[name=name]').val(group.name);

          
            groupModal.find(".modal-title").text("@lang('Edit Group')");
            groupModal.find('form').attr('action', action.replace(':id', group.id));
            groupModal.modal('show');

        });

    })(jQuery);
</script>
@endpush
