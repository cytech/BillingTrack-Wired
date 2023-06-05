@extends('layouts.master')

@section('content')
    @if ($editMode)
        {{ html()->modelForm($user, 'POST', route('users.update', [$user->id, 'client']))->open() }}
    @else
        {{ html()->form('POST', route('users.store', 'client'))->open() }}
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
                                {{ html()->password('password')->class('form-control')->attribute('autocomplete', 'new-password') }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>@lang('bt.password_confirmation'): </label>
                                {{ html()->password('password_confirmation')->class('form-control') }}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>@lang('bt.name'): </label>
                                {{ html()->text('name', null)->class('form-control')->isReadonly() }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>@lang('bt.email'): </label>
                                {{ html()->text('email', null)->class('form-control')->isReadonly() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
{{--        @if ($customFields->count())--}}
{{--            <div class=" card card-light">--}}
{{--                <div class="box-header">--}}
{{--                    <h3 class="box-title">@lang('bt.custom_fields')</h3>--}}
{{--                </div>--}}
{{--                <div class="card-body">--}}
{{--                    @if ($editMode)--}}
{{--                        @include('custom_fields._custom_fields', ['object' => $user])--}}
{{--                    @else--}}
{{--                        @include('custom_fields._custom_fields')--}}
{{--                    @endif--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        @endif--}}
    </section>
    @if ($editMode)
        {{ html()->closeModelForm() }}
    @else
        {{ html()->form()->close() }}
    @endif
@stop
