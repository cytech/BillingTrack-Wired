<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>@lang('bt.'. strtolower($document->moduletype())) #{{ $document->number }}</title>
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
@if($document->moduletype() == 'Purchaseorder')
    <table>
        <tr>
            <td style="width: 50%;" valign="top">
                <h1>{{ mb_strtoupper(trans('bt.purchaseorder')) }}</h1>
                <span class="info">{{ mb_strtoupper(trans('bt.purchaseorder')) }} #</span>{{ $document->number }}<br>
                <span class="info">{{ mb_strtoupper(trans('bt.issued')) }}</span> {{ $document->formatted_created_at }}
                <br>
                <span class="info">{{ mb_strtoupper(trans('bt.due_date')) }}</span> {{ $document->formatted_action_date }}
                <br><br>
                <span class="info">{{ mb_strtoupper(trans('bt.to')) }}</span><br>{{ $document->vendor->name }}<br>
                @if ($document->vendor->address)
                    {!! $document->vendor->formatted_address !!}<br>
                @endif
            </td>
            <td style="width: 50%; text-align: right;" valign="top">
                <span class="info">{{ mb_strtoupper(trans('bt.bill_to')) }}</span>
                {!! $document->companyProfile->logo() !!}<br>
                {{ $document->companyProfile->company }}<br>
                {!! $document->companyProfile->formatted_address !!}<br>
                @if ($document->companyProfile->phone)
                    {{ $document->companyProfile->phone }}<br>
                @endif
                @if ($document->companyProfile->email)
                    <a href="mailto:{{ $document->companyProfile->email }}">{{ $document->companyProfile->email }}</a>
                @endif
                <br><br><br>
                @if ($document->companyProfile->address_2)
                    <span class="info">{{ mb_strtoupper(trans('bt.ship_to')) }}</span><br>
                    {{ $document->companyProfile->company }}<br>
                    {!! $document->companyProfile->formatted_address2 !!}<br>
                @endif
            </td>
        </tr>
    </table>
@else
    <table>
        <tr>
            <td style="width: 50%;" valign="top">
                <h1>{{ mb_strtoupper(trans('bt.'. strtolower($document->moduletype()))) }}</h1>
                <span class="info">{{ mb_strtoupper(trans('bt.'. strtolower($document->moduletype()))) }} #</span>{{ $document->number }}
                <br>
                <span class="info">{{ mb_strtoupper(trans('bt.issued')) }}</span> {{ $document->formatted_created_at }}
                <br>
                @if($document->moduletype() == 'Quote')
                    <span class="info">{{ mb_strtoupper(trans('bt.expires')) }}</span> {{ $document->formatted_action_date }}
                @else
                    <span class="info">{{ mb_strtoupper(trans('bt.due_date')) }}</span> {{ $document->formatted_action_date }}
                @endif
                <br><br>
                <span class="info">{{ mb_strtoupper(trans('bt.bill_to')) }}</span><br>{{ $document->client->name }}<br>
                @if ($document->client->address)
                    {!! $document->client->formatted_address !!}<br>
                @endif
                @if($document->moduletype() == 'Workorder')
                    @if ($document->client->phone)
                        {!! $document->client->phone !!}
                        <br>
            @endif
            @if ($document->client->address_2)
                <td style="width: 50%;" valign="bottom">
                    <span class="info">{{ mb_strtoupper(trans('bt.ship_to')) }}</span><br>{{ $document->client->name }}
                    <br>
                    {!! $document->client->formatted_address2 !!}<br>
                </td>
                @endif
                @endif
                </td>
                <td style="width: 50%; text-align: right;" valign="top">
                    {!! $document->companyProfile->logo() !!}<br>
                    {{ $document->companyProfile->company }}<br>
                    {!! $document->companyProfile->formatted_address !!}<br>
                    @if ($document->companyProfile->phone)
                        {{ $document->companyProfile->phone }}<br>
                    @endif
                    @if ($document->companyProfile->email)
                        <a href="mailto:{{ $document->companyProfile->email }}">{{ $document->companyProfile->email }}</a>
                    @endif
                    @if($document->moduletype() == 'Workorder')
                        <br>
                        <span class="info">{{ 'Job Date: ' }}</span>{{ $document->formatted_job_date }}<br>
                        <span class="info">{{ 'Start Time: ' }}</span>{{ $document->formatted_start_time }}<br>
                        {{--<span class="info">{{ 'End Time: ' }}</span>{{ $document->formatted_end_time }}<br>--}}
                        <span class="info">{{ 'Estimated Hours: ' }}</span>{{ $document->formatted_job_length }}<br>
                        <span class="info">
                 @if ($document->will_call ==1)
                                <strong>Client Pickup: Yes</strong>
                            @else
                                <strong>Client Pickup: No</strong>
                            @endif
            </span>
                    @endif
                </td>
        </tr>
    </table>
@endif
@if($document->moduletype() == 'Workorder')
    <table>
        <tr>
            <td style="width: 100%; text-align: left;" valign="top">
                <strong>Job Summary:</strong> {{$document->summary}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
        </tr>
    </table>
@endif
<table class="alternate">
    <thead>
    <tr>
        <th>{{ mb_strtoupper(trans('bt.product')) }}</th>
        <th>{{ mb_strtoupper(trans('bt.description')) }}</th>
        <th class="amount">{{ mb_strtoupper(trans('bt.quantity')) }}</th>
        <th class="amount">{{ mb_strtoupper(trans('bt.price')) }}</th>
        <th class="amount">{{ mb_strtoupper(trans('bt.total')) }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($document->items as $item)
        <tr>
            <td>{!! $item->name !!}</td>
            <td>{!! $item->formatted_description !!}</td>
            @if($document->moduletype() == 'Workorder')
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
        @if($document->moduletype() == 'Workorder')
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
        @if($document->moduletype() == 'Workorder')
            <td class="amount">__________</td>
        @else
            <td class="amount">{{ $document->amount->formatted_total }}</td>
        @endif
    </tr>
    @if($document->moduletype() == 'Invoice')
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
