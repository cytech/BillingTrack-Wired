@extends('client_center.layouts.public')

@section('javaScript')
    <script type="text/javascript">
        ready(function () {
            document.getElementById('view-notes').style.display = 'none'
            addEvent(document, 'click', ".btn-notes", (e) => {
                document.getElementById('view-doc').toggleid()
                document.getElementById('view-notes').toggleid()
                if (document.getElementById('approve-btn')) document.getElementById('approve-btn').toggleid()
                if (document.getElementById('reject-btn')) document.getElementById('reject-btn').toggleid()
                document.getElementById(e.target.dataset.buttonToggle).style.display = 'block'
                e.target.style.display = 'none'
            });
        });
    </script>
@stop

@section('content')
    <section class="content">
        <div class="public-wrapper">
            @include('layouts._alerts')
            <div style="margin-bottom: 15px;">
                <a href="{{ route('clientCenter.public.quote.pdf', [$quote->url_key]) }}" target="_blank"
                   class="btn btn-primary"><i class="fa fa-print"></i> <span>@lang('bt.pdf')</span>
                </a>
                @if (auth()->check())
                    <div class="btn-group">
                        <button id="btn-notes" data-button-toggle="btn-notes-back"
                                class="btn btn-primary btn-notes">
                            <i class="fa fa-comments"></i> @lang('bt.notes')
                        </button>
                        <button id="btn-notes-back" data-button-toggle="btn-notes"
                                class="btn btn-primary btn-notes" style="display: none;">
                            <i class="fa fa-backward"></i> @lang('bt.back_to_quote')
                        </button>
                    </div>
                @endif
                @if (count($attachments))
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary" data-bs-toggle="dropdown">
                            <i class="fa fa-files-o"></i> @lang('bt.attachments')
                        </button>
                        <ul class="dropdown-menu">
                            @foreach ($attachments as $attachment)
                                <li><a href="{{ $attachment->download_url }}">{{ $attachment->filename }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (in_array($quote->status_text, ['draft', 'sent']))
                    <div class="btn-group">
                    <a id="approve-btn" href="#" class="btn btn-primary"
                       onclick="swalConfirm('@lang('bt.confirm_approve_quote')', '', '{{ route('clientCenter.public.quote.approve', [$quote->url_key]) }}','view-doc');">
                        <i class="fa fa-thumbs-up"></i> @lang('bt.approve')
                    </a>
                    </div>
                    <div class="btn-group">
                    <a id="reject-btn" href="#" class="btn btn-primary"
                       onclick="swalConfirm('@lang('bt.confirm_reject_quote')', '', '{{ route('clientCenter.public.quote.reject', [$quote->url_key]) }}', 'view-doc');">
                        <i class="fa fa-thumbs-down"></i> @lang('bt.reject')
                    </a>
                    </div>
                @endif
            </div>
            <div class="public-doc-wrapper">
                <div id="view-doc">
                    <iframe src="{{ route('clientCenter.public.quote.html', [$urlKey]) }}"
                            style="width: 100%;" onload="resizeIframe(this, 800);"></iframe>
                </div>
                @if (auth()->check())
                    <div id="view-notes">
                        @include('notes._notes', ['object' => $quote, 'model' => 'BT\Modules\Quotes\Models\Quote'])
                    </div>
                @endif
            </div>
        </div>
    </section>

@stop
