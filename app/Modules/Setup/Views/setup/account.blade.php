@extends('setup.master')

@section('content')
    <section class="app-content-header">
        <h1>@lang('bt.account_setup')</h1>
        <h2>@lang('bt.account_setup_h1')</h2>
        <h3>@lang('bt.account_setup_h2')</h3>
        <h3>@lang('bt.account_setup_h3')</h3>
    </section>
    <section class="content">
        {!! Form::open(['route' => 'setup.postAccount', 'class' => 'form-install', 'autocomplete' => 'off']) !!}
        <div class="row">
            <div class="col-md-12">
                <div class=" card card-light">
                    <div class="card-body">
                        @include('layouts._alerts')
                        <h4>@lang('bt.user_account')</h4>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                {!! Form::text('user[name]', null, ['class' => 'form-control', 'placeholder' => '* '.trans('bt.name'), 'required', 'autocomplete' => 'new-password']) !!}
                            </div>
                            <div class="col-md-3 mb-3">
                                {!! Form::text('user[email]', null, ['class' => 'form-control', 'placeholder' => '* '.trans('bt.email'), 'required', 'autocomplete' => 'new-password']) !!}
                            </div>
                            <div class="col-md-3 mb-3">
                                {!! Form::password('user[password]', ['class' => 'form-control', 'placeholder' => '* '.trans('bt.password'), 'required', 'autocomplete' => 'new-password']) !!}
                            </div>
                            <div class="col-md-3 mb-3">
                                {!! Form::password('user[password_confirmation]', ['class' => 'form-control', 'placeholder' => '* '.trans('bt.password_confirmation'), 'required', 'autocomplete' => 'new-password']) !!}
                            </div>
                        </div>
                        <h4>@lang('bt.company_profile')</h4>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                {!! Form::text('company_profile[company]', null, ['class' => 'form-control', 'placeholder' => '* '.trans('bt.company'), 'required']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                {!! Form::textarea('company_profile[address]', null, ['class' => 'form-control', 'placeholder' => trans('bt.address'), 'rows' => 4]) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    {!! Form::text('company_profile[city]', null, ['id' => 'city', 'class' => 'form-control', 'placeholder' => trans('bt.city')]) !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    {!! Form::text('company_profile[state]', null, ['id' => 'state', 'class' => 'form-control', 'placeholder' => trans('bt.state')]) !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    {!! Form::text('company_profile[zip]', null, ['id' => 'zip', 'class' => 'form-control', 'placeholder' => trans('bt.postal_code')]) !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    {!! Form::text('company_profile[country]', null, ['id' => 'country', 'class' => 'form-control', 'placeholder' => trans('bt.country')]) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                {!! Form::text('company_profile[phone]', null, ['class' => 'form-control', 'placeholder' => trans('bt.phone')]) !!}
                            </div>
                            <div class="col-md-3 mb-3">
                                {!! Form::text('company_profile[mobile]', null, ['class' => 'form-control', 'placeholder' => trans('bt.mobile')]) !!}
                            </div>
                            <div class="col-md-3 mb-3">
                                {!! Form::text('company_profile[fax]', null, ['class' => 'form-control', 'placeholder' => trans('bt.fax')]) !!}
                            </div>
                            <div class="col-md-3 mb-3">
                                {!! Form::text('company_profile[web]', null, ['class' => 'form-control', 'placeholder' => trans('bt.web')]) !!}
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">@lang('bt.continue')</button>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </section>
@stop
