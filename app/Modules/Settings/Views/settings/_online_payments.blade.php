@foreach ($merchantDrivers as $driver)
    @if($driver->getName() == 'Stripe')
    <h4>{{ $driver->getName() }} @lang('bt.deprecated')</h4>
    @else
    <h4>{{ $driver->getName() }} </h4>
    @endif
    <div class="row">
        <div class="col-md-2">
            <div class="mb-3">
                <label class="form-label fw-bold">@lang('bt.enabled')</label>
                {{ html()->select('setting[' . $driver->getSettingKey('enabled') . ']', [0=>trans('bt.no'),1=>trans('bt.yes')], $driver->getSetting('enabled'))->class('form-select') }}
            </div>
        </div>
        @foreach ($driver->getSettings() as $key => $setting)
            <div class="col-md-2">
                <div class="mb-3">
                    @if (!is_array($setting))
                        <label class="form-label fw-bold">{{ trans('bt.' . snake_case($setting)) }}</label>
                        {{ html()->text('setting[' . $driver->getSettingKey($setting) . ']', config('bt.' . $driver->getSettingKey($setting)))->class('form-control') }}
                    @else
                        <label class="form-label fw-bold">{{ trans('bt.' . snake_case($key)) }}</label>
                        {{ html()->select('setting[' . $driver->getSettingKey($key) . ']', $setting, config('bt.' . $driver->getSettingKey($key)))->class('form-select') }}
                    @endif
                </div>
            </div>
        @endforeach
        <div class="col-md-2">
            <div class="mb-3">
                <label class="form-label fw-bold">@lang('bt.payment_button_text')</label>
                {{ html()->text('setting[' . $driver->getSettingKey('paymentButtonText') . ']', $driver->getSetting('paymentButtonText'))->class('form-control') }}
            </div>
        </div>
    </div>
@endforeach
