@section('javaScript')
    @parent
    <script type="text/javascript">
        ready(function () {
            addEvent(document, 'click', "#btn-check-update", (e) => {
                axios.get('{{ route('settings.updateCheck') }}')
                    .then(function (response) {
                        notify(response.data.message, 'info');
                    }).catch(function (error) {
                    notify("@lang('bt.unknown_error')", 'error');
                })
            });
        });
    </script>
@stop
<div class="row">
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.header_title_text'): </label>
            {!! Form::text('setting[headerTitleText]', config('bt.headerTitleText'), ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.default_company_profile'): </label>
            {!! Form::select('setting[defaultCompanyProfile]', $companyProfiles, config('bt.defaultCompanyProfile'), ['class' => 'form-select']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.version'): </label>
            <div class="input-group">
                {!! Form::text('version', config('bt.version'), ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                    @if (!config('app.demo'))
                        <button class="btn btn-secondary input-group-text " id="btn-check-update"
                                type="button">@lang('bt.check_for_update') </button>
                    @else
                        Check updates are disabled in the demo.
                    @endif
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-2">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.language'): </label>
            {!! Form::select('setting[language]', $languages, config('bt.language'), ['class' => 'form-select']) !!}
        </div>
    </div>
    <div class="col-md-2">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.date_format'): </label>
            {!! Form::select('setting[dateFormat]', $dateFormats, config('bt.dateFormat'), ['class' => 'form-select']) !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.use_24_hour_time_format'): </label>
            {!! Form::select('setting[use24HourTimeFormat]', $yesNoArray, config('bt.use24HourTimeFormat'), ['class' => 'form-select']) !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.timezone'): </label>
            {!! Form::select('setting[timezone]', $timezones, config('bt.timezone'), ['class' => 'form-select']) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-2">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.skin_theme'): </label>
            {!! Form::select('skin[headBackground]', $skins, json_decode(config('bt.skin'),true)['headBackground'], ['class' => 'form-select']) !!}
        </div>
    </div>
    <div class="col-md-2">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.skin_sidebar_theme'): </label>
            {!! Form::select('skin[headClass]', ['dark'=>'Dark', 'light'=>'Light'], json_decode(config('bt.skin'),true)['headClass'], ['class' => 'form-select']) !!}
        </div>
    </div>
    <div class="col-md-2">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.skin_menu_mode'): </label>
            {!! Form::select('skin[sidebarMode]', ['open'=>'Open', 'mini'=>'Collapse'], json_decode(config('bt.skin'),true)['sidebarMode'], ['class' => 'form-select']) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label fw-bold">@lang('bt.display_client_unique_name'): </label>
                    {!! Form::select('setting[displayClientUniqueName]', $clientUniqueNameOptions, config('bt.displayClientUniqueName'), ['class' => 'form-select']) !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label fw-bold">@lang('bt.quantity_price_decimals'): </label>
                            {!! Form::select('setting[amountDecimals]', $amountDecimalOptions, config('bt.amountDecimals'), ['class' => 'form-select']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label fw-bold">@lang('bt.round_tax_decimals'): </label>
                            {!! Form::select('setting[roundTaxDecimals]', $roundTaxDecimalOptions, config('bt.roundTaxDecimals'), ['class' => 'form-select']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.address_format'): </label>
            {!! Form::textarea('setting[addressFormat]', config('bt.addressFormat'), ['class' => 'form-control', 'rows' => 5]) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label fw-bold">@lang('bt.base_currency'): </label>
                    {!! Form::select('setting[baseCurrency]', $currencies, config('bt.baseCurrency'), ['class' => 'form-select']) !!}
                </div>
            </div>
            <div class="col-md-3">
                <div>
                    <label class="form-label fw-bold">@lang('bt.fixerio_api_key'): </label>
                    {!! Form::text('setting[currencyConversionKey]', config('bt.currencyConversionKey'), ['class' => 'form-control', 'placeholder' => 'Get a free API key at https://fixer.io', 'title' => 'Get a free API key at https://fixer.io']) !!}
                </div>
                {{--Why is this here?? because the latest version of Chrome 98.0.4758.80 insists on treating the 2nd text field in the form as a password autofill....--}}
                {!! Form::text('stupidchrome', 'Get a free API key at https://fixer.io', ['class' => 'form-control mb-3', 'readonly']) !!}
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label fw-bold">@lang('bt.exchange_rate_mode'): </label>
                    {!! Form::select('setting[exchangeRateMode]', $exchangeRateModes, config('bt.exchangeRateMode'), ['class' => 'form-select']) !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label fw-bold">@lang('bt.results_per_page'):</label>
                    {!! Form::select('setting[resultsPerPage]', $resultsPerPage, config('bt.resultsPerPage'), ['class' => 'form-select']) !!}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.restolup') </label>
            {!! Form::select('setting[restolup]', [0=>trans('bt.no'),1=>trans('bt.yes')], config('bt.restolup'),
                            ['class' => 'form-select']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.emptolup') </label>
            {!! Form::select('setting[emptolup]', [0=>trans('bt.no'),1=>trans('bt.yes')], config('bt.emptolup'),
                            ['class' => 'form-select']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.force_https'):</label>
            {!! Form::select('setting[forceHttps]', $yesNoArray, config('bt.forceHttps'), ['class' => 'form-select', 'title' => trans('bt.force_https_help') ]) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="mb-3">
            @if (!config('app.demo'))
                <a href="{{action('BT\Modules\Products\Controllers\ProductController@forceLUTupdate',['ret' => 0])}}"
                   class="btn btn-warning">@lang('bt.force_product_update')</a>
            @else
                Force updates are disabled in the demo.
            @endif
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            @if (!config('app.demo'))
                <a href="{{action('BT\Modules\Employees\Controllers\EmployeeController@forceLUTupdate',['ret' => 0])}}"
                   class="btn btn-warning">@lang('bt.force_employee_update')</a>
            @else
                Force updates are disabled in the demo.
            @endif
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <p class="form-text text-muted">@lang('bt.force_https_help')</p>
        </div>
    </div>
</div>
