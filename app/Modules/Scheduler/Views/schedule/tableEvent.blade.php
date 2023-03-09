@extends('layouts.master')

@section('content')
    <section class="app-content-header">
        <h3 class="float-start px-3">@lang('bt.events')</h3>
        <div class="btn-group float-end">
            <button class="btn btn-primary rounded border"
                    type="button"
                    onclick="window.livewire.emit('showModal', 'modals.create-event-modal')"
            ><i class="fa fa-plus"></i> @lang('bt.create_event')
            </button>
        </div>
        <div class="clearfix"></div>
    </section>
    <section class="content">
        @include('layouts._alerts')
        <div class="card">
            <div class="col-lg-12">
                <div class="card card-light">
                    <div class="card-body">
                        <livewire:data-tables.module-table :module_type="'Schedule'"/>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
