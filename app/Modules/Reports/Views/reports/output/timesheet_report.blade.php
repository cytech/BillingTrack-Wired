@extends('reports.layouts.master')

@section('content')
    <table>
        <tr>
            <td style="width: 50%; vertical-align:top;" >
                <span style="font-weight: bold">{{ $results['total_records'] }} @lang('bt.records_found_criteria')</span><br>
            </td>
            <td style="width: 50%; text-align: right;  vertical-align:top;">
                {{--{!! $logo !!}<br>--}}
                <span style="font-weight: bold">{{ $results['companyProfile_company'] }}</span><br>
            </td>
        </tr>
    </table>
    <h2 style="margin-bottom: 0;">@lang('bt.timesheet')</h2>
    <h3 style="margin-top: 0;">{{ $results['from_date'] }} - {{ $results['to_date'] }}</h3>
    <br>
    @if($results['report_type'] == 'condensed')
        <table class="alternate">
            <thead>
            <tr>
                <th>@lang('bt.employee_short_name')</th>
                <th>@lang('bt.fullname')</th>
                <th>@lang('bt.empnumber')</th>
                <th>@lang('bt.totalhours')</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($results['records'] as $result)
                <tr>
                    <td>{{ $result['item_name'] }}</td>
                    <td>{{ $result['full_name'] }}</td>
                    <td>{{ $result['employee_number'] }}</td>
                    <td>{{ $result['item_qty'] }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3"></td>
                <td style="font-weight: bold;">@lang('bt.totalhours')</td>
                <td style="font-weight: bold;">{{ $results['total_hours'] }}</td>
            </tr>
            </tbody>
        </table>
    @else
        <table class="alternate">
            <thead>
            <tr>
                <th>@lang('bt.invoicenumber')</th>
                <th>@lang('bt.customername')</th>
                <th>@lang('bt.datefinished')</th>
                <th>@lang('bt.itemname')</th>
                <th>@lang('bt.itemqty')</th>
                <th>@lang('bt.fullname')</th>
                <th>@lang('bt.empnumber')</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($results['records'] as $result)
                <tr>
                    <td>{{ $result['number'] }}</td>
                    <td>{{ $result['client_name'] }}</td>
                    <td>{{ $result['formatted_invoice_date'] }}</td>
                    <td>{{ $result['item_name'] }}</td>
                    <td>{{ $result['item_qty'] }}</td>
                    <td>{{ $result['full_name'] }}</td>
                    <td>{{ $result['employee_number'] }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3"></td>
                <td style="font-weight: bold;">@lang('bt.totalhours')</td>
                <td style="font-weight: bold;">{{ $results['total_hours'] }}</td>
            </tr>
            </tbody>
        </table>
    @endif
@stop
