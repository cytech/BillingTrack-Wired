@extends('layouts.master')

@section('content')
    {!! Form::model($payment, ['route' => ['payments.update', $payment->id]]) !!}
    {!! Form::hidden('invoice_id') !!}
    {!! Form::hidden('client_id') !!}
    <section class="app-content-header">
        <h3 class="float-start px-3">
            @lang('bt.payment_form')
        </h3>
        <div class="float-end">
            <a href="{{ route('payments.index') }}" class="btn btn-secondary">Cancel</a>
            {!! Form::submit(trans('bt.save'), ['class' => 'btn btn-primary']) !!}
        </div>
        <div class="clearfix"></div>
    </section>
    <section class="container-fluid">
        @include('layouts._alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="card card-light">
                    <div class="card-body">
                        <div class="mb-3">
                            <label>@lang('bt.email_payment_warning')</label>
                        </div>
                        <div class="mb-3">
                            <label>@lang('bt.amount'): </label>
                            {!! Form::text('amount', $payment->formatted_numeric_amount, ['id' => 'amount',
                            'class' => 'form-control']) !!}
                        </div>
                        <div class="mb-3">
                            <label>@lang('bt.payment_date'): </label>
                            <x-fp_common
                                    name="paid_at"
                                    id="paid_at"
                                    class="form-control"
                                    value="{{$payment->paid_at}}"></x-fp_common>
                        </div>
                        <div class="mb-3">
                            <label>@lang('bt.payment_method')</label>
                            {!! Form::select('payment_method_id', $paymentMethods, null, ['id' =>
                            'payment_method_id', 'class' => 'form-control']) !!}
                        </div>
                        <div class="mb-3">
                            <label>@lang('bt.note')</label>
                            {!! Form::textarea('note', null, ['id' => 'note', 'rows' => '2', 'class' => 'form-control']) !!}
                        </div>
                        @if ($customFields->count())
                            @include('custom_fields._custom_fields')
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    {!! Form::close() !!}
    <section class="container-fluid">
        @include('notes._notes', ['object' => $payment, 'model' => 'BT\Modules\Payments\Models\Payment', 'showPrivateCheckbox' => true])
    </section>
@stop
