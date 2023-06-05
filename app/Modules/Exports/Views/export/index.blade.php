@extends('layouts.master')

@section('content')
    <section class="app-content-header">
        <div class="container-fluid">
            <div class="col-sm-12">
                <div class="fs-3 float-start">@lang('bt.export_data')</div>
            </div>
            <div class="clearfix"></div>
        </div>
    </section>

    <section class="content">
        <div class="card">
                <div class="card m-2">
                    <div class="card-header d-flex p-0">
                        <ul class="nav nav-tabs" id="setting-tabs">
                            <li class="nav-item"><a class="nav-link active show" data-bs-toggle="tab"
                                                    href="#tab-clients">@lang('bt.clients')</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                                    href="#tab-quotes">@lang('bt.quotes')</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                                    href="#tab-quote-items">@lang('bt.quote_items')</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                                    href="#tab-invoices">@lang('bt.invoices')</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                                    href="#tab-invoice-items">@lang('bt.invoice_items')</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                                    href="#tab-payments">@lang('bt.payments')</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                                    href="#tab-expenses">@lang('bt.expenses')</a></li>
                        </ul>
                    </div>
                    <div class="tab-content m-2">
                        <div id="tab-clients" class="tab-pane active">
                            {{ html()->form('POST', route('export.export', 'Clients'))->attribute('id', 'client-export-form')->target('_blank')->open() }}
                            <div class="mb-3">
                                <label>@lang('bt.format'):</label>
                                {{ html()->select('writer', $writers, null)->class('form-select') }}
                            </div>
                            <button class="btn btn-primary"><i
                                        class="fa fa-download"></i> @lang('bt.export_clients')</button>
                            {{ html()->form()->close() }}
                        </div>
                        <div id="tab-quotes" class="tab-pane">
                            {{ html()->form('POST', route('export.export', 'Quotes'))->attribute('id', 'quote-export-form')->target('_blank')->open() }}
                            <div class="mb-3">
                                <label>@lang('bt.format'):</label>
                                {{ html()->select('writer', $writers, null)->class('form-select') }}
                            </div>
                            <button class="btn btn-primary"><i
                                        class="fa fa-download"></i> @lang('bt.export_quotes')</button>
                            {{ html()->form()->close() }}
                        </div>
                        <div id="tab-quote-items" class="tab-pane">
                            {{ html()->form('POST', route('export.export', 'QuoteItems'))->attribute('id', 'quote-item-export-form')->target('_blank')->open() }}
                            <div class="mb-3">
                                <label>@lang('bt.format'):</label>
                                {{ html()->select('writer', $writers, null)->class('form-select') }}
                            </div>
                            <button class="btn btn-primary"><i
                                        class="fa fa-download"></i> @lang('bt.export_quote_items')</button>
                            {{ html()->form()->close() }}
                        </div>
                        <div id="tab-invoices" class="tab-pane">
                            {{ html()->form('POST', route('export.export', 'Invoices'))->attribute('id', 'invoice-export-form')->target('_blank')->open() }}
                            <div class="mb-3">
                                <label>@lang('bt.format'):</label>
                                {{ html()->select('writer', $writers, null)->class('form-select') }}
                            </div>
                            <button class="btn btn-primary"><i
                                        class="fa fa-download"></i> @lang('bt.export_invoices')</button>
                            {{ html()->form()->close() }}
                        </div>
                        <div id="tab-invoice-items" class="tab-pane">
                            {{ html()->form('POST', route('export.export', 'InvoiceItems'))->attribute('id', 'invoice-item-export-form')->target('_blank')->open() }}
                            <div class="mb-3">
                                <label>@lang('bt.format'):</label>
                                {{ html()->select('writer', $writers, null)->class('form-select') }}
                            </div>
                            <button class="btn btn-primary"><i
                                        class="fa fa-download"></i> @lang('bt.export_invoice_items')</button>
                            {{ html()->form()->close() }}
                        </div>
                        <div id="tab-payments" class="tab-pane">
                            {{ html()->form('POST', route('export.export', 'Payments'))->attribute('id', 'payment-export-form')->target('_blank')->open() }}
                            <div class="mb-3">
                                <label>@lang('bt.format'):</label>
                                {{ html()->select('writer', $writers, null)->class('form-select') }}
                            </div>
                            <button class="btn btn-primary"><i
                                        class="fa fa-download"></i> @lang('bt.export_payments')</button>
                            {{ html()->form()->close() }}
                        </div>
                        <div id="tab-expenses" class="tab-pane">
                            {{ html()->form('POST', route('export.export', 'Expenses'))->attribute('id', 'expense-export-form')->target('_blank')->open() }}
                            <div class="mb-3">
                                <label>@lang('bt.format'):</label>
                                {{ html()->select('writer', $writers, null)->class('form-select') }}
                            </div>
                            <button class="btn btn-primary"><i
                                        class="fa fa-download"></i> @lang('bt.export_expenses')</button>
                            {{ html()->form()->close() }}
                        </div>
                    </div>
                </div>
        </div>
    </section>

@stop
