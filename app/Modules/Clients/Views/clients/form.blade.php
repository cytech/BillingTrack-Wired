@extends('layouts.master')

@section('content')
    <section class="app-content-header">
        <div class="container-fluid">
            <div class="col-sm-12">
                @if ($editMode)
                    {!! Form::model($client, ['route' => ['clients.update', $client->id]]) !!}
                    <div class=" fs-3 float-start">@lang('bt.client_form') - @lang('bt.edit')</div>
                @else
                    {!! Form::open(['route' => 'clients.store']) !!}
                    <div class="fs-3 float-start">@lang('bt.client_form') - @lang('bt.create')</div>
                @endif
                <div class="float-end">
                    <button class="btn btn-primary"><i class="fa fa-save"></i> @lang('bt.save')</button>
                    {{--            @if ($editMode)--}}
                    <a href="{{ $returnUrl }}" class="btn btn-secondary"><i
                                class="fa fa-times-circle"></i> @lang('bt.cancel')
                    </a>
                    {{--            @endif--}}
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </section>
    <section class="content">
        @include('layouts._alerts')
        <div class="card">
            <div class="col-12">
                <div class="card m-2">
                    <div class="card-header d-flex p-0">
                        <ul class="nav nav-tabs p-2">
                            <li class="nav-item"><a class="nav-link active show" href="#tab-general"
                                                    data-bs-toggle="tab">@lang('bt.general')</a></li>
                            @if ($editMode)
                                <li class="nav-item"><a class="nav-link" href="#tab-attachments"
                                                        data-bs-toggle="tab">@lang('bt.attachments')</a></li>
                                <li class="nav-item"><a class="nav-link" href="#tab-notes"
                                                        data-bs-toggle="tab">@lang('bt.notes')</a></li>
                            @endif
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab-general">
                                @include('clients._form')
                            </div>
                            @if ($editMode)
                                <div class="tab-pane" id="tab-attachments">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            @include('attachments._table', ['object' => $client, 'model' => 'BT\Modules\Clients\Models\Client'])
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab-notes">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            @include('notes._notes', ['object' => $client, 'model' => 'BT\Modules\Clients\Models\Client', 'hideHeader' => true])
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {!! Form::close() !!}
@stop
