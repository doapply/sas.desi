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
                                <th>@lang('Batch Number')</th>
                                <th>@lang('Date')</th>
                                <th>@lang('Total SMS')</th>
                                <th>@lang('Total Delivered')</th>
                                <th>@lang('Total Pending')</th>
                                <th>@lang('Total Processing')</th>
                                <th>@lang('Total Shcedule')</th>
                                <th>@lang('Total Faild')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($batches as $batch)
                            <tr>
                                <td>{{ __($batches->firstItem()+$loop->index) }}</td>
                                <td>
                                    <strong>{{ __($batch->batch_id) }}</strong>
                                </td>
                                <td> {{showdateTime(@$batch->created_at)}} <br> <small>{{ __(diffForHumans($batch->created_at)) }}</small> </td>
                                <td>
                                    <span class="badge badge--primary">{{__(@$batch->sms->count())}}</span>
                                </td>
                                <td>
                                    <span class="badge badge--success">{{__(@$batch->sms->where('status',1)->count())}}</span>
                                </td>
                                <td>
                                    <span class="badge badge--warning">{{__(@$batch->sms->where('status',2)->count())}}</span>
                                </td>
                                <td>
                                    <span class="badge badge--primary">{{__(@$batch->sms->where('status',4)->count())}}</span>
                                </td>
                                <td>
                                    <span class="badge badge--primary">{{__(@$batch->sms->where('status',3)->count())}}</span>
                                </td>
                                <td>
                                    <span class="badge badge--danger">{{__(@$batch->sms->where('status',9)->count())}}</span>
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
            @if ($batches->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($batches) }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
