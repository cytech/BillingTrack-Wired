@extends('layouts.master')

@section('content')
    <!--basic form starts-->
    @include('layouts._alerts')
    <section class="app-content-header">
        {!! Form::model($employees, array('route' => array('employees.update', $employees->id),
                                                        'id'=>'employees_form','action'=>'#','method' => 'PUT', 'class'=>'form-horizontal')) !!}
        <div class="card card-light">
            <div class="card-header">
                <div class="card-title  h4 mt-2"><i
                            class="fa fa-edit fa-fw float-start"></i>
                    @lang('bt.edit_employee')
                </div>
                <a class="btn btn-warning float-end" href="{{ $returnUrl }}"><i
                            class="fa fa-ban"></i> @lang('bt.cancel')</a>
                <button type="submit" class="btn btn-primary float-end"><i
                            class="fa fa-save"></i> @lang('bt.save') </button>
            </div>
            <div class="card-body">
                <!-- Employee Number input-->
                <div class="row col-md-6 mb-3">
                    <div class="col-md-4 text-end">
                    <label class="col-form-label fw-bold"
                           for="number">@lang('bt.employee_number')</label>
                    </div>
                    <div class="col-md-8">
                        {!! Form::text('number',$employees->number,['id'=>'number', 'class'=>'form-control']) !!}
                    </div>
                </div>
                <!-- First Name input-->
                <div class="row col-md-6 mb-3">
                    <div class="col-md-4 text-end">
                    <label class="col-form-label fw-bold"
                           for="first_name">@lang('bt.employee_first_name')</label>
                    </div>
                    <div class="col-md-8">
                        {!! Form::text('first_name',$employees->first_name,['id'=>'first_name', 'class'=>'form-control']) !!}
                    </div>
                </div>
                <!-- Last Name input-->
                <div class="row col-md-6 mb-3">
                    <div class="col-md-4 text-end">
                    <label class="col-form-label fw-bold"
                           for="last_name">@lang('bt.employee_last_name')</label>
                    </div>
                    <div class="col-md-8">
                        {!! Form::text('last_name',$employees->last_name,['id'=>'last_name', 'class'=>'form-control']) !!}
                    </div>
                </div>
                <!-- Title input-->
                <div class="row col-md-6 mb-3">
                    <div class="col-md-4 text-end">
                    <label class="col-form-label fw-bold"
                           for="title">@lang('bt.employee_title')</label>
                    </div>
                    <div class="col-md-8">
                        {!! Form::text('title',$employees->title,['id'=>'title', 'class'=>'form-control',
                        'list'=>'listid']) !!}
                        <datalist id='listid'>
                            @foreach($titles as $title)
                                <option>{!! $title !!}</option>
                            @endforeach
                        </datalist>
                    </div>
                </div>
                <!-- Employee Type input-->
                <div class="row col-md-6 mb-3">
                    <div class="col-md-4 text-end">
                        <label class="col-form-label fw-bold"
                               for="type_id">@lang('bt.type')</label>
                    </div>
                    <div class="col-md-8">
                        {!! Form::select('type_id', $types, null , ['id' => 'type_id', 'class' => 'form-select']) !!}
                    </div>
                </div>
                <!-- Expected Termination Date input-->
                <div class="row col-md-6 mb-3">
                    <div class="col-md-4 text-end">
                        <label class="col-form-label fw-bold"
                               for="term_date">@lang('bt.term_date')</label>
                    </div>
                    <div class="col-md-8">
                        {!! Form::date('term_date', $employees->term_date,['id'=>'term_date', 'class'=>'form-control']) !!}
                    </div>
                </div>
                <!-- Billing Rate input-->
                <div class="row col-md-6 mb-3">
                    <div class="col-md-4 text-end">
                    <label class="col-form-label fw-bold"
                           for="billing_rate">@lang('bt.employee_billing_rate')</label>
                    </div>
                    <div class="col-md-8">
                        {!! Form::text('billing_rate',$employees->billing_rate,['id'=>'billing_rate', 'class'=>'form-control']) !!}
                    </div>
                </div>
                <!-- Schedule Checkbox-->
                <div class="row col-md-6 mb-3 align-items-center">
                    <div class="col-md-4 text-end">
                        <label class="form-check-label fw-bold"
                               for="schedule">@lang('bt.scheduleable')</label>
                    </div>
                    <div class="col-md-8 form-check form-switch form-switch-md ps-5">
                        {!! Form::checkbox('schedule',1,$employees->schedule,['id'=>'schedule', 'class'=>'form-check-input']) !!}
                    </div>
                </div>
                <!-- Active Checkbox-->
                <div class="row col-md-6 mb-3 align-items-center">
                    <div class="col-md-4 text-end">
                        <label class="form-check-label fw-bold"
                               for="schedule">@lang('bt.employee_active')</label>
                    </div>
                    <div class="col-md-8 form-check form-switch form-switch-md ps-5">
                        {!! Form::checkbox('active',1,$employees->active,['id'=>'active', 'class'=>'form-check-input']) !!}
                    </div>
                </div>
                <!-- Driver Checkbox-->
                <div class="row col-md-6 mb-3 align-items-center">
                    <div class="col-md-4 text-end">
                        <label class="form-check-label fw-bold"
                               for="schedule">@lang('bt.employee_driver')</label>
                    </div>
                    <div class="col-md-8 form-check form-switch form-switch-md ps-5">
                        {!! Form::checkbox('driver',1,$employees->driver,['id'=>'driver', 'class'=>'form-check-input']) !!}
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </section>
@stop
