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
                <livewire:data-tables.module-table :module_type="'Expense'" :module_fullname="$modulefullname"/>
            </div>
        </div>
    </section>
@stop

