@extends('layouts.master')

@section('javaScript')
    <script type="text/javascript">
        ready(function () {
            addEvent(document, 'click', ".btn-bill-expense", (e) => {
                loadModal('{{ route('expenses.expenseBill.create') }} ', {
                    id: e.target.dataset.expenseId,
                    redirectTo: '{{ request()->fullUrl() }}'
                })
            })
        });
    </script>
@stop

@section('content')
    <section class="app-content-header">
        <div class="container-fluid">
            <div class="col-sm-12">
                <div class="fs-3 float-start">@lang('bt.expenses')</div>
                <div class="btn-group float-end">
                    <div class="btn-group">
                        {!! Form::open(['method' => 'GET', 'id' => 'filter']) !!}
                        <div class="input-group">
                            {!! Form::select('company_profile', $companyProfiles, request('company_profile'), ['class' => 'filter_options form-select w-auto me-1']) !!}
                            {!! Form::select('status', $statuses, request('status'), ['class' => 'filter_options form-select w-auto me-1']) !!}
                            {!! Form::select('category', $categories, request('category'), ['class' => 'filter_options form-select w-auto me-1']) !!}
                            {!! Form::select('vendor', $vendors, request('vendor'), ['class' => 'filter_options form-select w-auto me-1']) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>
                    <a href="{{ route('expenses.create') }}" class="btn btn-primary rounded border"><i
                                class="fa fa-plus"></i> @lang('bt.create_expense')</a>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </section>
    <section class="content">
        @include('layouts._alerts')
        <div class="card ">
            <div class="card-body">
                <livewire:data-tables.module-table :module_type="'Expense'"/>
            </div>
        </div>
    </section>
@stop

