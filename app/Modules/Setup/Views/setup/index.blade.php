@extends('setup.master')

@section('content')
    <section class="app-content-header">
        <h1>@lang('bt.license_agreement')</h1>
    </section>
    <section class="content">
        {{ html()->form('POST', route('setup.index'))->open() }}
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class=" card card-light">
                    <div class="card-body">
                        <div class="mb-3">
                            {{ html()->textarea('license', $license)->class('form-control')->cols(50)->rows(10)->isReadonly() }}
                        </div>
                        <div class="mb-3">
                            {{ html()->checkbox('accept', null, 1)->class('form-check-input') }} @lang('bt.license_agreement_accept')
                        </div>
                        {{ html()->submit(__('bt.i_accept'))->class('btn btn-primary') }}
                    </div>
                </div>
            </div>
        </div>
        {{ html()->form()->close() }}
    </section>
@stop
