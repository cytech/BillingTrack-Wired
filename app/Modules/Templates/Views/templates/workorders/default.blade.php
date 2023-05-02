<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>@lang('bt.workorder') #{{ $document->number }}</title>

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

<table>
    <tr>
        <td style="width: 33%;  vertical-align: top">
            <h1>{{ mb_strtoupper(trans('bt.workorder')) }}</h1>
            <span class="info">{{ mb_strtoupper(trans('bt.workorder')) }} #</span>{{ $document->number }}<br>
            <span class="info">{{ mb_strtoupper(trans('bt.issued')) }}</span> {{ $document->formatted_workorder_date }}
            <br>
            <span class="info">{{ mb_strtoupper(trans('bt.expires')) }}</span> {{ $document->formatted_action_date }}
            <br><br>
            <span class="info">{{ mb_strtoupper(trans('bt.bill_to')) }}</span><br>{{ $document->client->name }}<br>
            @if ($document->client->address) {!! $document->client->formatted_address !!}<br>@endif
            @if ($document->client->phone) {!! $document->client->phone !!}<br>@endif
        </td>
        @if ($document->client->address_2)
            <td style="width: 33%; vertical-align: bottom">
                <span class="info">{{ mb_strtoupper(trans('bt.ship_to')) }}</span><br>{{ $document->client->name }}<br>
                {!! $document->client->formatted_address2 !!}<br>
            </td>
        @endif

        <td style="width: 50%; text-align: right;  vertical-align: top">
            {!! $document->companyProfile->logo() !!}<br>
            {{ $document->companyProfile->company }}<br>
            {!! $document->companyProfile->formatted_address !!}<br>
            @if ($document->companyProfile->phone) {{ $document->user->phone }}<br>@endif
            @if ($document->companyProfile->email) <a href="mailto:{{ $document->companyProfile->email }}">{{ $document->companyProfile->email }}</a>@endif
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
        </td>
    </tr>

</table>

<table>
    <tr>
        <td style="width: 100%; text-align: left;  vertical-align: top">
            <strong>Job Summary:</strong> {{$document->summary}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
    </tr>

</table>

<table class="alternate">
    <thead>
    <tr>
        <th style="width:25%; text-align: left;">{{ mb_strtoupper(trans('bt.item')) }}</th>
        <th style="text-align: left;">{{ mb_strtoupper(trans('bt.description' )) }}</th>
        <th class="amount" width="10%">{{ mb_strtoupper(trans('bt.qty')) }}</th>
        <th class="amount" width="10%">{{ mb_strtoupper(trans('bt.price')) }}</th>
        <th class="amount" width="10%">{{ mb_strtoupper(trans('bt.total')) }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($document->items as $item)
        <tr>
            <td>{!! $item->name !!}</td>
            <td>{!! $item->formatted_description !!}</td>
            <td nowrap
                class="amount">{{ $item->formatted_quantity <> '0.00' ? $item->formatted_quantity : "________" }}</td>
            <td nowrap
                class="amount">{{ $item->formatted_numeric_price <> '0.00' ? $item->formatted_numeric_price : "________" }}</td>
            <td nowrap
                class="amount">{{ $item->amount->formatted_subtotal <> '$0.00' ? $item->amount->formatted_subtotal : "________" }}</td>
            {{--<td nowrap class="amount">{{ $item->quantity > 0 ? $item->quantity : "________" }}</td>--}}
            {{--<td nowrap class="amount">{{ $item->price > 0 ? $item->price : "________" }}</td>--}}
            {{--<td nowrap class="amount">{{ $item->amount->subtotal > 0 ? $item->amount->subtotal : "________" }}</td>--}}
        </tr>
    @endforeach

    <tr>
        <td colspan="4" class="amount">{{ mb_strtoupper(trans('bt.subtotal')) }}</td>
        <td class="amount">__________</td>
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
        <td class="amount">__________</td>
    </tr>
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
