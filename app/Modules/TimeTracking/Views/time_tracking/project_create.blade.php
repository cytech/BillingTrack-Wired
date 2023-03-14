@extends('layouts.master')

@section('content')

    <script type="text/javascript">
        ready(function () {
            document.getElementById('name').focus()
        })
    </script>

    {!! Form::open(['route' => 'timeTracking.projects.store']) !!}

    <section class="app-content-header">
        <h3 class="float-start px-3">
            @lang('bt.create_project')
        </h3>
        <div class="float-end">
            <a class="btn btn-warning float-end" href={!! route('timeTracking.projects.index')  !!}><i
                        class="fa fa-ban"></i> @lang('bt.cancel')</a>
            <button class="btn btn-primary" id="save-btn"><i class="fa fa-save"></i> @lang('bt.save')</button>
        </div>
        <div class="clearfix"></div>
    </section>
    <section class="container-fluid">
        @include('layouts._alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-primary">
                    <div class="card-body" id="create-project">
                        <div class="mb-3">
                            <label>* @lang('bt.project_name'): </label>
                            {!! Form::text('name', null, ['id' => 'name', 'class' => 'form-control']) !!}
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>* @lang('bt.company_profile'):</label>
                                {!! Form::select('company_profile_id', $companyProfiles, config('bt.defaultCompanyProfile'),
                                ['id' => 'company_profile_id', 'class' => 'form-control']) !!}
                            </div>
                            <div class="col-md-4">
                                <label>* @lang('bt.client'):</label>
                                <livewire:client-search
                                        {{-- module base name, adds hidden fields with _id and _name --}}
                                        name="client"
                                        value=""
                                        description=""
                                        placeholder="{{ __('bt.select_or_create_client') }}"
                                        :searchable="true"
                                        noResultsMessage="{{__('bt.client_not_found_create')}}"
                                        :readonly="$readonly ?? null"
                                />
                            </div>
                            <div class="col-md-4">
                                <label>* @lang('bt.due_date'):</label>
                                <x-fp_common
                                        name="due_at"
                                        id="due_at"
                                        class="form-control"
                                ></x-fp_common>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-4">
                                <label>* @lang('bt.hourly_rate'):</label>
                                {!! Form::text('hourly_rate', null, ['id' => 'hourly_rate', 'class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {!! Form::close() !!}
@stop
