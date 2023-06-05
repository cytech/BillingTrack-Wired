<div class="row">
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.default_workorder_template'): </label>
            {{ html()->select('setting[workorderTemplate]', $workorderTemplates, config('bt.workorderTemplate'))->class('form-select') }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.default_group'): </label>
            {{ html()->select('setting[workorderGroup]', $groups, config('bt.workorderGroup'))->class('form-select') }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.workorders_expire_after'): </label>
            {{ html()->text('setting[workordersExpireAfter]', config('bt.workordersExpireAfter'))->class('form-control') }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.default_status_filter'): </label>
            {{ html()->select('setting[workorderStatusFilter]', $workorderStatuses, config('bt.workorderStatusFilter'))->class('form-select') }}
        </div>
    </div>
</div>
<div class="mb-3">
    <label class="form-label fw-bold">@lang('bt.convert_workorder_when_approved'): </label>
    {{ html()->select('setting[convertWorkorderWhenApproved]', $yesNoArray, config('bt.convertWorkorderWhenApproved'))->class('form-select') }}
</div>
<div class="mb-3">
    <label class="form-label fw-bold">@lang('bt.convert_workorder_setting'): </label>
    {{ html()->select('setting[convertWorkorderTerms]', $convertWorkorderOptions, config('bt.convertWorkorderTerms'))->class('form-select') }}
    {{ html()->select('setting[convertWorkorderDate]', $convertWorkorderDate, config('bt.convertWorkorderDate'))->class('form-select') }}
</div>
<div class="mb-3">
    <label class="form-label fw-bold">@lang('bt.default_terms'): </label>
    {{ html()->textarea('setting[workorderTerms]', config('bt.workorderTerms'))->rows(2)->class('form-control') }}
</div>
<div class="mb-3">
    <label class="form-label fw-bold">@lang('bt.default_footer'): </label>
    {{ html()->textarea('setting[workorderFooter]', config('bt.workorderFooter'))->rows(2)->class('form-control') }}
</div>
<div class="row">
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.if_workorder_is_emailed_while_draft'): </label>
            {{ html()->select('setting[resetWorkorderDateEmailDraft]', $workorderWhenDraftOptions, config('bt.resetWorkorderDateEmailDraft'))->class('form-select') }}
        </div>
    </div>
    <div class="col-md-6"></div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.recalculate_workorders'): </label><br>
            @if (!config('app.demo'))
                <button type="button" class="btn btn-secondary" id="btn-recalculate-workorders"
                        data-loading-text="@lang('bt.recalculating_wait')">@lang('bt.recalculate')</button>
            @else
                Recalculate is disabled in the demo.
            @endif
            <p class="form-text text-muted">@lang('bt.recalculate_help_text')</p>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.wo_timesheet_companyname')</label>
            {{ html()->text('setting[tsCompanyName]', config('bt.tsCompanyName'))->class('form-control') }}
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.wo_timesheet_companycreatetime') </label>
            {{ html()->text('setting[tsCompanyCreate]', config('bt.tsCompanyCreate'))->class('form-control') }}
        </div>
    </div>
</div>
