@extends('layouts.master')

@section('content')
    @include('layouts._alerts')
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
            @foreach ($widgets as $widget)
                @if (config('bt.widgetEnabled' . $widget))
                    <div class="col-md-{{ config('bt.widgetColumnWidth' . $widget) }} col-sm-12">
                        @include($widget . 'Widget')
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@stop
