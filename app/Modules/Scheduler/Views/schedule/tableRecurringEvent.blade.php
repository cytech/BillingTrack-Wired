@extends('layouts.master')

@section('content')
    <section class="app-content-header">
        <h3 class="float-start px-3">@lang('bt.recurring_events')</h3>
        <div class="btn-group float-end">
            <a href="{!! route('scheduler.editrecurringevent') !!}" class="btn btn-primary rounded border"><i
                        class="fa fa-fw fa-plus"></i> @lang('bt.create_recurring_event')</a>
        </div>
        <div class="clearfix"></div>
    </section>
    <section class="content">
        @include('layouts._alerts')
        <div class="card">
            <div class="col-lg-12">
                <div class="card card-light">
                    <div class="card-body">
                        <livewire:data-tables.module-table :module_type="'RecurringEvent'"/>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
