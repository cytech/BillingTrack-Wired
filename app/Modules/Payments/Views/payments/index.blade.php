@extends('layouts.master')

@section('javaScript')
    <script type="text/javascript">
        ready(function () {
            addEvent(document, 'click', ".email-payment-receipt", (e) => {
                loadModal('{{ route('payments.paymentMail.create') }}', {
                    payment_id: e.target.dataset.paymentId,
                    redirectTo: e.target.dataset.redirectTo
                })
            })
        })
    </script>
@stop

@section('content')
    <section class="app-content-header">
        <div class="container-fluid">
            <div class="col-sm-12">
                <div class="fs-3 float-start">@lang('bt.payments')</div>
                <div class="btn-group float-end">
                    <button class="btn btn-primary rounded border"
                            type="button"
                            onclick="window.livewire.emit('showModal', 'modals.create-payment-modal')"
                    ><i class="fa fa-credit-card"></i> @lang('bt.enter_payment')
                    </button>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </section>
    <section class="content">
        @include('layouts._alerts')
        <div class="card ">
            <div class="card-body">
                <livewire:data-tables.module-table :module_type="'Payment'"  :clientid="request('client')"/>
            </div>
        </div>
    </section>
@stop
