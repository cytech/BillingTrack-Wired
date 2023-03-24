<table class="table table-hover" style="height: 100%;">
    <thead>
    <tr>
        <th>@lang('bt.status')</th>
        <th>@lang('bt.document')</th>
        <th>@lang('bt.date')</th>
        <th>@lang('bt.expires')</th>
        <th>@lang('bt.summary')</th>
        <th style="text-align: right; padding-end: 25px;">@lang('bt.total')</th>
        <th>@lang('bt.invoiced')</th>
        <th>@lang('bt.options')</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($documents as $document)
        <tr>
            <td>
                <span class="badge badge-{{ $statuses[$document->document_status_id] }}">{{ trans('bt.' . $statuses[$document->document_status_id]) }}</span>
                @if ($document->viewed)
                    <span class="badge bg-success">@lang('bt.viewed')</span>
                @else
                    <span class="badge bg-secondary">@lang('bt.not_viewed')</span>
                @endif
            </td>
            <td><a href="{{ route('documents.edit', [$document->id]) }}"
                   title="@lang('bt.edit')">{{ $document->number }}</a></td>
            <td>{{ $document->formatted_document_date }}</td>
            <td>{{ $document->formatted_expires_at }}</td>
            <td>{{ mb_strimwidth($document->summary,0,100,'...') }}</td>
            <td style="text-align: right; padding-end: 25px;">{{ $document->amount->formatted_total }}</td>
            <td>
                @if ($document->invoice)
                    <a href="{{ route('invoices.edit', [$document->invoice_id]) }}">@lang('bt.invoice')</a>
                @elseif ($document->workorder)
                    <a href="{{ route('workorders.edit', [$document->workorder_id]) }}">@lang('bt.workorder')</a>
                @else
                    @lang('bt.no')
                @endif
            </td>
            <td>
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="dropdown">
                        @lang('bt.options')
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" role="menu">
                        <a class="dropdown-item" href="{{ route('documents.edit', [$document->id]) }}"><i
                                    class="fa fa-edit"></i> @lang('bt.edit')</a>
                        <a class="dropdown-item" href="{{ route('documents.pdf', [$document->id]) }}" target="_blank"
                           id="btn-pdf-document"><i
                                    class="fa fa-print"></i> @lang('bt.pdf')</a>
                        <a href="javascript:void(0)" class="email-document dropdown-item" data-document-id="{{ $document->id }}"
                           data-redirect-to="{{ request()->fullUrl() }}"><i
                                    class="fa fa-envelope"></i> @lang('bt.email')</a>
                        <a class="dropdown-item" href="{{ route('clientCenter.public.document.show', [$document->url_key]) }}"
                           target="_blank"
                           id="btn-public-document"><i class="fa fa-globe"></i> @lang('bt.public')</a>
                        <a class="dropdown-item" href="#"
                           onclick="swalConfirm('@lang('bt.trash_record_warning')', '','{{ route('documents.delete', [$document->id]) }}');"><i
                                    class="fa fa-trash-alt text-danger"></i> @lang('bt.trash')</a>
                    </div>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
