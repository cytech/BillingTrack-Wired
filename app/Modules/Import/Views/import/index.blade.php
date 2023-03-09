@extends('layouts.master')

@section('content')

    {!! Form::open(['route' => 'import.upload', 'files' => true]) !!}

    <section class="app-content-header">
        <div class="container-fluid">
            <div class="col-sm-12">
                <div class="fs-3 float-start">@lang('bt.import_data')</div>
        <div class="float-end">
            @if (!config('app.demo'))
                {!! Form::submit(trans('bt.submit'), ['class' => 'btn btn-primary']) !!}
            @endif
        </div>
        <div class="clearfix"></div>
            </div></div>
    </section>

    <section class="container-fluid">

        @include('layouts._alerts')
        <div class=" card card-light">
            <div class="card-body">
                <div class="mb-3">
                    <label>@lang('bt.what_to_import')</label>
                    {!! Form::select('import_type', $importTypes, null, ['class' => 'form-select']) !!}
                </div>
                <div class="mb-3">
                    <label>@lang('bt.select_file_to_import')</label>
                    @if (!config('app.demo'))
                        {!! Form::file('import_file') !!}
                    @else
                        Imports are disabled in the demo.
                    @endif
                </div>
            </div>
        </div>
    </section>

    {!! Form::close() !!}
@stop
