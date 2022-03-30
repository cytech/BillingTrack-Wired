<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ trans('bt.quote') }} #{{ $workorder->number }}</title>

    <style>
        @page {
            margin: 25px;
        }

        body {
            color: #001028;
            background: #FFFFFF;
            font-family : DejaVu Sans, Helvetica, sans-serif;
            font-size: 12px;
            margin-bottom: 50px;
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
        <td style="width: 50%;" valign="top">
            <h1>{{ mb_strtoupper(trans('bt.quote')) }}</h1>
            <span class="info">{{ mb_strtoupper(trans('bt.quote')) }} #</span>{{ $workorder->number }}<br>
            <span class="info">{{ mb_strtoupper(trans('bt.issued')) }}</span> {{ $workorder->formatted_created_at }}<br>
            <span class="info">{{ mb_strtoupper(trans('bt.expires')) }}</span> {{ $workorder->formatted_expires_at }}<br><br>
            <span class="info">{{ mb_strtoupper(trans('bt.bill_to')) }}</span><br>{{ $workorder->client->name }}<br>
            @if ($workorder->client->address) {!! $workorder->client->formatted_address !!}<br>@endif
        </td>
        <td style="width: 50%; text-align: right;" valign="top">
            {!! $workorder->companyProfile->logo() !!}<br>
            {{ $workorder->companyProfile->company }}<br>
            {!! $workorder->companyProfile->formatted_address !!}<br>
            @if ($workorder->companyProfile->phone) {{ $workorder->companyProfile->phone }}<br>@endif
            @if ($invoice->companyProfile->email) <a href="mailto:{{ $invoice->companyProfile->email }}">{{ $invoice->companyProfile->email }}</a>@endif
        </td>
    </tr>
</table>

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
    @foreach ($workorder->items as $item)
        <tr>
            <td>{!! $item->name !!}</td>
            <td>{!! $item->formatted_description !!}</td>
            <td nowrap class="amount">{{ $item->formatted_quantity }}</td>
            <td nowrap class="amount">{{ $item->formatted_price }}</td>
            <td nowrap class="amount">{{ $item->amount->formatted_subtotal }}</td>
        </tr>
    @endforeach

    <tr>
        <td colspan="4" class="amount">{{ mb_strtoupper(trans('bt.subtotal')) }}</td>
        <td class="amount">{{ $workorder->amount->formatted_subtotal }}</td>
    </tr>

    @if ($workorder->discount > 0)
        <tr>
            <td colspan="4" class="amount">{{ mb_strtoupper(trans('bt.discount')) }}</td>
            <td class="amount">{{ $workorder->amount->formatted_discount }}</td>
        </tr>
    @endif

    @foreach ($workorder->summarized_taxes as $tax)
        <tr>
            <td colspan="4" class="amount">{{ mb_strtoupper($tax->name) }} ({{ $tax->percent }})</td>
            <td class="amount">{{ $tax->total }}</td>
        </tr>
    @endforeach

    <tr>
        <td colspan="4" class="amount">{{ mb_strtoupper(trans('bt.total')) }}</td>
        <td class="amount">{{ $workorder->amount->formatted_total }}</td>
    </tr>
    </tbody>
</table>

@if ($workorder->terms)
    <div class="section-header">{{ mb_strtoupper(trans('bt.terms_and_conditions')) }}</div>
    <div class="terms">{!! $workorder->formatted_terms !!}</div>
@endif

<div class="footer">{!! $workorder->formatted_footer !!}</div>

</body>
</html>
