@extends('layouts.master')

@section('content')

    <script type="text/javascript">
        ready(function () {
            document.getElementById('name').focus()
        })
    </script>

    @if ($editMode == true)
        {!! Form::model($paymentMethod, ['route' => ['paymentMethods.update', $paymentMethod->id]]) !!}
    @else
        {!! Form::open(['route' => 'paymentMethods.store']) !!}
    @endif

    <section class="app-content-header">
        <div class="container-fluid">
            <div class="col-sm-12">
                <div class="fs-3 float-start">@lang('bt.payment_method_form')</div>
                <a class="btn btn-warning float-end" href={!! route('paymentMethods.index')  !!}><i
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
                <div class="control-group">
                    <label>@lang('bt.payment_method'): </label>
                    {!! Form::text('name', null, ['id' => 'name', 'class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </section>
    {!! Form::close() !!}
@stop
