@extends('layouts.master')

@section('content')
    @if ($editMode == true)
        {!! Form::model($expense, ['route' => ['expenses.update', $expense->id], 'files' => true]) !!}
    @else
        {!! Form::open(['route' => 'expenses.store', 'files' => true]) !!}
    @endif

    {!! Form::hidden('user_id', auth()->user()->id) !!}

    <section class="app-content-header">
        <h3 class="float-start px-3">
            @lang('bt.expense_form')
        </h3>
        <div class="float-end">
            <a class="btn btn-warning float-end" href={!! route('expenses.index')  !!}><i
                        class="fa fa-ban"></i> @lang('bt.cancel')</a>
            <button class="btn btn-primary" id="save-btn"><i class="fa fa-save"></i> @lang('bt.save')</button>
        </div>
        <div class="clearfix"></div>
    </section>
    <section class="container-fluid">
        @include('layouts._alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="card card-light">
                    <div class="card-body" id="create-expense">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label>* @lang('bt.company_profile'): </label>
                                    {!! Form::select('company_profile_id', $companyProfiles, (($editMode) ? $expense->company_profile_id : config('bt.defaultCompanyProfile')), ['id' => 'company_profile_id', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label>* @lang('bt.date'): </label>
                                    <x-fp_common
                                            name="expense_date"
                                            id="expense_date"
                                            class="form-control"
                                            value="{{(($editMode) ? $expense->expense_date : $currentDate)}}"></x-fp_common>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label>* @lang('bt.category'): </label>
                                    {!! Form::text('category_name', null, ['id' => 'category_name', 'class' => 'form-control','list'=>'catlistid']) !!}
                                    <datalist id='catlistid'>
                                        @foreach($categories as $category)
                                            <option>{!! $category !!}</option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label>* @lang('bt.amount'): </label>
                                    {!! Form::text('amount', (($editMode) ? $expense->formatted_numeric_amount : null), ['id' => 'amount', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label>@lang('bt.tax'): </label>
                                    {!! Form::text('tax', (($editMode) ? $expense->formatted_numeric_tax : null), ['id' => 'amount', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label> @lang('bt.vendor'): </label>
                                    <livewire:vendor-search
                                            name="vendor"
                                            value="{!! $editMode ? $expense->vendor_id : null !!}"
                                            description="{!! $editMode ? $expense->vendor->name ?? null : null !!}"
                                            placeholder="{{ __('bt.select_or_create_vendor') }}"
                                            :searchable="true"
                                            noResultsMessage="{{__('bt.vendor_not_found_create')}}"
                                            :readonly="$readonly ?? null"
                                    />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label> @lang('bt.client'): </label>
                                    <livewire:client-search
                                            {{-- module base name, adds hidden fields with _id and _name --}}
                                            name="client"
                                            value="{!! $editMode ? $expense->client_id : null !!}"
                                            description="{!! $editMode ? $expense->client->name ?? null : null !!}"
                                            placeholder="{{ __('bt.select_or_create_client') }}"
                                            :searchable="true"
                                            noResultsMessage="{{__('bt.client_not_found_create')}}"
                                            :readonly="$readonly ?? null"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>@lang('bt.description'): </label>
                            {!! Form::textarea('description', null, ['id' => 'description', 'rows' => '5', 'class' => 'form-control']) !!}
                        </div>
                        @if ($customFields->count())
                            @include('custom_fields._custom_fields')
                        @endif

                        @if (!$editMode)
                            @if (!config('app.demo'))
                                <div class="mb-3">
                                    <label>@lang('bt.attach_files'): </label>
                                    {!! Form::file('attachments[]', ['id' => 'attachments', 'class' => 'form-control', 'multiple' => 'multiple']) !!}
                                </div>
                            @endif
                        @else
                            @include('attachments._table', ['object' => $expense, 'model' => 'BT\Modules\Expenses\Models\Expense'])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    {!! Form::close() !!}
@stop
