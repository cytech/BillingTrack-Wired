@extends('layouts.master')

@section('javaScript')
    <script type="text/javascript">
        ready(function () {
            addEvent(document, 'click', '#btn-add-contact', (e) => {
                loadModal('{{ route('clients.contacts.create', [$client->id]) }}')
            });

            addEvent(document, 'click', "#clientview-tabs a", (e) => {
                const tabId = e.target.getAttribute('href').slice(1)
                axios.post("{{ route('clients.saveTab') }}", {clientviewTabId: tabId});
            })

            let stid = '{{ session('clientviewTabId') }}' ? '{{ session('clientviewTabId') }}' : 'tab-details'
            var triggerEl = new bootstrap.Tab(document.querySelector('#clientview-tabs a[href="#' + stid + '"]'))
            triggerEl.show() // Select tab by name
        })
    </script>
@stop

@section('content')
    <section class="app-content-header">
        <div class="container-fluid">
            <div class="col-sm-12 align-items-center">
                <div class="fs-3 float-start">{{__('bt.view_client') . ' - ' . $client->name}}</div>
                <div class="btn-group float-end">
                    <a class="btn btn-secondary rounded me-1" href="#" id="btn-create-quote"
                       {{--                   params 3 thru ... mount(,,$modulefullname, $moduleop, $resource_id = null, $module_id = null, $readonly = null)--}}
                       onclick="window.livewire.emit('showModal', 'modals.create-module-modal', '{{ addslashes(get_class($client->quotes()->getRelated())) }}', 'create', {{ $client->id }}, null, true)">
                        @lang('bt.create_quote')</a>
                    <a class="btn btn-secondary rounded me-1" href="#" id="btn-create-workorder"
                       {{--                   params 3 thru ... mount(,,$modulefullname, $moduleop, $resource_id = null, $module_id = null, $readonly = null)--}}
                       onclick="window.livewire.emit('showModal', 'modals.create-module-modal', '{{ addslashes(get_class($client->workorders()->getRelated())) }}', 'create', {{ $client->id }}, null, true)">
                        @lang('bt.create_workorder')</a>
                    <a class="btn btn-secondary rounded me-1" href="#" id="btn-create-invoice"
                       {{--                   params 3 thru ... mount(,,$modulefullname, $moduleop, $resource_id = null, $module_id = null, $readonly = null)--}}
                       onclick="window.livewire.emit('showModal', 'modals.create-module-modal', '{{ addslashes(get_class($client->invoices()->getRelated())) }}', 'create', {{ $client->id }}, null, true)">
                        @lang('bt.create_invoice')</a>
                    <a href="{{ $returnUrl }}" class="btn btn-secondary rounded me-1"><i class="fa fa-backward"></i> @lang('bt.back')
                    </a>
                    <a href="{{ route('clients.edit', [$client->id]) }}" class="btn btn-secondary rounded me-1">@lang('bt.edit')</a>
                    <a class="btn btn-secondary rounded me-1" href="#"
                       onclick="swalConfirm('@lang('bt.trash_client_warning')', '@lang('bt.trash_client_warning_msg')', '{{ route('clients.delete', [$client->id]) }}');"><i
                                class="fa fa-trash"></i> @lang('bt.trash')</a>
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
                        <ul class="nav nav-pills p-2" id="clientview-tabs">
                            <li class="nav-item "><a class="nav-link active show" data-bs-toggle="tab"
                                                     href="#tab-details">@lang('bt.details')</a></li>
                            <li class="nav-item "><a class="nav-link" data-bs-toggle="tab"
                                                     href="#tab-quotes">@lang('bt.quotes')</a></li>
                            <li class="nav-item "><a class="nav-link" data-bs-toggle="tab"
                                                     href="#tab-workorders">@lang('bt.workorders')</a></li>
                            <li class="nav-item "><a class="nav-link" data-bs-toggle="tab"
                                                     href="#tab-invoices">@lang('bt.invoices')</a></li>
                            <li class="nav-item "><a class="nav-link" data-bs-toggle="tab"
                                                     href="#tab-recurring-invoices">@lang('bt.recurring_invoices')</a>
                            </li>
                            <li class="nav-item "><a class="nav-link" data-bs-toggle="tab"
                                                     href="#tab-payments">@lang('bt.payments')</a></li>
                            <li class="nav-item "><a class="nav-link" data-bs-toggle="tab"
                                                     href="#tab-attachments">@lang('bt.attachments')</a></li>
                            <li class="nav-item "><a class="nav-link" data-bs-toggle="tab"
                                                     href="#tab-notes">@lang('bt.notes')</a></li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div id="tab-details" class="tab-pane active">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="float-start">
                                            <h2>{!! $client->name !!}</h2>
                                            @if($client->is_company)
                                                <span class="badge bg-primary">@lang('bt.company')</span>
                                            @else
                                                <span class="badge bg-green">@lang('bt.individual')</span>
                                            @endif
                                            &nbsp;&nbsp;&nbsp;@lang('bt.payment_terms'):&nbsp;&nbsp;
                                            @if($client->paymentterm->id != 1)
                                                {{$client->paymentterm->name}}
                                            @else
                                                @lang('bt.default_terms')
                                            @endif
                                        </div>
                                        <div class="float-end" style="text-align: right;">
                                            <p>
                                                <strong>@lang('bt.total_billed')
                                                    :</strong> {{ $client->formatted_total }}<br/>
                                                <strong>@lang('bt.total_paid'):</strong> {{ $client->formatted_paid }}
                                                <br/>
                                                <strong>@lang('bt.total_balance')
                                                    :</strong> {{ $client->formatted_balance }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-striped">
                                            <tr class="row">
                                                <td class="col-md-2">@lang('bt.billing_address')</td>
                                                <td class="table-bordered col-md-4">{!! $client->formatted_address !!}</td>
                                                <td class="col-md-2">@lang('bt.shipping_address')</td>
                                                <td class="table-bordered col-md-4">{!! $client->formatted_address2 !!}</td>
                                            </tr>
                                            <tr class="row">
                                                <td class="col-md-2">@lang('bt.email')</td>
                                                <td class="col-md-10"><a
                                                            href="mailto:{!! $client->email !!}">{!! $client->email !!}</a>
                                                </td>
                                            </tr>
                                            <tr class="row">
                                                <td class="col-md-1">@lang('bt.phone')</td>
                                                <td class="table-bordered col-md-3">{!! $client->phone !!}</td>
                                                <td class="col-md-1">@lang('bt.mobile')</td>
                                                <td class="table-bordered col-md-3">{!! $client->mobile !!}</td>
                                                <td class="col-md-1">@lang('bt.fax')</td>
                                                <td class="table-bordered col-md-3">{!! $client->fax !!}</td>
                                            </tr>
                                            <tr class="row">
                                                <td class="col-md-2">@lang('bt.web')</td>
                                                <td class="col-md-10"><a href="{!! $client->web !!}"
                                                                         target="_blank">{!! $client->web !!}</a></td>
                                            </tr>
                                            <tr class="row">
                                                <td class="col-md-2">@lang('bt.industry')</td>
                                                <td class="table-bordered col-md-4">{!! $client->industry->name !!}</td>
                                                <td class="col-md-2">@lang('bt.size')</td>
                                                <td class="table-bordered col-md-4">{!! $client->size->name !!}</td>
                                            </tr>
                                            @foreach ($customFields as $customField)
                                                <tr class="row">
                                                    <td class="col-md-2">{!! $customField->field_label !!}</td>
                                                    <td class="col-md-10">
                                                        @if (isset($client->custom->{$customField->column_name}))
                                                            {!! nl2br($client->custom->{$customField->column_name}) !!}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="float-start">
                                            <h2>@lang('bt.contacts')</h2>
                                        </div>
                                        <div class="float-end mb-3">
                                            <button class="btn btn-primary btn-sm" id="btn-add-contact"><i
                                                        class="fa fa-plus"></i> @lang('bt.add_contact')</button>
                                        </div>
                                    </div>
                                </div>
                                <div id="tab-contacts">
                                    @include('clients._contacts', ['contacts' => $client->contacts()->orderBy('name')->get(), 'clientId' => $client->id])
                                </div>
                            </div>
                            <div id="tab-quotes" class="tab-pane">
                                <div class="card">
                                    <div class="card-body">
                                        <livewire:data-tables.module-table :module_type="'Quote'" :clientid="$client->id"/>
                                    </div>

                                </div>
                            </div>
                            <div id="tab-workorders" class="tab-pane">
                                <div class="card">
                                    <div class="card-body">
                                        <livewire:data-tables.module-table :module_type="'Workorder'" :clientid="$client->id"/>
                                    </div>

                                </div>
                            </div>
                            <div id="tab-invoices" class="tab-pane">
                                <div class="card">
                                    <div class="card-body">
                                        <livewire:data-tables.module-table :module_type="'Invoice'" :clientid="$client->id"/>
                                    </div>
                                </div>
                            </div>
                            <div id="tab-recurring-invoices" class="tab-pane">
                                <div class="card">
                                    @include('recurring_invoices._table')
                                    <div class="card-footer"><p class="text-center"><strong><a
                                                        href="{{ route('recurringInvoices.index') }}?client={{ $client->id }}">@lang('bt.view_all')</a></strong>
                                        </p></div>
                                </div>
                            </div>
                            <div id="tab-payments" class="tab-pane">
                                <div class="card">
                                    @include('payments._table')
                                    <div class="card-footer"><p class="text-center"><strong><a
                                                        href="{{ route('payments.index') }}?client={{ $client->id }}">@lang('bt.view_all')</a></strong>
                                        </p></div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab-attachments">
                                @include('attachments._table', ['object' => $client, 'model' => 'BT\Modules\Clients\Models\Client'])
                            </div>
                            <div id="tab-notes" class="tab-pane">
                                @include('notes._notes', ['object' => $client, 'model' => 'BT\Modules\Clients\Models\Client', 'hideHeader' => true])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
