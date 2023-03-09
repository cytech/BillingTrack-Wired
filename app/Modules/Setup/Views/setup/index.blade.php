@extends('setup.master')

@section('content')
    <section class="app-content-header">
        <h1>@lang('bt.license_agreement')</h1>
    </section>
    <section class="content">
        {!! Form::open() !!}
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class=" card card-light">
                    <div class="card-body">
                        <div class="mb-3">
                            {!! Form::textarea('', $license, ['id' => 'license', 'class' => 'form-control', 'readonly' => 'readonly']) !!}
                        </div>
                        <div class="mb-3">
                            {!! Form::checkbox('accept', 1, null, ['class' => 'form-check-input']) !!} @lang('bt.license_agreement_accept')
                        </div>
                        {!! Form::submit(trans('bt.i_accept'), ['class' => 'btn btn-primary']) !!}
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </section>
@stop
