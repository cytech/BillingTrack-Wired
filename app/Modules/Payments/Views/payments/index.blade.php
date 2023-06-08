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
                    <div class="btn-group">
                        {{ html()->form('GET', route('payments.index'))->attribute('id', 'filter')->open() }}
                        <div class="input-group">
                            {{ html()->select('status', $statuses, request('status'))->class('filter_options form-select w-auto me-1') }}
                        </div>
                        {{ html()->form()->close() }}
                    </div>
                    @if(request('status') == 1)
                    <button class="btn btn-primary rounded border"
                            type="button"
                            onclick="window.livewire.emit('showModal', 'modals.create-payment-modal')"
                    ><i class="fa fa-credit-card"></i> @lang('bt.enter_payment')
                    </button>
                    @else
                        <button class="btn btn-secondary rounded border"
                                type="button"
                                disabled
                        > @lang('bt.enter_thru_purchaseorder')
                        </button>
                    @endif
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
