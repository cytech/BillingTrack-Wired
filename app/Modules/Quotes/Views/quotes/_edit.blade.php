@include('quotes._js_edit')
<section class="app-content-header">
    <h3 class="float-start px-3">@lang('bt.quote') #{{ $quote->number }}</h3>
    @if ($quote->viewed)
        <span style="margin-start: 10px;" class="badge bg-success">@lang('bt.viewed')</span>
    @else
        <span style="margin-start: 10px;" class="badge bg-secondary">@lang('bt.not_viewed')</span>
    @endif

    @if ($quote->invoice()->count())
        @if($quote->invoice->status_text == 'canceled')
            <span class="badge badge-canceled" title="@lang('bt.canceled')"><a
                        href="{{ route('invoices.edit', [$quote->invoice_id]) }}"
                        style="color: inherit;">@lang('bt.converted_to_invoice') {{ $quote->invoice->number }}</a></span>
        @else
            <span class="badge bg-info"><a href="{{ route('invoices.edit', [$quote->invoice_id]) }}"
                                              style="color: inherit;">@lang('bt.converted_to_invoice') {{ $quote->invoice->number }}</a></span>
        @endif
    @elseif ($quote->invoice()->withTrashed()->count())
        <span class="badge bg-danger"
              title="Trashed">@lang('bt.converted_to_invoice') {{ $quote->invoice_id }}</span>
    @endif

    @if ($quote->workorder()->count())
        @if($quote->workorder->status_text == 'canceled')
            <span class="badge badge-canceled" title="@lang('bt.canceled')"><a
                        href="{{ route('workorders.edit', [$quote->workorder_id]) }}"
                        style="color: inherit;">@lang('bt.converted_to_workorder') {{ $quote->workorder->number }}</a></span>
        @else
            <span class="badge bg-info"><a href="{{ route('workorders.edit', [$quote->workorder_id]) }}"
                                              style="color: inherit;">@lang('bt.converted_to_workorder') {{ $quote->workorder->number }}</a></span>
        @endif
    @elseif ($quote->workorder()->withTrashed()->count())
        <span class="badge bg-danger"
              title="Trashed">@lang('bt.converted_to_workorder') {{ $quote->workorder_id }}</span>
    @endif
    <div class="float-end">
        <a href="{{ route('quotes.pdf', [$quote->id]) }}" target="_blank" id="btn-pdf-quote"
           class="btn btn-secondary"><i class="fa fa-print"></i> @lang('bt.pdf')</a>
        @if (config('bt.mailConfigured'))
            <a href="javascript:void(0)" id="btn-email-quote" class="btn btn-secondary email-quote"
               data-quote-id="{{ $quote->id }}" data-redirect-to="{{ route('quotes.edit', [$quote->id]) }}"><i
                        class="fa fa-envelope"></i> @lang('bt.email')</a>
        @endif
        <div class="btn-group">
            <button type="button" class="btn btn-secondary" data-bs-toggle="dropdown">
                @lang('bt.other')
            </button>
            <div class="dropdown-menu dropdown-menu-end" role="menu">
                <a class="dropdown-item" href="#" id="btn-copy-quote"
                   {{--                   params 3 thru ... mount(,,$modulefullname, $moduleop, $resource_id = null, $module_id = null, $readonly = null)--}}
                   onclick="window.livewire.emit('showModal', 'modals.create-module-modal', '{{  addslashes(get_class($quote)) }}', 'copy', {{ $quote->client->id }}, {{ $quote->id }})">
                    <i class="fa fa-copy"></i> @lang('bt.copy_quote')</a>
                <a class="dropdown-item" href="javascript:void(0)" id="btn-quote-to-workorder"><i
                            class="fa fa-check"></i> @lang('bt.quote_to_workorder')</a>
                <a class="dropdown-item" href="javascript:void(0)" id="btn-quote-to-invoice"><i
                            class="fa fa-check"></i> @lang('bt.quote_to_invoice')</a>
                <a class="dropdown-item" href="{{ route('clientCenter.public.quote.show', [$quote->url_key]) }}"
                   target="_blank"><i
                            class="fa fa-globe"></i> @lang('bt.public')</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#"
                   onclick="swalConfirm('@lang('bt.trash_record_warning')', '', '{{ route('quotes.delete', [$quote->id]) }}');"><i
                            class="fa fa-trash-alt"></i> @lang('bt.trash')</a>
            </div>
        </div>
        <div class="btn-group">
            @if ($returnUrl)
                <a href="{{ $returnUrl }}" class="btn btn-secondary"><i
                            class="fa fa-backward"></i> @lang('bt.back')</a>
            @endif
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-primary btn-save-quote"><i
                        class="fa fa-save"></i> @lang('bt.save')</button>
            <button type="button" class="btn btn-primary" data-bs-toggle="dropdown"><i class="fas fa-chevron-down"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" role="menu">
                <a href="#" class="btn-save-quote dropdown-item"
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
                    @include('quotes._edit_from')
                </div>
                <div class="col-sm-6" id="col-to">
                    @include('quotes._edit_to')
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">@lang('bt.summary')</h3>
                        </div>
                        <div class="card-body">
                            {!! Form::text('summary', $quote->summary, ['id' => 'summary', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <livewire:items-table :module="$quote"/>
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
                                                {!! Form::textarea('terms', $quote->terms, ['id' => 'terms', 'class' => 'form-control', 'rows' => 5]) !!}
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label>@lang('bt.footer')</label>
                                                {!! Form::textarea('footer', $quote->footer, ['id' => 'footer', 'class' => 'form-control', 'rows' => 5]) !!}
                                            </div>
                                        </div>
                                    </div>
                                    @if ($customFields->count())
                                        <div class="row">
                                            <div class="col-md-12">
                                                @include('custom_fields._custom_fields_unbound', ['object' => $quote])
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="tab-pane" id="tab-notes">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            @include('notes._notes', ['object' => $quote, 'model' => 'BT\Modules\Quotes\Models\Quote', 'showPrivateCheckbox' => true, 'hideHeader' => true])
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab-attachments">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            @include('attachments._table', ['object' => $quote, 'model' => 'BT\Modules\Quotes\Models\Quote'])
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
                @include('quotes._edit_totals')
            </div>
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">@lang('bt.options')</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label>@lang('bt.quote') #</label>
                        {!! Form::text('number', $quote->number, ['id' => 'number', 'class' =>
                        'form-control
                        form-control-sm']) !!}
                    </div>
                    <div class="mb-3">
                        <label>@lang('bt.date')</label>
                        <x-fp_common
                                id="quote_date"
                                class="form-control form-control-sm"
                                value="{{$quote->quote_date}}"></x-fp_common>
                    </div>
                    <div class="mb-3">
                        <label>@lang('bt.expires')</label>
                        <x-fp_common
                                id="expires_at"
                                class="form-control form-control-sm"
                                value="{{$quote->expires_at}}"></x-fp_common>
                    </div>
                    <div class="mb-3">
                        <label>@lang('bt.discount')</label>
                        <div class="input-group input-group-sm">
                            {!! Form::text('discount', $quote->formatted_numeric_discount, ['id' =>
                            'discount', 'class' => 'form-control form-control-sm']) !!}
                                <span class="input-group-text">%</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>@lang('bt.currency')</label>
                        {!! Form::select('currency_code', $currencies, $quote->currency_code, ['id' =>
                        'currency_code', 'class' => 'form-select form-select-sm']) !!}
                    </div>
                    <div class="mb-3">
                        <label>@lang('bt.exchange_rate')</label>
                        <div class="input-group">
                            {!! Form::text('exchange_rate', $quote->exchange_rate, ['id' =>
                            'exchange_rate', 'class' => 'form-control form-control-sm']) !!}
                                <button class="btn btn-sm input-group-text " id="btn-update-exchange-rate" type="button"
                                        data-toggle="tooltip" data-placement="left"
                                        title="@lang('bt.update_exchange_rate')"><i class="fa fa-sync"></i>
                                </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>@lang('bt.status')</label>
                        {!! Form::select('quote_status_id', $statuses, $quote->quote_status_id,
                        ['id' => 'quote_status_id', 'class' => 'form-select form-select-sm']) !!}
                    </div>
                    <div class="mb-3">
                        <label>@lang('bt.template')</label>
                        {!! Form::select('template', $templates, $quote->template,
                        ['id' => 'template', 'class' => 'form-select form-select-sm']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
