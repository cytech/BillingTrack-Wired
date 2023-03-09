@extends('client_center.layouts.master')

@section('content')

    <section class="app-content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <div class="fs-3">@lang('bt.dashboard')</div>
                </div>
            </div>
        </div>
    </section>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class=" card card-light">
                    <div class="card-header">
                        <h4 class="card-title">@lang('bt.recent_quotes')</h4>
                    </div>
                    @if (count($quotes))
                        <div class="card-body">
                            @include('client_center.quotes._table')
                            <p style="text-align: center;"><a href="{{ route('clientCenter.quotes') }}"
                                                              class="btn btn-secondary">@lang('bt.view_all')</a></p>
                        </div>
                    @else
                        <div class="card-body">
                            <p>@lang('bt.no_records_found')</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class=" card card-light">
                    <div class="card-header">
                        <h4 class="card-title">@lang('bt.recent_workorders')</h4>
                    </div>
                    @if (count($workorders))
                        <div class="card-body">
                            @include('client_center.workorders._table')
                            <p style="text-align: center;"><a href="{{ route('clientCenter.workorders') }}"
                                                              class="btn btn-secondary">@lang('bt.view_all')</a></p>
                        </div>
                    @else
                        <div class="card-body">
                            <p>@lang('bt.no_records_found')</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class=" card card-light">
                    <div class="card-header">
                        <h4 class="card-title">@lang('bt.recent_invoices')</h4>
                    </div>
                    @if (count($invoices))
                        <div class="card-body">
                            @include('client_center.invoices._table')
                            <p style="text-align: center;"><a href="{{ route('clientCenter.invoices') }}"
                                                              class="btn btn-secondary">@lang('bt.view_all')</a></p>
                        </div>
                    @else
                        <div class="card-body">
                            <p>@lang('bt.no_records_found')</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class=" card card-light">
                    <div class="card-header">
                        <h4 class="card-title">@lang('bt.recent_payments')</h4>
                    </div>
                    @if (count($payments))
                        <div class="card-body">
                            @include('client_center.payments._table')
                            <p style="text-align: center;"><a href="{{ route('clientCenter.payments') }}"
                                                              class="btn btn-secondary">@lang('bt.view_all')</a></p>
                        </div>
                    @else
                        <div class="card-body">
                            <p>@lang('bt.no_records_found')</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@stop
