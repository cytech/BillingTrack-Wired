@include('documents._js_edit')
<section class="app-content-header">
    <h3 class="float-start px-3">@lang('bt.'. $document->lower_case_baseclass) #{{ $document->number }}</h3>
    @if ($document->viewed)
        <span style="margin-start: 10px;" class="badge bg-success">@lang('bt.viewed')</span>
    @else
        <span style="margin-start: 10px;" class="badge bg-secondary">@lang('bt.not_viewed')</span>
    @endif

    @if ($document->invoice_id)
            @if ($document->invoice->trashed())
            <span class="badge bg-danger"
                  title="Trashed">@lang('bt.converted_to_invoice') {{ $document->invoice->number }}</span>

        @elseif($document->invoice->status_text == 'canceled')
            <span class="badge badge-canceled" title="@lang('bt.canceled')"><a
                        href="{{ route('documents.edit', [$document->invoice->id]) }}"
                        style="color: inherit;">@lang('bt.converted_to_invoice') {{ $document->invoice->number }}</a></span>
        @else
            <span class="badge bg-info"><a href="{{ route('documents.edit', [$document->invoice->id]) }}"
                                           style="color: inherit;">@lang('bt.converted_to_invoice') {{ $document->invoice->number }}</a></span>
        @endif
    @endif

    @if ($document->workorder_id)
        @if ($document->workorder->trashed())
            <span class="badge bg-danger"
                  title="Trashed">@lang('bt.converted_to_workorder') {{ $document->workorder->number }}</span>
        @elseif($document->workorder->status_text == 'canceled')
            <span class="badge badge-canceled" title="@lang('bt.canceled')"><a
                        href="{{ route('documents.edit', [$document->workorder->id]) }}"
                        style="color: inherit;">@lang('bt.converted_to_workorder') {{ $document->workorder->number }}</a></span>
        @else
            <span class="badge bg-info"><a href="{{ route('documents.edit', [$document->workorder->id]) }}"
                                           style="color: inherit;">@lang('bt.converted_to_workorder') {{ $document->workorder->number }}</a></span>
        @endif
    @endif
    <div class="float-end">
        <a href="{{ route('documents.pdf', [$document->id]) }}" target="_blank" id="btn-pdf-document"
           class="btn btn-secondary"><i class="fa fa-print"></i> @lang('bt.pdf')</a>
        @if (config('bt.mailConfigured'))
            <a href="javascript:void(0)" id="btn-email-document" class="btn btn-secondary email-document"
               data-document-id="{{ $document->id }}" data-redirect-to="{{ route('documents.edit', [$document->id]) }}"><i
                        class="fa fa-envelope"></i> @lang('bt.email')</a>
        @endif
        <div class="btn-group">
            <button type="button" class="btn btn-secondary" data-bs-toggle="dropdown">
                @lang('bt.other')
            </button>
            <div class="dropdown-menu dropdown-menu-end" role="menu">
                @if ($document->module_type == 'Invoice' and ($document->isPayable or config('bt.allowPaymentsWithoutBalance')))
                    <button class="dropdown-item"
                            type="button"
                            onclick="window.livewire.emit('showModal', 'modals.create-payment-modal', '{{  addslashes(get_class($document)) }}', {{ $document->id }}, true )"
                    ><i class="fa fa-credit-card"></i> @lang('bt.enter_payment')
                    </button>
                @endif
                <a class="dropdown-item" href="#" id="btn-copy-document"
                   {{--                   params 3 thru ... mount(,,$modulefullname, $module_type, $moduleop, $resource_id = null, $module_id = null, $readonly = null)--}}
                   onclick="window.livewire.emit('showModal', 'modals.create-module-modal', '{{  addslashes(get_class($document)) }}', '{{$document->module_type}}', 'copy', {{ $document->client->id }}, {{ $document->id }})">
                    <i class="fa fa-copy"></i> @lang('bt.copy_'.$document->lower_case_baseclass)</a>
                @if($document->module_type == 'Quote')
                    <a class="dropdown-item" href="javascript:void(0)" id="btn-document-to-workorder"><i
                                class="fa fa-check"></i> @lang('bt.'.$document->lower_case_baseclass.'_to_workorder')
                    </a>
                @endif
                @if($document->module_type != 'Invoice' && $document->module_type != 'Purchaseorder')
                    <a class="dropdown-item" href="javascript:void(0)" id="btn-document-to-invoice"><i
                                class="fa fa-check"></i> @lang('bt.'.$document->lower_case_baseclass.'_to_invoice')
                    </a>
                @endif
                @if($document->module_type != 'Purchaseorder')
                    <a class="dropdown-item"
                       href="{{ route('clientCenter.public.' . $document->lower_case_baseclass .'.show', [$document->url_key]) }}"
                       target="_blank"><i
                                class="fa fa-globe"></i> @lang('bt.public')</a>
                    <div class="dropdown-divider"></div>
                @endif
                <a class="dropdown-item" href="#"
                   onclick="swalConfirm('@lang('bt.trash_record_warning')', '', '{{ route('documents.delete', [$document->id]) }}');"><i
                            class="fa fa-trash-alt text-danger"></i> @lang('bt.trash')</a>
            </div>
        </div>
        <div class="btn-group">
            @if ($returnUrl)
                <a href="{{ $returnUrl }}" class="btn btn-secondary"><i
                            class="fa fa-backward"></i> @lang('bt.back')</a>
            @endif
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-primary btn-save-document"><i
                        class="fa fa-save"></i> @lang('bt.save')</button>
            <button type="button" class="btn btn-primary" data-bs-toggle="dropdown"><i class="fas fa-chevron-down"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" role="menu">
                <a href="#" class="btn-save-document dropdown-item"
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
                <div class="col" id="col-from">
                    @include('documents._edit_from')
                </div>
                <div class="col" id="col-to">
                    @include('documents._edit_to')
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">@lang('bt.summary')</h3>
                        </div>
                        <div class="card-body">
                            {!! Form::text('summary', $document->summary, ['id' => 'summary', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>
            @if($document->module_type == 'Workorder')
                <div class="row g-3 mb-3 align-items-center">
                    <div class="col-sm-1 text-end fw-bold">
                        <label class="col-form-label">@lang('bt.job_date')</label>
                    </div>
                    <div class="col-sm-2">
                        <x-fp_common
                                id="job_date"
                                class="form-control form-control-sm"
                                value="{{$document->job_date}}"></x-fp_common>
                    </div>
                    <div class="col-sm-1 text-end fw-bold">
                        <label for="start_time" class="col-form-label">@lang('bt.start_time')</label>
                    </div>
                    <div class="col-sm-2">
                        <x-fp_time
                                id="start_time"
                                class="form-control form-control-sm"
                                value="{{$document->start_time}}"></x-fp_time>
                    </div>
                    <div class="col-sm-1 text-end fw-bold">
                        <label for="end_time" class="col-form-label">@lang('bt.end_time')</label>
                    </div>
                    <div class="col-sm-2">
                        <x-fp_time
                                id="end_time"
                                class="form-control form-control-sm"
                                value="{{$document->end_time}}"></x-fp_time>
                    </div>
                    <div class="col-sm-2 ms-5 form-check form-switch form-switch-md">
                        {!! Form::checkbox('will_call', 1, $document->will_call, ['id' => 'will_call', 'class' => 'form-check-input']) !!}
                        <label class="form-check-label fw-bold ps-3 pt-1" for="will_call">@lang('bt.will_call')</label>
                    </div>
                </div>
            @endif
            <livewire:items-table :module="$document"/>
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
                                                {!! Form::textarea('terms', $document->terms, ['id' => 'terms', 'class' => 'form-control', 'rows' => 5]) !!}
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label>@lang('bt.footer')</label>
                                                {!! Form::textarea('footer', $document->footer, ['id' => 'footer', 'class' => 'form-control', 'rows' => 5]) !!}
                                            </div>
                                        </div>
                                    </div>
                                    @if ($customFields->count())
                                        <div class="row">
                                            <div class="col-md-12">
                                                @include('custom_fields._custom_fields_unbound', ['object' => $document])
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="tab-pane" id="tab-notes">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            @include('notes._notes', ['object' => $document, 'model' => 'BT\Modules\Documents\Models\Document', 'showPrivateCheckbox' => true, 'hideHeader' => true])
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab-attachments">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            @include('attachments._table', ['object' => $document, 'model' => 'BT\Modules\Documents\Models\Document'])
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
                @include('documents._edit_totals')
            </div>
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">@lang('bt.options')</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label>@lang('bt.'.$document->lower_case_baseclass) #</label>
                        {!! Form::text('number', $document->number, ['id' => 'number', 'class' =>
                        'form-control
                        form-control-sm']) !!}
                    </div>
                    <div class="mb-3">
                        <label>@lang('bt.date')</label>
                        <x-fp_common
                                id="document_date"
                                class="form-control form-control-sm"
                                value="{{$document->document_date}}"></x-fp_common>
                    </div>
                    <div class="mb-3">
                        @if($document->module_type == 'Invoice' || $document->module_type == 'Purchaseorder')
                            <label>@lang('bt.due_date')</label>
                        @else
                            <label>@lang('bt.expires')</label>
                        @endif
                        <x-fp_common
                                id="action_date"
                                class="form-control form-control-sm"
                                value="{{$document->action_date}}"></x-fp_common>
                    </div>
                    <div class="mb-3">
                        <label>@lang('bt.discount')</label>
                        <div class="input-group input-group-sm">
                            {!! Form::text('discount', $document->formatted_numeric_discount, ['id' =>
                            'discount', 'class' => 'form-control form-control-sm']) !!}
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>@lang('bt.currency')</label>
                        {!! Form::select('currency_code', $currencies, $document->currency_code, ['id' =>
                        'currency_code', 'class' => 'form-select form-select-sm']) !!}
                    </div>
                    <div class="mb-3">
                        <label>@lang('bt.exchange_rate')</label>
                        <div class="input-group">
                            {!! Form::text('exchange_rate', $document->exchange_rate, ['id' =>
                            'exchange_rate', 'class' => 'form-control form-control-sm']) !!}
                            <button class="btn btn-sm input-group-text " id="btn-update-exchange-rate" type="button"
                                    data-toggle="tooltip" data-placement="left"
                                    title="@lang('bt.update_exchange_rate')"><i class="fa fa-sync"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>@lang('bt.status')</label>
                        {!! Form::select('document_status_id', $statuses, $document->document_status_id,
                        ['id' => 'document_status_id', 'class' => 'form-select form-select-sm']) !!}
                    </div>
                    <div class="mb-3">
                        <label>@lang('bt.template')</label>
                        {!! Form::select('template', $templates, $document->template,
                        ['id' => 'template', 'class' => 'form-select form-select-sm']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
