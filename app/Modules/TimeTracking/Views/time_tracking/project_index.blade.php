@extends('layouts.master')

@section('content')
    <section class="app-content-header">
        <div class="container-fluid">
            <div class="col-sm-12">
                <div class="fs-3 float-start">@lang('bt.projects')</div>
                <div class="btn-group float-end">
                    <div class="btn-group">
                        {{ html()->form('GET', route('timeTracking.projects.index'))->attribute('id', 'filter')->open() }}
                        <div class="input-group">
                            {{ html()->select('company_profile', $companyProfiles, request('company_profile'))->class('filter_options form-select w-auto me-1') }}
                            {{ html()->select('status', $statuses, request('status'))->class('filter_options form-select w-auto me-1') }}
                        </div>
                        {{ html()->form()->close() }}
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

