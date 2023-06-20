<!doctype html>
<html lang="en-US">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>@lang('bt.'. $document->lower_case_baseclass ) #{{ $document->number }}</title>
    <style>
        @page {
            margin: 25px;
        }

        body {
            color: #001028;
            background: #FFFFFF;
            font-family: DejaVu Sans, Helvetica, sans-serif;
            font-size: 12px;
            margin-bottom: 10px;
        }

        a {
            color: #5D6975;
            border-bottom: 1px solid currentColor;
            text-decoration: none;
        }

        h1 {
            color: #5D6975;
            font-size: 2.8em;
            line-height: 1.4em;
            font-weight: bold;
            margin: 0;
        }

        table {
            width: 100%;
            border-spacing: 0;
            margin-bottom: 20px;
        }

        th, .section-header {
            padding: 5px 10px;
            color: #5D6975;
            border-bottom: 1px solid #C1CED9;
            white-space: nowrap;
            font-weight: normal;
            text-align: center;
        }

        td {
            padding: 10px;
        }

        table.alternate tr:nth-child(odd) td {
            background: #F5F5F5;
        }

        th.amount, td.amount {
            text-align: right;
        }

        .info {
            color: #5D6975;
            font-weight: bold;
        }

        .infoitalic {
            color: #5D6975;
            font-weight: bold;
            font-style: italic;
        }

        .terms {
            padding: 10px;
            text-align: center;
        }

        .footer {
            position: fixed;
            height: 50px;
            width: 100%;
            bottom: 0;
            text-align: center;
        }
    </style>
</head>
<body>
@if($document->module_type == 'Purchaseorder')
    <table>
        <tr>
            <td style="width: 33%; vertical-align:top;">
                <h1>{{ mb_strtoupper(trans('bt.purchaseorder')) }}</h1>
                <span class="info">{{ mb_strtoupper(trans('bt.purchaseorder')) }} #</span>{{ $document->number }}<br>
                <span class="info">{{ mb_strtoupper(trans('bt.issued')) }}</span> {{ $document->formatted_created_at }}
                <br>
                <span class="info">{{ mb_strtoupper(trans('bt.due_date')) }}</span> {{ $document->formatted_action_date }}
                <br><br>
                <span class="infoitalic">{{ mb_strtoupper(trans('bt.to')) }}:</span><br>
                <span class="info">{{ $document->vendor->name }}</span><br>
                @if ($document->vendor->address)
                    {!! $document->vendor->formatted_address !!}<br>
                @endif
            </td>
            <td style="width: 50%; text-align: right; vertical-align:top;">
                {!! $document->companyProfile->logo() !!}<br>
                <span class="infoitalic">{{ mb_strtoupper(trans('bt.bill_to')) }}:</span><br>
                <span class="info">{{ $document->companyProfile->company }}</span><br>
                {!! $document->companyProfile->formatted_address !!}<br>
                @if ($document->companyProfile->phone)
                    {{ $document->companyProfile->phone }}<br>
                @endif
                @if ($document->companyProfile->email)
                    <a href="mailto:{{ $document->companyProfile->email }}">{{ $document->companyProfile->email }}</a>
                @endif
                <br><br><br>
                @if ($document->companyProfile->address_2)
                    <span class="infoitalic">{{ mb_strtoupper(trans('bt.ship_to')) }}:</span><br>
                    <span class="info">{{ $document->companyProfile->company }}</span><br>
                    {!! $document->companyProfile->formatted_address2 !!}<br>
                @endif
            </td>
        </tr>
    </table>
@else
    <table>
        <tr>
            <td style="width: 33%; vertical-align:top;">
                <h1>{{ mb_strtoupper(trans('bt.'. strtolower($document->module_type))) }}</h1>
                <span class="info">{{ mb_strtoupper(trans('bt.'. strtolower($document->module_type))) }} #</span>{{ $document->number }}
                <br>
                <span class="info">{{ mb_strtoupper(trans('bt.issued')) }}</span> {{ $document->formatted_created_at }}
                <br>
                @if($document->module_type == 'Quote')
                    <span class="info">{{ mb_strtoupper(trans('bt.expires')) }}</span> {{ $document->formatted_action_date }}
                @else
                    <span class="info">{{ mb_strtoupper(trans('bt.due_date')) }}</span> {{ $document->formatted_action_date }}
                @endif
                <br><br>
                <span class="infoitalic">{{ mb_strtoupper(trans('bt.bill_to')) }}:</span><br>
                <span class="info">{{ $document->client->name }}</span><br>
                {!! $document->client->formatted_address ?? '' !!}<br>
                @if($document->module_type == 'Workorder')
                    {!! $document->client->phone ?? ''!!}<br>
                @endif
                @if ($document->client->address_2)
                    <td style="width: 50%; vertical-align:bottom;">
                        <span class="info">{{ mb_strtoupper(trans('bt.ship_to')) }}:</span><br>
                        <span class="info">{{ $document->client->name }}</span><br>
                        {!! $document->client->formatted_address2 !!}<br>
                    </td>
                @endif
            </td>
            <td style="width: 50%; text-align: right; vertical-align:top;">
                {!! $document->companyProfile->logo() !!}<br>
                <span class="info">{{ $document->companyProfile->company }}</span><br>
                {!! $document->companyProfile->formatted_address !!}<br>
                {{ $document->companyProfile->phone ?? ''}}<br>
                @if ($document->companyProfile->email)
                    <a href="mailto:{{ $document->companyProfile->email }}">{{ $document->companyProfile->email }}</a>
                    <br>
                @endif
                @if($document->module_type == 'Workorder')
                    <br>
                    <span class="info">{{ 'Job Date: ' }}</span>{{ $document->formatted_job_date }}<br>
                    <span class="info">{{ 'Start Time: ' }}</span>{{ $document->formatted_start_time }}<br>
                    <span class="info">{{ 'Estimated Hours: ' }}</span>{{ $document->formatted_job_length }}<br>
                    <span class="info">
                <strong>Client Pickup: {{ $document->will_call ? __('bt.yes') : __('bt.no')  }}</strong>
                @endif
            </td>
        </tr>
    </table>
@endif
@if($document->module_type == 'Workorder')
    <table>
        <tr>
            <td style="width: 100%; text-align: left; vertical-align:top;">
                <strong>Job Summary:</strong> {{$document->summary}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
        </tr>
    </table>
@endif
<table class="alternate">
    <thead>
    <tr>
        <th class="info">{{ mb_strtoupper(trans('bt.product')) }}</th>
        <th class="info">{{ mb_strtoupper(trans('bt.description')) }}</th>
        <th class="info amount">{{ mb_strtoupper(trans('bt.quantity')) }}</th>
        <th class="info amount">{{ mb_strtoupper(trans('bt.price')) }}</th>
        <th class="info amount">{{ mb_strtoupper(trans('bt.total')) }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($document->items as $item)
        <tr>
            <td>{{ $item->name }}</td>
            <td>{{ $item->formatted_description }}</td>
            @if($document->module_type == 'Workorder')
                <td nowrap
                    class="amount">{{ $item->formatted_quantity <> '0.00' ? $item->formatted_quantity : "________" }}</td>
                <td nowrap
                    class="amount">{{ $item->formatted_numeric_price <> '0.00' ? $item->formatted_numeric_price : "________" }}</td>
                <td nowrap
                    class="amount">{{ $item->amount->formatted_subtotal <> '$0.00' ? $item->amount->formatted_subtotal : "________" }}</td>
            @else
                <td nowrap class="amount">{{ $item->formatted_quantity }}</td>
                <td nowrap class="amount">{{ $item->formatted_price }}</td>
                <td nowrap class="amount">{{ $item->amount->formatted_subtotal }}</td>
            @endif
        </tr>
    @endforeach
    <tr>
        <td colspan="4" class="amount">{{ mb_strtoupper(trans('bt.subtotal')) }}</td>
        @if($document->module_type == 'Workorder')
            <td class="amount">__________</td>
        @else
            <td class="amount">{{ $document->amount->formatted_subtotal }}</td>
        @endif
    </tr>
    @if ($document->discount > 0)
        <tr>
            <td colspan="4" class="amount">{{ mb_strtoupper(trans('bt.discount')) }}</td>
            <td class="amount">{{ $document->amount->formatted_discount }}</td>
        </tr>
    @endif

    @foreach ($document->summarized_taxes as $tax)
        <tr>
            <td colspan="4" class="amount">{{ mb_strtoupper($tax->name) }} ({{ $tax->percent }})</td>
            <td class="amount">{{ $tax->total }}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="4" class="amount">{{ mb_strtoupper(trans('bt.total')) }}</td>
        @if($document->module_type == 'Workorder')
            <td class="amount">__________</td>
        @else
            <td class="amount">{{ $document->amount->formatted_total }}</td>
        @endif
    </tr>
    @if($document->module_type == 'Invoice')
        <tr>
            <td colspan="4" class="amount">{{ mb_strtoupper(trans('bt.paid')) }}</td>
            <td class="amount">{{ $document->amount->formatted_paid }}</td>
        </tr>
        <tr>
            <td colspan="4" class="amount">{{ mb_strtoupper(trans('bt.balance')) }}</td>
            <td class="amount">{{ $document->amount->formatted_balance }}</td>
        </tr>
    @endif
    </tbody>
</table>
@if ($document->terms)
    <div class="section-header">{{ mb_strtoupper(trans('bt.terms_and_conditions')) }}</div>
    <div class="terms">{!! $document->formatted_terms !!}</div>
    <br>
@endif
<div class="footer"> {!! $document->formatted_footer !!}</div>
</body>
</html>
