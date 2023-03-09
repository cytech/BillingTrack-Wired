@extends('layouts.master')

@section('content')

    <script type="text/javascript">
        ready(function () {
            document.getElementById('name').focus()
        })
    </script>

    @if ($editMode == true)
        {!! Form::model($group, ['route' => ['groups.update', $group->id]]) !!}
    @else
        {!! Form::open(['route' => 'groups.store']) !!}
    @endif

    <section class="app-content-header">
        <div class="container-fluid">
            <div class="col-sm-12">
                <div class="fs-3 float-start">@lang('bt.group_form')</div>
                <a class="btn btn-warning float-end" href={!! route('groups.index')  !!}><i
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
                <div class="mb-3">
                    <label>@lang('bt.name'): </label>
                    {!! Form::text('name', null, ['id' => 'name', 'class' => 'form-control']) !!}
                </div>
                <div class="mb-3">
                    <label>@lang('bt.format'): </label>
                    {!! Form::text('format', null, ['id' => 'format', 'class' => 'form-control']) !!}
                    <span class="form-text text-muted">@lang('bt.available_fields'): {NUMBER} {YEAR} {MONTH} {MONTHSHORTNAME} {WEEK}</span>
                </div>
                <div class="mb-3">
                    <label>@lang('bt.next_number'): </label>
                    {!! Form::text('next_id', isset($group->next_id) ? $group->next_id : 1, ['id' => 'next_id', 'class' => 'form-control']) !!}
                </div>
                <div class="mb-3">
                    <label>@lang('bt.left_pad'): </label>
                    {!! Form::text('left_pad', isset($group->left_pad) ? $group->left_pad : 0, ['id' => 'left_pad', 'class' => 'form-control']) !!}
                    <span class="form-text text-muted">@lang('bt.left_pad_description')</span>
                </div>
                <div class="mb-3">
                    <label>@lang('bt.reset_number'): </label>
                    {!! Form::select('reset_number', $resetNumberOptions, null, ['id' => 'reset_number', 'class' => 'form-select']) !!}
                </div>
            </div>
        </div>
    </section>
    {!! Form::close() !!}
@stop
