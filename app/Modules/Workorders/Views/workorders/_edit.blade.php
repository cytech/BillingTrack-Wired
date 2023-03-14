@include('workorders._js_edit')
<section class="app-content-header">
    <h3 class="float-start px-3">@lang('bt.workorder') #{{ $workorder->number }}</h3>
    @if ($workorder->viewed)
        <span style="margin-start: 10px;" class="badge bg-success">@lang('bt.viewed')</span>
    @else
        <span style="margin-start: 10px;" class="badge bg-secondary">@lang('bt.not_viewed')</span>
    @endif

    @if ($workorder->invoice()->count())
        @if($workorder->invoice->status_text == 'canceled')
            <span class="badge badge-canceled" title="@lang('bt.canceled')"><a
                        href="{{ route('invoices.edit', [$workorder->invoice_id]) }}"
                        style="color: inherit;">@lang('bt.converted_to_invoice') {{ $workorder->invoice->number }}</a></span>
        @else
            <span class="badge bg-info"><a href="{{ route('invoices.edit', [$workorder->invoice_id]) }}"
                                           style="color: inherit;">@lang('bt.converted_to_invoice') {{ $workorder->invoice->number }}</a></span>
        @endif
    @elseif ($workorder->invoice()->withTrashed()->count())
        <span class="badge bg-danger"
              title="Trashed">@lang('bt.converted_to_invoice') {{ $workorder->invoice_id }}</span>
    @endif

    @if ($workorder->quote()->count())
        <span class="badge bg-info"><a href="{{ route('quotes.edit', [$workorder->quote->id]) }}"
                                       style="color: inherit;">@lang('bt.converted_from_quote') {{ $workorder->quote->number }}</a></span>
    @endif
    <div class="float-end">
        <a href="{{ route('workorders.pdf', [$workorder->id]) }}" target="_blank" id="btn-pdf-workorder"
           class="btn btn-secondary"><i class="fa fa-print"></i> @lang('bt.pdf')</a>
        {{-- removed email button from workorders, there should not be emailing a customer a workorder, only a quote or invoice --}}
        {{--@if (config('bt.mailConfigured'))
            <a href="javascript:void(0)" id="btn-email-workorder" class="btn btn-secondary email-workorder"
               data-workorder-id="{{ $workorder->id }}" data-redirect-to="{{ route('workorders.edit', [$workorder->id]) }}"><i
                        class="fa fa-envelope"></i> @lang('bt.email')</a>
        @endif--}}
        <div class="btn-group">
            <button type="button" class="btn btn-secondary" data-bs-toggle="dropdown">
                @lang('bt.other')
            </button>
            <div class="dropdown-menu dropdown-menu-end" role="menu">
                <a class="dropdown-item" href="#" id="btn-copy-workorder"
                   {{--                   params 3 thru ... mount(,,$modulefullname, $moduleop, $resource_id = null, $module_id = null, $readonly = null)--}}
                   onclick="window.livewire.emit('showModal', 'modals.create-module-modal', '{{  addslashes(get_class($workorder)) }}', 'copy', {{ $workorder->client->id }}, {{ $workorder->id }})">
                    <i class="fa fa-copy"></i> @lang('bt.copy_workorder')</a>
                <a class="dropdown-item" href="javascript:void(0)" id="btn-workorder-to-invoice"><i
                            class="fa fa-check"></i> @lang('bt.workorder_to_invoice')</a>
                <div class="dropdown-divider"></div>
                @if($workorder->quote)
                    <a class="dropdown-item" href="#"
                       onclick="swalConfirm('@lang('bt.trash_record_warning')','@lang('bt.trash_workorder_warning_assoc_msg')', '{{ route('workorders.delete', [$workorder->id]) }}');"><i
                                class="fa fa-trash-alt"></i> @lang('bt.trash')</a>
                @else
                    <a class="dropdown-item" href="#"
                       onclick="swalConfirm('@lang('bt.trash_record_warning')', '', '{{ route('workorders.delete', [$workorder->id]) }}');"><i
                                class="fa fa-trash-alt"></i> @lang('bt.trash')</a>
                @endif
            </div>
        </div>
        <div class="btn-group">
            @if ($returnUrl)
                <a href="{{ $returnUrl }}" class="btn btn-secondary"><i
                            class="fa fa-backward"></i> @lang('bt.back')</a>
            @endif
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-primary btn-save-workorder"><i
                        class="fa fa-save"></i> @lang('bt.save')</button>
            <button type="button" class="btn btn-primary" data-bs-toggle="dropdown"><i class="fas fa-chevron-down"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" role="menu">
                <a class="dropdown-item" href="#" class="btn-save-workorder"
                   data-apply-exchange-rate="1">@lang('bt.save_and_apply_exchange_rate')</a>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</section>
<section class="container-fluid">
    <div class="row">
        <div class="col-lg-10">
            @include('layouts._alerts')
            <div id="form-status-placeholder"></div>
            <div class="row">
                <div class="col-sm-6" id="col-from">
                    @include('workorders._edit_from')
                </div>
                <div class="col-sm-6" id="col-to">
                    @include('workorders._edit_to')
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">@lang('bt.summary')</h3>
                        </div>
                        <div class="card-body">
                            {!! Form::text('summary', $workorder->summary, ['id' => 'summary', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-3 mb-3 align-items-center">
                <div class="col-sm-1 text-end fw-bold">
                    <label class="col-form-label">@lang('bt.job_date')</label>
                </div>
                <div class="col-sm-2">
                    <x-fp_common
                            id="job_date"
                            class="form-control form-control-sm"
                            value="{{$workorder->job_date}}"></x-fp_common>
                </div>
                <div class="col-sm-1 text-end fw-bold">
                    <label for="start_time" class="col-form-label">@lang('bt.start_time')</label>
                </div>
                <div class="col-sm-2">
                    <x-fp_time
                            id="start_time"
                            class="form-control form-control-sm"
                            value="{{$workorder->start_time}}"></x-fp_time>
                </div>
                <div class="col-sm-1 text-end fw-bold">
                    <label for="end_time" class="col-form-label">@lang('bt.end_time')</label>
                </div>
                <div class="col-sm-2">
                    <x-fp_time
                            id="end_time"
                            class="form-control form-control-sm"
                            value="{{$workorder->end_time}}"></x-fp_time>
                </div>
                <div class="col-sm-2 ms-5 form-check form-switch form-switch-md">
                    {!! Form::checkbox('will_call', 1, $workorder->will_call, ['id' => 'will_call', 'class' => 'form-check-input']) !!}
                    <label class="form-check-label fw-bold ps-3 pt-1" for="will_call">@lang('bt.will_call')</label>
                </div>
            </div>
            <livewire:items-table :module="$workorder"/>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-outline card-primary m-2">
                        <div class="card-header d-flex p-0">
                            <ul class="nav nav-tabs p-2">
                                <li class="nav-item"><a class="nav-link active show" href="#tab-additional"
                                                        data-bs-toggle="tab">@lang('bt.additional')</a></li>
                                <li class="nav-item"><a class="nav-link" href="#tab-notes"
                                                        data-bs-toggle="tab">@lang('bt.notes')</a></li>
                                <li class="nav-item"><a class="nav-link" href="#tab-attachments"
                                                        data-bs-toggle="tab">@lang('bt.attachments')</a></li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab-additional">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label>@lang('bt.terms_and_conditions')</label>
                                                {!! Form::textarea('terms', $workorder->terms, ['id' => 'terms', 'class' => 'form-control', 'rows' => 5]) !!}
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label>@lang('bt.footer')</label>
                                                {!! Form::textarea('footer', $workorder->footer, ['id' => 'footer', 'class' => 'form-control', 'rows' => 5]) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab-notes">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            @include('notes._notes', ['object' => $workorder, 'model' => 'BT\Modules\Workorders\Models\Workorder', 'showPrivateCheckbox' => true, 'hideHeader' => true])
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab-attachments">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            @include('attachments._table', ['object' => $workorder, 'model' => 'BT\Modules\Workorders\Models\Workorder'])
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            <div id="div-totals">
                @include('workorders._edit_totals')
            </div>
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">@lang('bt.options')</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label>@lang('bt.workorder') #</label>
                        {!! Form::text('number', $workorder->number, ['id' => 'number', 'class' =>
                        'form-control
                        form-control-sm']) !!}
                    </div>
                    <div class="mb-3">
                        <label>@lang('bt.date')</label>
                        <x-fp_common
                                id="workorder_date"
                                class="form-control form-control-sm"
                                value="{{$workorder->workorder_date}}"></x-fp_common>
                    </div>
                    <div class="mb-3">
                        <label>@lang('bt.status')</label>
                        {!! Form::select('workorder_status_id', $statuses, $workorder->workorder_status_id,
                        ['id' => 'workorder_status_id', 'class' => 'form-select form-select-sm']) !!}
                    </div>
                    <div class="mb-3">
                        <label>@lang('bt.expires')</label>
                        <x-fp_common
                                id="expires_at"
                                class="form-control form-control-sm"
                                value="{{$workorder->expires_at}}"></x-fp_common>
                    </div>
                    <div class="mb-3">
                        <label>@lang('bt.discount')</label>
                        <div class="input-group input-group-sm">
                            {!! Form::text('discount', $workorder->formatted_numeric_discount, ['id' =>
                            'discount', 'class' => 'form-control form-control-sm']) !!}
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>@lang('bt.currency')</label>
                        {!! Form::select('currency_code', $currencies, $workorder->currency_code, ['id' =>
                        'currency_code', 'class' => 'form-select form-select-sm']) !!}
                    </div>
                    <div class="mb-3">
                        <label>@lang('bt.exchange_rate')</label>
                        <div class="input-group">
                            {!! Form::text('exchange_rate', $workorder->exchange_rate, ['id' =>
                            'exchange_rate', 'class' => 'form-control form-control-sm']) !!}
                            <button class="btn btn-sm input-group-text " id="btn-update-exchange-rate" type="button"
                                    data-toggle="tooltip" data-placement="left"
                                    title="@lang('bt.update_exchange_rate')"><i class="fa fa-sync"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>@lang('bt.template')</label>
                        {!! Form::select('template', $templates, $workorder->template,
                        ['id' => 'template', 'class' => 'form-select form-select-sm']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($customFields->count())
        <div class="row">
            <div class="col-md-12">
                @include('custom_fields._custom_fields_unbound', ['object' => $workorder])
            </div>
        </div>
    @endif
</section>
