<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.past_days')</label>
            {{ html()->text('setting[schedulerPastdays]', config('bt.schedulerPastdays'))->class('form-control') }}
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.event_limit')</label>
            {{ html()->text('setting[schedulerEventLimit]', config('bt.schedulerEventLimit'))->class('form-control') }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.enable_cwo')</label>
            {{ html()->select('setting[schedulerCreateWorkorder]', ['0' => 'No', '1' => 'Yes'], config('bt.schedulerCreateWorkorder'))->class('form-select') }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.fc_themesystem')</label>
            {{ html()->select('setting[schedulerFcThemeSystem]', ['standard' => 'Standard', 'bootstrap5' => 'Bootstrap5'], config('bt.schedulerFcThemeSystem'))->class('form-select') }}
        </div>
    </div>
    <div id='cp3' class="mb-3 col-md-3">
        <label class="form-label fw-bold">@lang('bt.fc_todaybgcolor')</label>
        {{ html()->input('color', 'setting[schedulerFcTodaybgColor]', config('bt.schedulerFcTodaybgColor'))->class('form-control') }}
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.timestep')</label>
            {{ html()->select('setting[schedulerTimestep]',['60' => '60', '30' => '30', '15' => '15', '10' => '10','5' => '5','1' => '1'], config('bt.schedulerTimestep'))->class('form-select') }}
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.fc_aspectratio')</label>
            {{ html()->number('setting[schedulerFcAspectRatio]', config('bt.schedulerFcAspectRatio'), '1', '2', '.05')->class('form-control') }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.coreeventslist')</label>
            <div class="col-lg-8 col-sm-8">
                @foreach (\BT\Modules\Settings\Models\Setting::$coreevents as $entityType => $value)
                    <div class="form-check">
                        <label for="enabledCoreEvents{{ $value}}" class="form-check-label">
                            <input name="enabledCoreEvents[]" id="enabledCoreEvents{{ $value}}" type="checkbox"
                                   {{ (new \BT\Modules\Settings\Models\Setting())->isCoreeventEnabled($entityType) ? 'checked="checked"' : '' }}
                                   value="{{ $value }}" class="form-check-input">{{ trans("bt.{$entityType}") }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.show_invoiced')</label>
            {{ html()->select('setting[schedulerDisplayInvoiced]', ['0' => 'No', '1' => 'Yes'], config('bt.schedulerDisplayInvoiced'))->class('form-select') }}
        </div>
    </div>
    <div class="col-md-5 mt-4">
        <p class="form-text text-muted">
            {!! __('bt.scheduler_display_defaults')  !!}
        </p>
    </div>
</div>
