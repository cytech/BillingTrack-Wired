@include('recurring_invoices._js_edit')
<section class="app-content-header">
    <h3 class="float-start px-3">@lang('bt.recurring_invoice') #{{ $recurringInvoice->id }}</h3>
    <div class="float-end">
        <div class="btn-group">
            <button type="button" class="btn btn-secondary" data-bs-toggle="dropdown">
                @lang('bt.other')
            </button>
            <div class="dropdown-menu dropdown-menu-end" role="menu">
                <a class="dropdown-item" href="#" id="btn-copy-recurring-invoice"
                   {{--                   params 3 thru ... mount(,,$modulefullname, $moduleop, $resource_id = null, $module_id = null, $readonly = null)--}}
                   onclick="window.livewire.emit('showModal', 'modals.create-module-modal', '{{  addslashes(get_class($recurringInvoice)) }}', 'copy', {{ $recurringInvoice->client->id }}, {{ $recurringInvoice->id }})">
                    <i class="fa fa-copy"></i> @lang('bt.copy_recurring_invoice')</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#"
                   onclick="swalConfirm('@lang('bt.trash_record_warning')', '', '{{ route('recurringInvoices.delete', [$recurringInvoice->id]) }}');"><i
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
            <button type="button" class="btn btn-primary btn-save-recurring-invoice"><i
                        class="fa fa-save"></i> @lang('bt.save')</button>
            <button type="button" class="btn btn-primary" data-bs-toggle="dropdown"><i class="fas fa-chevron-down"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" role="menu">
                <a class="dropdown-item" href="#" class="btn-save-recurring-invoice"
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
                    @include('recurring_invoices._edit_from')
                </div>
                <div class="col-sm-6" id="col-to">
                    @include('recurring_invoices._edit_to')
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">@lang('bt.summary')</h3>
                        </div>
                        <div class="card-body">
                            {!! Form::text('summary', $recurringInvoice->summary, ['id' => 'summary', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <livewire:items-table :module="$recurringInvoice" />
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-outline card-primary m-2">
                        <div class="card-header d-flex p-0">
                            <ul class="nav nav-tabs p-2">
                                <li class="nav-item"><a class="nav-link active show" href="#tab-additional"
                                                        data-bs-toggle="tab">@lang('bt.additional')</a></li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab-additional">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label>@lang('bt.terms_and_conditions')</label>
                                                {!! Form::textarea('terms', $recurringInvoice->terms, ['id' => 'terms', 'class' => 'form-control', 'rows' => 5]) !!}
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label>@lang('bt.footer')</label>
                                                {!! Form::textarea('footer', $recurringInvoice->footer, ['id' => 'footer', 'class' => 'form-control', 'rows' => 5]) !!}
                                            </div>
                                        </div>
                                    </div>
                                    @if ($customFields->count())
                                        <div class="row">
                                            <div class="col-md-12">
                                                @include('custom_fields._custom_fields_unbound', ['object' => $recurringInvoice])
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            <div id="div-totals">
                @include('recurring_invoices._edit_totals')
            </div>
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">@lang('bt.options')</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label>@lang('bt.next_date')</label>
                        <x-fp_common
                                id="next_date"
                                class="form-control form-control-sm"
                                value="{{$recurringInvoice->next_date}}"
                        ></x-fp_common>
                    </div>
                    <div class="mb-3">
                        <label>@lang('bt.every')</label>
                        <div class="row">
                            <div class="col-5">
                                {!! Form::select('recurring_frequency', array_combine(range(1, 90), range(1, 90)), $recurringInvoice->recurring_frequency, ['id' => 'recurring_frequency', 'class' => 'form-select form-select-sm']) !!}
                            </div>
                            <div class="col-7">
                                {!! Form::select('recurring_period', $frequencies, $recurringInvoice->recurring_period, ['id' => 'recurring_period', 'class' => 'form-select form-select-sm']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>@lang('bt.stop_date')</label>
                        <x-fp_common
                                id="stop_date"
                                class="form-control form-control-sm"
                                value="{{$recurringInvoice->stop_date == '0000-00-00' ? '' : $recurringInvoice->stop_date}}"
                        ></x-fp_common>
                    </div>
                    <div class="mb-3">
                        <label>@lang('bt.discount')</label>
                        <div class="input-group input-group-sm">
                            {!! Form::text('discount', $recurringInvoice->formatted_numeric_discount, ['id' =>
                            'discount', 'class' => 'form-control form-control-sm']) !!}
                                <span class="input-group-text">%</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>@lang('bt.currency')</label>
                        {!! Form::select('currency_code', $currencies, $recurringInvoice->currency_code, ['id' =>
                        'currency_code', 'class' => 'form-select form-select-sm']) !!}
                    </div>
                    <div class="mb-3">
                        <label>@lang('bt.exchange_rate')</label>
                        <div class="input-group">
                            {!! Form::text('exchange_rate', $recurringInvoice->exchange_rate, ['id' => 'exchange_rate', 'class' => 'form-control form-control-sm']) !!}
                                <button class="btn btn-sm input-group-text " id="btn-update-exchange-rate" type="button"
                                        data-toggle="tooltip" data-placement="left"
                                        title="@lang('bt.update_exchange_rate')">
                                    <i class="fa fa-sync"></i>
                                </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>@lang('bt.group')</label>
                        {!! Form::select('group_id', $groups, $recurringInvoice->group_id, ['id' => 'group_id', 'class' => 'form-select form-select-sm']) !!}
                    </div>
                    <div class="mb-3">
                        <label>@lang('bt.template')</label>
                        {!! Form::select('template', $templates, $recurringInvoice->template, ['id' => 'template', 'class' => 'form-select form-select-sm']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
