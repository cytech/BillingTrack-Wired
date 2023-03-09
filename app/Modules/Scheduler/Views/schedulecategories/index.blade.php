@extends('layouts.master')

@section('content')
    <section class="app-content-header">
        <div class="container-fluid">
            <div class="col-sm-12">
                <div class="fs-3 float-start">@lang('bt.categories')</div>
                <div class="btn-group float-end">
                    <a href="{!! route('scheduler.categories.create') !!}" class="btn btn-primary rounded border"><i
                                class="fas fa-plus"></i> @lang('bt.create_category')</a>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </section>
    <section class="container-fluid">
        @include('layouts._alerts')
        <div class="card card-light">
            <div class="card-body">
                <livewire:data-tables.module-table :module_type="'ScheduleCategory'"/>
            </div>
        </div>
    </section>
@stop
