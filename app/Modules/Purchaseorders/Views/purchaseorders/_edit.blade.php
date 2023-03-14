@include('purchaseorders._js_edit')
<section class="app-content-header">
    <h3 class="float-start px-3">@lang('bt.purchaseorder') #{{ $purchaseorder->number }}</h3>
    @if ($purchaseorder->status_text)
        <span style="margin-start: 10px;"
              class="badge badge-{{strtolower($purchaseorder->status_text)}}">@lang('bt.'.$purchaseorder->status_text)</span>
        {{--    @else--}}
        {{--        <span style="margin-start: 10px;" class="badge bg-secondary">@lang('bt.not_viewed')</span>--}}
    @endif

    {{--    @if ($purchaseorder->quote()->count())--}}
    {{--        <span class="badge bg-info"><a href="{{ route('quotes.edit', [$purchaseorder->quote->id]) }}" style="color: inherit;">@lang('bt.converted_from_quote') {{ $purchaseorder->quote->number }}</a></span>--}}
    {{--    @endif--}}

    {{--    @if ($purchaseorder->workorder()->count())--}}
    {{--        <span class="badge bg-info"><a href="{{ route('workorders.edit', [$purchaseorder->workorder->id]) }}" style="color: inherit;">@lang('bt.converted_from_workorder') {{ $purchaseorder->workorder->number }}</a></span>--}}
    {{--    @endif--}}
    <div class="float-end">
        <a href="{{ route('purchaseorders.pdf', [$purchaseorder->id]) }}" target="_blank" id="btn-pdf-purchaseorder"
           class="btn btn-secondary"><i class="fa fa-print"></i> @lang('bt.pdf')</a>
        @if (config('bt.mailConfigured'))
            <a href="javascript:void(0)" id="btn-email-purchaseorder" class="btn btn-secondary email-purchaseorder"
               data-purchaseorder-id="{{ $purchaseorder->id }}"
               data-redirect-to="{{ route('purchaseorders.edit', [$purchaseorder->id]) }}"><i
                        class="fa fa-envelope"></i> @lang('bt.email')</a>
        @endif
        <div class="btn-group">
            <button type="button" class="btn btn-secondary" data-bs-toggle="dropdown">
                @lang('bt.other')
            </button>
            <div class="dropdown-menu dropdown-menu-end" role="menu">
                {{--                @if ($purchaseorder->isPayable or config('bt.allowPaymentsWithoutBalance'))--}}
                {{--                    <a class="dropdown-item enter-payment" href="javascript:void(0)" id="btn-enter-payment"--}}
                {{--                           data-purchaseorder-id="{{ $purchaseorder->id }}"--}}
                {{--                           data-purchaseorder-balance="{{ $purchaseorder->amount->formatted_numeric_balance }}"--}}
                {{--                           data-redirect-to="{{ route('purchaseorders.edit', [$purchaseorder->id]) }}"><i--}}
                {{--                                class="fa fa-credit-card"></i> @lang('bt.enter_payment')</a>--}}
                {{--                @endif--}}
                @if(!in_array($purchaseorder->status_text, ['received', 'draft', 'canceled']))
                    <a class="dropdown-item receive-purchaseorder" href="javascript:void(0)"
                       data-purchaseorder-id="{{ $purchaseorder->id }}"><i
                                class="fa fa-arrow-alt-circle-right"></i> @lang('bt.receive_purchaseorder')</a>
                @endif
                <a class="dropdown-item" href="#" id="btn-copy-purchaseorder"
                   {{--                   params 3 thru ... mount(,,$modulefullname, $moduleop, $resource_id = null, $module_id = null, $readonly = null)--}}
                   onclick="window.livewire.emit('showModal', 'modals.create-module-modal', '{{  addslashes(get_class($purchaseorder)) }}', 'copy', {{ $purchaseorder->vendor->id }}, {{ $purchaseorder->id }})">
                    <i class="fa fa-copy"></i> @lang('bt.copy_purchaseorder')</a>
                {{--                <a class="dropdown-item" href="{{ route('vendorCenter.public.purchaseorder.show', [$purchaseorder->url_key]) }}" target="_blank"><i--}}
                {{--                            class="fa fa-globe"></i> @lang('bt.public')</a>--}}
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#"
                   onclick="swalConfirm('@lang('bt.trash_record_warning')', '', '{{ route('purchaseorders.delete', [$purchaseorder->id]) }}');"><i
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
            <button type="button" class="btn btn-primary btn-save-purchaseorder"><i
                        class="fa fa-save"></i> @lang('bt.save')</button>
            <button type="button" class="btn btn-primary" data-bs-toggle="dropdown"><i class="fas fa-chevron-down"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" role="menu">
                <a class="dropdown-item" href="#" class="btn-save-purchaseorder"
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
                <div class="col-sm-8" id="col-from">
                    @include('purchaseorders._edit_from')
                </div>
                <div class="col-sm-4" id="col-to">
                    @include('purchaseorders._edit_to')
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">@lang('bt.summary')</h3>
                        </div>
                        <div class="card-body">
                            {!! Form::text('summary', $purchaseorder->summary, ['id' => 'summary', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <livewire:items-table :module="$purchaseorder"/>
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
                                {{--                            <li class="nav-item"><a class="nav-link" href="#tab-payments"--}}
                                {{--                                                    data-bs-toggle="tab">@lang('bt.payments')</a></li>--}}
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab-additional">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label>@lang('bt.terms_and_conditions')</label>
                                                {!! Form::textarea('terms', $purchaseorder->terms, ['id' => 'terms', 'class' => 'form-control', 'rows' => 5]) !!}
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label>@lang('bt.footer')</label>
                                                {!! Form::textarea('footer', $purchaseorder->footer, ['id' => 'footer', 'class' => 'form-control', 'rows' => 5]) !!}
                                            </div>
                                        </div>
                                    </div>
                                    @if ($customFields->count())
                                        <div class="row">
                                            <div class="col-md-12">
                                                @include('custom_fields._custom_fields_unbound', ['object' => $purchaseorder])
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="tab-pane" id="tab-notes">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            @include('notes._notes', ['object' => $purchaseorder, 'model' => 'BT\Modules\Purchaseorders\Models\Purchaseorder', 'showPrivateCheckbox' => true, 'hideHeader' => true])
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab-attachments">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            @include('attachments._table', ['object' => $purchaseorder, 'model' => 'BT\Modules\Purchaseorders\Models\Purchaseorder'])
                                        </div>
                                    </div>
                                </div>
                                {{--                            <div class="tab-pane" id="tab-payments">--}}
                                {{--                                <table class="table table-hover">--}}

                                {{--                                    <thead>--}}
                                {{--                                    <tr>--}}
                                {{--                                        <th>@lang('bt.payment_date')</th>--}}
                                {{--                                        <th>@lang('bt.amount')</th>--}}
                                {{--                                        <th>@lang('bt.payment_method')</th>--}}
                                {{--                                        <th>@lang('bt.note')</th>--}}
                                {{--                                    </tr>--}}
                                {{--                                    </thead>--}}

                                {{--                                    <tbody>--}}
                                {{--                                    @foreach ($purchaseorder->payments as $payment)--}}
                                {{--                                        <tr>--}}
                                {{--                                            <td>{{ $payment->formatted_paid_at }}</td>--}}
                                {{--                                            <td>{{ $payment->formatted_amount }}</td>--}}
                                {{--                                            <td>@if ($payment->paymentMethod) {{ $payment->paymentMethod->name }} @endif</td>--}}
                                {{--                                            <td>{{ $payment->note }}</td>--}}
                                {{--                                        </tr>--}}
                                {{--                                    @endforeach--}}
                                {{--                                    </tbody>--}}

                                {{--                                </table>--}}
                                {{--                            </div>--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            <div id="div-totals">
                @include('purchaseorders._edit_totals')
            </div>
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">@lang('bt.options')</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label>@lang('bt.purchaseorder') #</label>
                        {!! Form::text('number', $purchaseorder->number, ['id' => 'number', 'class' =>
                        'form-control
                        form-control-sm']) !!}
                    </div>
                    <div class="mb-3">
                        <label>@lang('bt.date')</label>
                        <x-fp_common
                                id="purchaseorder_date"
                                class="form-control form-control-sm"
                                value="{{$purchaseorder->purchaseorder_date}}"></x-fp_common>
                    </div>
                    <div class="mb-3">
                        <label>@lang('bt.due_date')</label>
                        <x-fp_common
                                id="due_at"
                                class="form-control form-control-sm"
                                value="{{$purchaseorder->due_at}}"></x-fp_common>
                    </div>
                    <div class="mb-3">
                        <label>@lang('bt.discount')</label>
                        <div class="input-group input-group-sm">
                            {!! Form::text('discount', $purchaseorder->formatted_numeric_discount, ['id' =>
                            'discount', 'class' => 'form-control form-control-sm']) !!}
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>@lang('bt.currency')</label>
                        {!! Form::select('currency_code', $currencies, $purchaseorder->currency_code, ['id' =>
                        'currency_code', 'class' => 'form-select form-select-sm']) !!}
                    </div>
                    <div class="mb-3">
                        <label>@lang('bt.exchange_rate')</label>
                        <div class="input-group">
                            {!! Form::text('exchange_rate', $purchaseorder->exchange_rate, ['id' =>
                            'exchange_rate', 'class' => 'form-control form-control-sm']) !!}
                            <button class="btn btn-sm input-group-text " id="btn-update-exchange-rate" type="button"
                                    data-toggle="tooltip" data-placement="left"
                                    title="@lang('bt.update_exchange_rate')"><i class="fa fa-sync"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>@lang('bt.status')</label>
                        {!! Form::select('purchaseorder_status_id', $statuses, $purchaseorder->purchaseorder_status_id,
                        ['id' => 'purchaseorder_status_id', 'class' => 'form-select form-select-sm']) !!}
                    </div>
                    <div class="mb-3">
                        <label>@lang('bt.template')</label>
                        {!! Form::select('template', $templates, $purchaseorder->template,
                        ['id' => 'template', 'class' => 'form-select form-select-sm']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
