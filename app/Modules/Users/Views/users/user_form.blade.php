@extends('layouts.master')

@section('content')

    <script type="text/javascript">
        ready(function () {
            document.getElementById('name').focus()

            addEvent(document, 'click', "#btn-generate-api-keys", (e) => {
                axios.post("{{ route('api.generateKeys') }}").then(function (response) {
                    document.getElementById('api_public_key').value = response.data.api_public_key
                    document.getElementById('api_secret_key').value = response.data.api_secret_key
                });
            });

            addEvent(document, 'click', "#btn-clear-api-keys", (e) => {
                document.getElementById('api_public_key').value = ''
                document.getElementById('api_secret_key').value = ''
            })
        });
    </script>

    @if ($editMode == true)
        {!! Form::model($user, ['route' => ['users.update', $user->id, 'user']]) !!}
    @else
        {!! Form::open(['route' => ['users.store', 'user']]) !!}
    @endif

    <section class="app-content-header">
        <div class="container-fluid">
            <div class="col-sm-12">
                <div class="fs-3 float-start">@lang('bt.user_form')</div>
                <a class="btn btn-warning float-end" href={!! route('users.index')  !!}><i
                            class="fa fa-ban"></i> @lang('bt.cancel')</a>
                <button type="submit" class="btn btn-primary float-end"><i
                            class="fa fa-save"></i> @lang('bt.save') </button>
                <div class="clearfix"></div>
            </div>
        </div>
    </section>

    <section class="container-fluid">
        @include('layouts._alerts')
        <div class=" card card-light">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>@lang('bt.name'): </label>
                            {!! Form::text('name', null, ['id' => 'name', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>@lang('bt.email'): </label>
                            {!! Form::text('email', null, ['id' => 'email', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
{{--                <div class="row">--}}
{{--                    <div class="col-md-3">--}}
{{--                        <div class="mb-3">--}}
{{--                            <label>@lang('bt.avatar'): </label>--}}
{{--                            {!! Form::select('avatar', ['Gravatar'=>'Gravatar', 'Custom'=>'Custom'],'Gravatar', ['id' => 'avatar', 'class' => 'form-select']) !!}--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
                @if (!$editMode)
                    <div class="mb-3">
                        <label>@lang('bt.password'): </label>
                        {!! Form::password('password', ['id' => 'password', 'class' => 'form-control', 'autocomplete' => 'new-password']) !!}
                    </div>
                    <div class="mb-3">
                        <label>@lang('bt.password_confirmation'): </label>
                        {!! Form::password('password_confirmation', ['id' => 'password_confirmation',
                        'class' => 'form-control']) !!}
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label>@lang('bt.api_public_key'): </label>
                            {!! Form::text('api_public_key', null, ['id' => 'api_public_key', 'class' => 'form-control', 'readonly' => 'readonly']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label>@lang('bt.api_secret_key'): </label>
                            {!! Form::text('api_secret_key', null, ['id' => 'api_secret_key', 'class' => 'form-control', 'readonly' => 'readonly']) !!}
                        </div>
                    </div>
                </div>
                <div class="mb-5">
                <a href="#" class="btn btn-secondary" id="btn-generate-api-keys">@lang('bt.generate_keys')</a>
                <a href="#" class="btn btn-secondary" id="btn-clear-api-keys">@lang('bt.clear_keys')</a>
                </div>
                @hasrole('superadmin|admin')
                @include('users.user_roles_form', [ 'userType' => 'user'])
                @endhasrole
{{--                @include('users.user_perms_form')--}}
            </div>
        </div>
        @if ($customFields->count())
            <div class=" card card-light">
                <div class="box-header">
                    <h3 class="box-title">@lang('bt.custom_fields')</h3>
                </div>
                <div class="card-body">
                    @include('custom_fields._custom_fields')
                </div>
            </div>
        @endif
    </section>
    {!! Form::close() !!}
@stop
