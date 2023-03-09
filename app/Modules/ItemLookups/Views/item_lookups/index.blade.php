@extends('layouts.master')

@section('content')
    <section class="app-content-header">
        <div class="container-fluid">
            <div class="col-sm-12">
                <div class="fs-3 float-start">@lang('bt.item_lookups')</div>
                <div class="btn-group float-end">
                    <a href="{{ route('itemLookups.create') }}" class="btn btn-primary "><i
                                class="fa fa-plus"></i> @lang('bt.create_itemlookup')</a>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </section>

    <section class="container-fluid">
        @include('layouts._alerts')
        <div class="card card-light">
            <div class="card-body">
                <livewire:data-tables.module-table :module_type="'ItemLookup'"/>
            </div>
        </div>
    </section>
@stop
