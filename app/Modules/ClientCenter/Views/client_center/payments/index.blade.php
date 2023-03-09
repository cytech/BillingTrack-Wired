@extends('client_center.layouts.master')

@section('content')

    <section class="app-content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <div class="fs-3">@lang('bt.payments')</div>
                </div>
            </div>
        </div>
    </section>

    <div class="container-fluid">
        @include('layouts._alerts')
        <div class="row">
            <div class="col-12">
                <div class=" card card-light">
                    <div class="card-body">
                        @include('client_center.payments._table')
                    </div>
                </div>
                <div class="float-end">
                    {!! $payments->render() !!}
                </div>
            </div>
        </div>
    </div>

@stop
