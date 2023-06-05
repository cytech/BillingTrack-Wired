<div class="row">
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.default_quote_template'): </label>
            {{ html()->select('setting[quoteTemplate]', $quoteTemplates, config('bt.quoteTemplate'))->class('form-select') }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.default_group'): </label>
            {{ html()->select('setting[quoteGroup]', $groups, config('bt.quoteGroup'))->class('form-select') }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.quotes_expire_after'): </label>
            {{ html()->text('setting[quotesExpireAfter]', config('bt.quotesExpireAfter'))->class('form-control') }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.default_status_filter'): </label>
            {{ html()->select('setting[quoteStatusFilter]', $quoteStatuses, config('bt.quoteStatusFilter'))->class('form-select') }}
        </div>
    </div>
</div>
<div class="mb-3">
    <label class="form-label fw-bold">@lang('bt.convert_quote_when_approved'): </label>
    {{ html()->select('setting[convertQuoteWhenApproved]', $yesNoArray, config('bt.convertQuoteWhenApproved'))->class('form-select') }}
</div>
<div class="mb-3">
    <label class="form-label fw-bold">@lang('bt.convert_quote_setting'): </label>
    {{ html()->select('setting[convertQuoteTerms]', $convertQuoteOptions, config('bt.convertQuoteTerms'))->class('form-select') }}
</div>
<div class="mb-3">
    <label class="form-label fw-bold">@lang('bt.default_terms'): </label>
    {{ html()->textarea('setting[quoteTerms]', config('bt.quoteTerms'))->rows(2)->class('form-control') }}
</div>
<div class="mb-3">
    <label class="form-label fw-bold">@lang('bt.default_footer'): </label>
    {{ html()->textarea('setting[quoteFooter]', config('bt.quoteFooter'))->rows(2)->class('form-control') }}
</div>
<div class="row">
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.if_quote_is_emailed_while_draft'): </label>
            {{ html()->select('setting[resetQuoteDateEmailDraft]', $quoteWhenDraftOptions, config('bt.resetQuoteDateEmailDraft'))->class('form-select') }}
        </div>
    </div>
    <div class="col-md-6"></div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.recalculate_quotes'): </label><br>
            @if (!config('app.demo'))
                <button type="button" class="btn btn-secondary" id="btn-recalculate-quotes"
                        data-loading-text="@lang('bt.recalculating_wait')">@lang('bt.recalculate')</button>
            @else
                Recalculate is disabled in the demo.
            @endif
            <p class="form-text text-muted">@lang('bt.recalculate_help_text')</p>
        </div>
    </div>
</div>
