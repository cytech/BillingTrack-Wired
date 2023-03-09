@extends('layouts.master')

@section('content')
    <section class="app-content-header">
        <div class="container-fluid">
            <div class="col-sm-12">
                <div class="fs-3 float-start">@lang('bt.products')</div>
                <div class="btn-group float-end">
                    <a href="{{ route('products.index', ['status' => 'active']) }}"
                       class="btn btn-secondary @if ($status == 'active') active @endif">@lang('bt.active')</a>
                    <a href="{{ route('products.index', ['status' => 'inactive']) }}"
                       class="btn btn-secondary @if ($status == 'inactive') active @endif">@lang('bt.inactive')</a>
                    <a href="{{ route('products.index') }}"
                       class="btn btn-secondary @if ($status == 'all') active @endif">@lang('bt.all')</a>
                    <a href="{{ route('products.create') }}" class="btn btn-primary "><i
                                class="fa fa-plus"></i> @lang('bt.create_product')</a>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </section>
    <section class="container-fluid">
        @include('layouts._alerts')
        <div class="card">
            <div class="card-body">
                <livewire:data-tables.module-table :module_type="'Product'"/>
            </div>
        </div>
    </section>
@stop
