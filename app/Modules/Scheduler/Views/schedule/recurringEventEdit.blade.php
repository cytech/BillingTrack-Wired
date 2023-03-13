@extends('layouts.master')

@section('content')
    @include('layouts._alerts')
    <section class="app-content-header">
        <div class="container-fluid m-2">
            {!! Form::model($schedule,['route' => ['scheduler.updaterecurringevent', $schedule->id],'id' => 'recurringevent', 'accept-charset' => 'utf-8']) !!}
            <div class="card card-light">
                <div class="card-header">
                    <h3 class="card-title"><i
                                class="fa fa-edit fa-fw"></i> {{ trans('bt.'.$title) }}
                    </h3>
                    <a class="btn btn-warning float-end" href={!! url('/scheduler/table_recurring_event')  !!}><i
                                class="fa fa-ban"></i> @lang('bt.cancel') </a>
                    <button type="submit" class="btn btn-primary float-end"><i
                                class="fa fa-save"></i> {{ trans('bt.'.$title) }} </button>
                </div>
                <div class="card-body">
                    <div class="mb-3 d-flex align-items-center">
                        {!! Form::label('title',trans('bt.title'),['class'=>'col-sm-2 text-end fw-bold pe-3']) !!}
                        <div class="col-sm-6">
                            <livewire:employee-search
                                    {{-- module base name, adds hidden fields with _id and _name --}}
                                    wire:onload="$emit('refreshSearch', ['searchTerm' => null, 'value' => null, 'description' => null, 'optionsValues' => null]);"
                                    name="employee"
                                    value="{{ $schedule->employee_id ?? null}}"
                                    description="{{ $schedule->employee_name }}"
                                    placeholder="{{ __('bt.placeholder_employee_select') }}"
                                    :searchable="true"
                                    noResultsMessage="{{  __('bt.no_results_employee') }}"
                            />
                        </div>
                    </div>
                    <div class="mb-3 d-flex align-items-center">
                        <label for="location" class="col-sm-2 text-end fw-bold pe-3">@lang('bt.location')</label>
                        <div class="col-sm-6">
                            {!! Form::text('location_str',null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                    <div class="mb-3 d-flex align-items-center">
                        {!! Form::label('description',trans('bt.description'),['class'=>'col-sm-2 text-end fw-bold pe-3']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('description',null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                    <div class="mb-3 d-flex align-items-center">
                        {!! Form::label('category_id',trans('bt.category'),['class'=>'col-sm-2 text-end fw-bold pe-3']) !!}
                        <div class="col-sm-3">
                            {!! Form::select('category_id',$categories,null, ['id' => 'category_id','class'=>'form-control']) !!}
                        </div>
                    </div>
                    <div class="row g-3 mb-3 align-items-center ">
                            <label for="reminder_qty" class="col-sm-2 text-end fw-bold pe-3">@lang('bt.reminder')</label>
                        <div class="col-sm-2">
                            <input type="number" id="reminder_qty" name="reminder_qty"
                                   class="form-control" min="0"
                                   value="0"/></div>
                        <div class="col-auto">
                            <select id="reminder_interval" name="reminder_interval"
                                    class="form-select">
                                @foreach($reminder_interval as $key => $value)
                                    <option value="{{$key}}" @if($loop->first) selected @endif>{{$value}}</option>
                                @endforeach
                            </select></div>
                        <div class="col-auto">
                            <label class="fw-bold">@lang('bt.before_event_start')</label></div>
                    </div>
                    @include('partials._rrule_editdialog')
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </section>
@stop
@section('javaScript')
    <script type="text/javascript">
        ready(function () {
            document.getElementById('employee').focus()
            // handle readOnly of reminder_qty input
            var form = document.getElementById('recurringevent'),
                interval = form.elements.reminder_interval;

            if (form.elements.reminder_interval.value === 'none') {
                form.elements.reminder_qty.readOnly = true;
            }

            interval.onchange = function () {
                var form = this.form;
                if (this.selectedIndex === 0) {
                    form.elements.reminder_qty.value = 0;
                    form.elements.reminder_qty.readOnly = true;
                } else {
                    form.elements.reminder_qty.readOnly = false;
                }
            };
        });
    </script>
@stop
