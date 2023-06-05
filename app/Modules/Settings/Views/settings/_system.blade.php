<div class="row">
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.enabled_modules')</label>
            <div class="col-lg-8 col-sm-8">
                @foreach (\BT\Modules\Settings\Models\Setting::$modules as $entityType => $value)
                    <div class="form-check">
                        <label for="enabledModules{{ $value}}">
                            <input name="enabledModules[]" id="enabledModules{{ $value}}" type="checkbox"
                                   {{ (new \BT\Modules\Settings\Models\Setting())->isModuleEnabled($entityType) ? 'checked="checked"' : '' }} value="{{ $value }}"> {{ trans("bt.{$entityType}") }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-4">
    </div>
    @if (!config('app.demo'))
        <div class="mb-3 col-md-4">
            <label class="form-label fw-bold">Application URL: </label>
            {{ html()->text('app_url',config('app.url'))->class('form-control')->isReadonly() }}
            <label class="form-label fw-bold">Debug Enabled? </label>
            {{ html()->select('debug', ['0' => 'No', '1' => 'Yes'], config('app.debug'))->class('form-select')->disabled() }}
            <label class="form-label fw-bold">Database Driver: </label>
            {{ html()->text('db_driver',config('database.connections.mysql.driver'))->class('form-control')->isReadonly() }}
            <label class="form-label fw-bold">Database Host: </label>
            {{ html()->text('db_host',env('DB_HOST', 'empty'))->class('form-control')->isReadonly() }}
            <label class="form-label fw-bold">Database: </label>
            {{ html()->text('db_database',env('DB_DATABASE','empty'))->class('form-control')->isReadonly() }}
            <label class="form-label fw-bold">Database UserName: </label>
            {{ html()->text('db_username',env('DB_USERNAME', 'empty'))->class('form-control')->isReadonly() }}
        </div>
    @endif
</div>
