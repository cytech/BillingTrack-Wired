@extends('layouts.master')

@section('content')
    @include('layouts._alerts')
    <section class="app-content-header">
        <nav class="navbar navbar-expand navbar-light border-bottom">   {{--bg-primary navbar-default--}}
            <div class="container-fluid">
                <a class="navbar-brand mb-0" href="#">@lang('bt.schedule_dashboard')</a>
                <div class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link"
                                            href="{!! route('scheduler.fullcalendar') !!}">@lang('bt.calendar')</a>
                    </li>
                    <li class="nav-item"><a class="nav-link"
                                            href="{!! route('scheduler.showschedule') !!}">@lang('bt.schedule')</a>
                    </li>
                    <li class="nav-item"><a class="nav-link"
                                            href="{!! route('scheduler.tableevent') !!}">@lang('bt.event_table')</a>
                    </li>
                    <li class="nav-item"><a class="nav-link"
                                            href="{!! route('scheduler.tablerecurringevent') !!}">@lang('bt.recurring_event')</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-bs-toggle="dropdown" href="#">@lang('bt.utilities')
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item"
                               href="{!! route('scheduler.categories.index') !!}"><i
                                        class="nav-icon fas fa-thumbtack"></i> @lang('bt.categories')</a>
                            <a class="dropdown-item"
                               href="{!! route('scheduler.checkschedule') !!}"><i
                                        class="nav-icon fas fa-check-double"></i> @lang('bt.orphan_check')</a>
                        </div>
                    </li>
                </div>
            </div>
        </nav>
        <div class="row col-lg-12 ps-5">
            <div class="col-lg-4 col-md-4">
                <div class="small-box text-bg-green">
                    <div class="inner">
                        <div><h1 class="fw-bold">{!! $monthEvent !!}</h1></div>
                        <p>@lang('bt.events_this_month')</p>
                    </div>
                    <div class="small-box-faicon"><i class="fas fa-tasks"></i></div>
                    <a class="small-box-footer" href="{!! route('scheduler.fullcalendar') !!}">
                        @lang('bt.vevents_this_month')
                        <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="small-box text-bg-blue">
                    <div class="inner">
                        <div><h1 class="fw-bold">{!! $lastMonthEvent !!}</h1></div>
                        <p>@lang('bt.events_last_month')</p>
                    </div>
                    <div class="small-box-faicon"><i class="fas fa-tasks"></i></div>
                    <a class="small-box-footer" href="{!! route('scheduler.fullcalendar') !!}?status=last">
                        @lang('bt.vevents_last_month')
                        <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="small-box text-bg-orange">
                    <div class="inner">
                        <div><h1 class="fw-bold">{!! $nextMonthEvent !!}</h1></div>
                        <p>@lang('bt.events_next_month')</p>
                    </div>
                    <div class="small-box-faicon"><i class="fas fa-tasks"></i></div>
                    <a class="small-box-footer" href="{!! route('scheduler.fullcalendar') !!}?status=next">
                        @lang('bt.vevents_next_month')
                        <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="row col-lg-12 ps-5">
            <div class="col-lg-4 col-md-4">
                <div class="small-box text-bg-green">
                    <div class="inner">
                        <div>{!! $thisquotes !!} @lang('bt.this_approved_quotes')</div>
                        <div>{!! $thisworkorders !!} @lang('bt.this_approved_workorders')</div>
                        <div>{!! $thisinvoices !!} @lang('bt.this_sent_invoices')</div>
                        <div>{!! $thispayments !!} @lang('bt.this_received_payments')</div>
                    </div>
                    <div class="small-box-faicon"><i class="fas fa-info-circle"></i></div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="small-box text-bg-blue">
                    <div class="inner">
                        <div>{!! $lastquotes !!} @lang('bt.last_approved_quotes')</div>
                        <div>{!! $lastworkorders !!} @lang('bt.last_approved_workorders')</div>
                        <div>{!! $lastinvoices !!} @lang('bt.last_sent_invoices')</div>
                        <div>{!! $lastpayments !!} @lang('bt.last_received_payments')</div>
                    </div>
                    <div class="small-box-faicon"><i class="fas fa-info-circle"></i></div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="small-box text-bg-orange">
                    <div class="inner">
                        <div>{!! $nextquotes !!} @lang('bt.next_approved_quotes')</div>
                        <div>{!! $nextworkorders !!} @lang('bt.next_approved_workorders')</div>
                        <div>{!! $nextinvoices !!} @lang('bt.next_sent_invoices')</div>
                        <div>{!! $nextpayments !!} @lang('bt.next_received_payments')</div>
                    </div>
                    <div class="small-box-faicon"><i class="fas fa-info-circle"></i></div>
                </div>
            </div>
        </div>
        {{--Reminder table --}}
        <div class="container-fluid">
            <div class="card card-light">
                <div class="card-header">
                    <h3 class="card-title"><i class="fa fa-bell"></i> @lang('bt.reminders')</h3>
                </div>
                <div class="card-body">
                    <table id="dt-reminderstable" class="display table dataTable">
                        <thead>
                        <tr>
                            <th>@lang('bt.event_title')</th>
                            <th>@lang('bt.location')</th>
                            <th>@lang('bt.occasion_start')</th>
                            <th>@lang('bt.occasion_end')</th>
                            <th>@lang('bt.action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($reminders_occ as $reminder)
                            <tr>
                                <td>{!! $reminder->schedule->title !!}</td>
                                <td>{!! $reminder->schedule->location_str !!}</td>
                                <td>{!! $reminder->formatted_start_date !!}</td>
                                <td>{!! $reminder->formatted_end_date !!}</td>
                                <td>
                                    <a href="#" class="btn btn-danger btn-sm" id="delete-reminder-{{ $reminder->id }}"
                                       onclick="swalConfirm('@lang('bt.reminder_trash_warning')', '', '{{ route('scheduler.trashreminder', $reminder->id) }}');"><i
                                                class="fa fa-trash-alt"></i> @lang('bt.trash')
                                    </a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="card card-light">
                <div class="card-header">
                    <h3 class="card-title"><i
                                class="fa fa-chart-bar fa-bar fa-fw"></i> @lang('bt.month_day_events')</h3>
                </div>
                <div>
                    <canvas id="monthEventsBarChart" height="50"></canvas>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="card card-light">
                <div class="card-header">
                    <h3 class="card-title"><i class="fa fa-chart-bar fa-fw"></i> @lang('bt.year_month_report')
                    </h3>
                </div>
                <div>
                    <canvas id="yearEventsBarChart" height="50"></canvas>
                </div>
            </div>
        </div>
    </section>
@stop
@section('javaScript')
    {!! Html::script('plugins/chart.js/chart.umd.js') !!}

    <script>
        var monthEvents = [
                @foreach($fullMonthEvent as $MonthEvent)
            {
                x: "{!! date('M-d', strtotime($MonthEvent->start_date)) !!}",
                y: {!! $MonthEvent->total !!}
            },
            @endforeach
        ];

        var yearEvents = [
            @foreach($fullYearMonthEvent as $yearMonthEvent)
            {
                x: "{!! date('M-Y', strtotime($yearMonthEvent->start_date)) !!}",
                y: "{!! $yearMonthEvent->total !!}"
            },
            @endforeach
        ];

        var monthBarChartData = {
            datasets: [{
                label: 'Total Events This Day',
                backgroundColor: 'rgb(102,110,182)',
                data: monthEvents
            }],
        };

        var yearBarChartData = {
            datasets: [{
                label: 'Total Events This month',
                backgroundColor: 'rgb(23,47,218)',
                data: yearEvents
            }],

        };

        window.onload = function () {
            var barctx = document.getElementById("monthEventsBarChart").getContext("2d");
            window.myBar = new Chart(barctx, {
                type: 'bar',
                data: monthBarChartData,
                options: {
                    scales: {
                        y: {
                            title: {
                                display: true,
                                text: 'Number of Events'
                            },
                            ticks: {
                                stepSize: 1
                            }
                        }
                    },
                    responsive: true,
                }
            });

            var donutctx = document.getElementById("yearEventsBarChart").getContext("2d");
            window.myBar = new Chart(donutctx, {
                type: 'bar',
                data: yearBarChartData,
                options: {
                    scales: {
                        y: {
                            title: {
                                display: true,
                                text: 'Number of Events'
                            },
                            ticks: {
                                stepSize: 1
                            }
                        }
                    },
                    responsive: true,
                }
            });
        };
    </script>
@stop
