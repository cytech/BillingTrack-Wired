@extends('layouts.master')

@section('content')
    <section class="app-content-header">
        <div class="container-fluid">
            <div class="col-sm-12">
                <div class="fs-3 float-start">@lang('bt.projects')</div>
                <div class="btn-group float-end">
                    <div class="btn-group">
                        {!! Form::open(['method' => 'GET', 'id' => 'filter']) !!}
                        <div class="input-group">
                            {!! Form::select('company_profile', $companyProfiles, request('company_profile'), ['class' => 'filter_options form-select w-auto me-1']) !!}
                            {!! Form::select('status', $statuses, request('status'), ['class' => 'filter_options form-select w-auto me-1']) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>
                    <a href="{{ route('timeTracking.projects.create') }}" class="btn btn-primary rounded border"><i
                                class="fa fa-plus"></i> @lang('bt.create_project')</a>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </section>
    <section class="content">
        @include('layouts._alerts')
        <div class="card ">
            <div class="card-body">
                <livewire:data-tables.module-table :module_type="'TimeTrackingProject'"
                                                   :keyedStatuses="$keyedStatuses"/>
            </div>
        </div>
    </section>
@stop

