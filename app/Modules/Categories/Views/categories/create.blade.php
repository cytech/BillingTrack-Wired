@extends('layouts.master')

@section('content')
    <!--basic form starts-->
    @include('layouts._alerts')
    <section class="app-content-header">
        {!! Form::open(['route' => 'categories.store', 'class'=>'form-horizontal']) !!}

        <div class="card card-light">
            <div class="card-header">
                <div class="card-title h4 mt-2">
                    @lang('bt.create_category')
                </div>
                    <a class="btn btn-warning float-end" href={!! route('categories.index')  !!}><i
                                class="fa fa-ban"></i> @lang('bt.cancel')</a>
                    <button type="submit" class="btn btn-primary float-end"><i
                                class="fa fa-save"></i> @lang('bt.save') </button>
            </div>
            <div class="card-body">
            <!-- Name input-->
                <div class="row col-md-6 mb-3 align-items-center">
                    <div class="col-md-4 text-end">
                        <label class="form-check-label fw-bold"
                           for="name">@lang('bt.name')</label>
                    </div>
                    <div class="col-md-8">
                        {!! Form::text('name',old('name'),['id'=>'name', 'class'=>'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>

        {!! Form::close() !!}
    </section>
@stop
