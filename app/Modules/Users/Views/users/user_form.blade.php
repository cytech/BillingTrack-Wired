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

    @if ($editMode)
        {{ html()->modelForm($user, 'POST', route('users.update', [$user->id, 'user']))->open() }}
    @else
        {{ html()->form('POST', route('users.store', 'user'))->open() }}
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
                            {{ html()->text('name', null)->class('form-control') }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>@lang('bt.email'): </label>
                            {{ html()->text('email', null)->class('form-control') }}
                        </div>
                    </div>
                </div>
{{--                <div class="row">--}}
{{--                    <div class="col-md-3">--}}
{{--                        <div class="mb-3">--}}
{{--                            <label>@lang('bt.avatar'): </label>--}}
{{--                                {{ html()->select('avatar', ['Gravatar'=>'Gravatar', 'Custom'=>'Custom'],'Gravatar')->class('form-select') }}--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
                @if (!$editMode)
                    <div class="mb-3">
                        <label>@lang('bt.password'): </label>
                        {{ html()->password('password')->class('form-control')->attribute('autocomplete', 'new-password') }}
                    </div>
                    <div class="mb-3">
                        <label>@lang('bt.password_confirmation'): </label>
                        {{ html()->password('password_confirmation')->class('form-control') }}
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label>@lang('bt.api_public_key'): </label>
                            {{ html()->text('api_public_key', null)->class('form-control')->isReadonly() }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label>@lang('bt.api_secret_key'): </label>
                            {{ html()->text('api_secret_key', null)->class('form-control')->isReadonly() }}
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
            <div class=" card card-light mt-3">
                <div class="card-header">
                    <h3 class="card-title">@lang('bt.custom_fields')</h3>
                </div>
                <div class="card-body">
                    @if ($editMode)
                        @include('custom_fields._custom_fields', ['object' => $user])
                    @else
                        @include('custom_fields._custom_fields')
                    @endif
                </div>
            </div>
        @endif
    </section>
    @if ($editMode)
        {{ html()->closeModelForm() }}
    @else
        {{ html()->form()->close() }}
    @endif
@stop
