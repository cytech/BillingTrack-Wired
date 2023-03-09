@extends('layouts.master')

@section('content')
    @if ($editMode == true)
        {!! Form::model($user, ['route' => ['users.update', $user->id, 'client']]) !!}
    @else
        {!! Form::open(['route' => ['users.store', 'client']]) !!}
    @endif

    <section class="app-content-header">
        <div class="container-fluid">
            <div class="col-sm-12">
                <div class="fs-3 float-start">@lang('bt.client') @lang('bt.user_form')</div>
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
            <div class="card-body" id="create-user">
                @if (!$editMode)
                    <div class="mb-3">
                        <label>@lang('bt.client'):</label>
                        <livewire:client-search
                                {{-- module base name, adds hidden fields with _id and _name --}}
                                name="client"
                                value=""
                                description=""
                                placeholder="{{ __('bt.select_client') }}"
                                :searchable="true"
                                noResultsMessage="{{__('bt.client_not_found_create')}}"
                                :readonly="$readonly ?? null"
                                extras="clientuserfilter"
                        />
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>@lang('bt.password'): </label>
                                {!! Form::password('password', ['id' => 'password', 'class' => 'form-control', 'autocomplete' => 'new-password']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>@lang('bt.password_confirmation'): </label>
                                {!! Form::password('password_confirmation', ['id' => 'password_confirmation',
                                'class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>@lang('bt.name'): </label>
                                {!! Form::text('name', null, ['id' => 'name', 'class' => 'form-control', 'readonly' => 'readonly']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>@lang('bt.email'): </label>
                                {!! Form::text('email', null, ['id' => 'email', 'class' => 'form-control', 'readonly' => 'readonly']) !!}
                            </div>
                        </div>
                    </div>
                @endif
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
