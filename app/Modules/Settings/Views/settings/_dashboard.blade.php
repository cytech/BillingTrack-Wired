{{--<div class="row col-md-6">--}}
{{--    <div class="col-md-4">--}}
{{--        <div class="mb-3">--}}
{{--            <label class="form-label fw-bold">@lang('bt.display_profile_image'): </label>--}}
{{--                {{ html()->select('setting[displayProfileImage]', $yesNoArray, config('bt.displayProfileImage'))->class('form-select') }}--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
<div class="col-md-2">
    <div class="mb-3">
        <label class="form-label fw-bold">@lang('bt.avatar_driver'): </label>
        {{ html()->select('setting[profileImageDriver]', $profileImageDrivers, config('bt.profileImageDriver'))->class('form-select') }}
    </div>
</div>

@foreach ($dashboardWidgets as $widget)
    <h4 style="font-weight: bold; clear: both;">{{ $widget }}</h4>
    <div class="row col-md-6">
        <div class="col-md-4">
            <div class="mb-3">
                <label class="form-label fw-bold">@lang('bt.enabled'): </label>
                {{ html()->select('setting[widgetEnabled' . $widget . ']', $yesNoArray, config('bt.widgetEnabled' .
                $widget))->class('form-select')->attribute('id', 'widgetEnabled' . $widget) }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="mb-3">
                <label class="form-label fw-bold">@lang('bt.display_order'): </label>
                {{ html()->select('setting[widgetDisplayOrder' . $widget . ']', $displayOrderArray,
                config('bt.widgetDisplayOrder' . $widget))->class('form-select')->attribute('id', 'widgetDisplayOrder' . $widget) }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="mb-3">
                <label class="form-label fw-bold">@lang('bt.column_width'): </label>
                {{ html()->select('setting[widgetColumnWidth' . $widget . ']', $colWidthArray,
                config('bt.widgetColumnWidth' . $widget))->class('form-select')->attribute('id', 'widgetColumnWidth' . $widget) }}
            </div>
        </div>
    </div>

    @if (view()->exists($widget . 'WidgetSettings'))
        <div class="col-md-6">
            @include($widget . 'WidgetSettings')
        </div>
    @endif

@endforeach
<div class="row"></div>
