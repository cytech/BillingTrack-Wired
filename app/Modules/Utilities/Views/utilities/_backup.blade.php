@extends('layouts.master')

@section('content')
    @include('layouts._alerts')

    <section class="app-content-header">
        <div class="container-fluid">
            <div class="col-sm-12">
                <div class="fs-3 float-start">@lang('bt.database')</div>
                <div class="clearfix"></div>
            </div>
        </div>
    </section>
    <section class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <h3>@lang('bt.database_backup')</h3>
                        @if (!config('app.demo'))
                            <a href="{{ route('utilities.backup.database') }}" target="_blank"
                               class="btn bg-green"><i
                                        class="fas fa-download pe-1"></i> @lang('bt.download_database_backup')</a>
                        @else
                            <p>Database backup not available in demo.</p>
                        @endif
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <h3>@lang('bt.set_clients_inactive')</h3>
                        @if (!config('app.demo'))
                            {!! Form::open(['route' => 'utilities.clientprior.database','method' => 'get', 'id' => 'clientprior']) !!}
                            <div class="col-md-6 mb-3">@lang('bt.set_clients_inactive_msg')</div>
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <label class="form-label fw-bold">@lang('bt.set_clients_inactive_date')</label>
                                    <div class="input-group">
                                        {!! Form::text('clientprior_date', Carbon\Carbon::parse('first day of january')->subYears(2)->format('m/d/Y'),
                                         ['id' => 'clientprior_date', 'class' => 'form-control ']) !!}
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i> </span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" id="btnSubmitClient" class="btn btn-danger float-end"><i
                                                class="fa fa-exclamation pe-1"></i>@lang('bt.execute_now')
                                    </button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        @else
                            <p>Database clientinactive not available in demo.</p>
                        @endif
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <h3>@lang('bt.database_entities_trash')</h3>
                        @if (!config('app.demo'))
                            {!! Form::open(['route' => 'utilities.trashprior.database','method' => 'get', 'id' => 'tprior']) !!}
                            {{--            quotes workorders invoices payments purchaseorders schedule--}}
                            <div class="col-md-6 mb-3">
                                @lang('bt.database_entities_trash_msg')
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label fw-bold">@lang('bt.trash_before_date')</label>
                                <div class="input-group">
                                    {!! Form::text('trashprior_date', Carbon\Carbon::parse('first day of january')->subYears(2)->format('m/d/Y'),
                                     ['id' => 'trashprior_date', 'class' => 'form-control ']) !!}
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i> </span>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <label class="form-label fw-bold">@lang('bt.module_to_trash')</label>
                                    {!! Form::select('trashprior_module', ['Quote'=>'Quotes', 'Workorder'=>'Workorders',
                                     'Invoice'=>'Invoices', 'Purchaseorder'=>'Purchaseorders',
                                      'Schedule'=>'Schedule', 'Payment' => 'Payments'], null, ['class' => 'form-select']) !!}
                                </div>
                                <div class="col-md-4">
                                    <button type="button" id="btnSubmitTrash" class="btn btn-danger float-end"><i
                                                class="fa fa-trash pe-1"></i>@lang('bt.trash_now')
                                    </button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        @else
                            <p>Database trashprior not available in demo.</p>
                        @endif
                    </div>
                </div>
                <hr>
                <h3>@lang('bt.trashed_current_count')</h3>
                <div class="col-md-12">
                    @lang('bt.trashed_quote_count'){{$quotecount}}<br>
                    @lang('bt.trashed_workorder_count'){{$workordercount}}<br>
                    @lang('bt.trashed_invoice_count'){{$invoicecount}}<br>
                    @lang('bt.trashed_purchaseorder_count'){{$purchaseordercount}}<br>
                    @lang('bt.trashed_schedule_count'){{$schedulecount}}<br>
                    @lang('bt.trashed_payment_count'){{$paymentcount}}<br>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <h3>@lang('bt.database_entities_delete_trash')</h3>
                        @if (!config('app.demo'))
                            {!! Form::open(['route' => 'utilities.deleteprior.database','method' => 'get', 'id' => 'dprior']) !!}
                            {{--            quotes workorders invoices payments purchaseorders schedule--}}
                            <div class="col-md-6 mb-3">
                                @lang('bt.database_entities_delete_trash_msg')
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label fw-bold">@lang('bt.delete_before_date')</label>
                                <div class="input-group">
                                    {!! Form::text('deleteprior_date', Carbon\Carbon::parse('first day of january')->subYears(2)->format('m/d/Y'),
                                     ['id' => 'deleteprior_date', 'class' => 'form-control form-control-sm']) !!}
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i> </span>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <label class="form-label fw-bold">@lang('bt.trashed_module_delete')</label>
                                    {!! Form::select('deleteprior_module', ['Quote'=>'Quotes', 'Workorder'=>'Workorders',
                                     'Invoice'=>'Invoices', 'Purchaseorder'=>'Purchaseorders',
                                      'Schedule'=>'Schedule', 'Payment' => 'Payments'], null, ['class' => 'form-select']) !!}
                                </div>
                                <div class="col-md-4">
                                    <button type="button" id="btnSubmitDelete" class="btn btn-danger float-end"><i
                                                class="fa fa-ban pe-1"></i>@lang('bt.delete_now')
                                    </button>
                                </div>
                            </div>
                        @else
                            <p>Database deleteprior not available in demo.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@section('javaScript')
    <script>
        ready(function () {
            document.querySelectorAll('#clientprior_date,#trashprior_date,#deleteprior_date').flatpickr({
                altFormat: "{{config('bt.dateFormat')}}",
                dateFormat: 'Y-m-d',
                altInput: true,
                position: "auto center",
                defaultDate: new Date(new Date().getFullYear() - 2, 0, 1)
            });

            addEvent(document, 'click', "#btnSubmitClient,#btnSubmitTrash,#btnSubmitDelete", (e) => {
                Swal.fire({
                    title: 'Are You Sure?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d68500',
                    confirmButtonText: '@lang('bt.yes_sure')'
                }).then((result) => {
                    if (result.value) {
                        // disable button
                        e.target.setAttribute('disabled', 'disabled')
                        // add spinner to button
                        e.target.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Working...`
                        e.target.closest('form').submit()
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // swal cancelled
                    }
                });
            });
        });
    </script>
@stop
