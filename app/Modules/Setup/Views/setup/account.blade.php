@extends('setup.master')

@section('content')
    <section class="app-content-header">
        <h1>@lang('bt.account_setup')</h1>
        <h2>@lang('bt.account_setup_h1')</h2>
        <h3>@lang('bt.account_setup_h2')</h3>
        <h3>@lang('bt.account_setup_h3')</h3>
    </section>
    <section class="content">
        {{ html()->form('POST', route('setup.postAccount'))->class('form-install')->attribute('autocomplete', 'off')->open() }}
        <div class="row">
            <div class="col-md-12">
                <div class=" card card-light">
                    <div class="card-body">
                        @include('layouts._alerts')
                        <h4>@lang('bt.user_account')</h4>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                {{ html()->text('user[name]', null)->class('form-control')->placeholder('* '.trans('bt.name'))->required()->attribute('autocomplete', 'new-password') }}
                            </div>
                            <div class="col-md-3 mb-3">
                                {{ html()->text('user[email]', null)->class('form-control')->placeholder('* '.trans('bt.email'))->required()->attribute('autocomplete', 'new-password') }}
                            </div>
                            <div class="col-md-3 mb-3">
                                {{ html()->password('user[password]')->class('form-control')->placeholder('* '.trans('bt.password'))->attribute('autocomplete', 'new-password')->required() }}
                            </div>
                            <div class="col-md-3 mb-3">
                                {{ html()->password('user[password_confirmation]')->class('form-control')->placeholder('* '.trans('bt.password_confirmation'))->attribute('autocomplete', 'new-password')->required() }}
                            </div>
                        </div>
                        <h4>@lang('bt.company_profile')</h4>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                {{ html()->text('company_profile[company]', null)->class('form-control')->placeholder('* '.trans('bt.company'))->required() }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                {{ html()->textarea('company_profile[address]', null)->rows(4)->placeholder(trans('bt.address'))->class('form-control') }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    {{ html()->text('company_profile[city]', null)->class('form-control')->placeholder('* '.trans('bt.city')) }}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    {{ html()->text('company_profile[state]', null)->class('form-control')->placeholder('* '.trans('bt.state')) }}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    {{ html()->text('company_profile[zip]', null)->class('form-control')->placeholder('* '.trans('bt.postal_code')) }}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    {{ html()->text('company_profile[country]', null)->class('form-control')->placeholder('* '.trans('bt.country')) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                {{ html()->text('company_profile[phone]', null)->class('form-control')->placeholder('* '.trans('bt.phone')) }}
                            </div>
                            <div class="col-md-3 mb-3">
                                {{ html()->text('company_profile[mobile]', null)->class('form-control')->placeholder('* '.trans('bt.mobile')) }}
                            </div>
                            <div class="col-md-3 mb-3">
                                {{ html()->text('company_profile[fax]', null)->class('form-control')->placeholder('* '.trans('bt.fax')) }}
                            </div>
                            <div class="col-md-3 mb-3">
                                {{ html()->text('company_profile[web]', null)->class('form-control')->placeholder('* '.trans('bt.web')) }}
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">@lang('bt.continue')</button>
                    </div>
                </div>
            </div>
        </div>
        {{ html()->form()->close() }}
    </section>
@stop
