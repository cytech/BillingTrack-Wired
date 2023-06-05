<div class="mb-3">
    <label class="form-label fw-bold">@lang('bt.default_item_tax_rate'): </label>
    {{ html()->select('setting[itemTaxRate]', $taxRates, config('bt.itemTaxRate'))->class('form-select') }}
</div>
<div class="mb-3">
    <label class="form-label fw-bold">@lang('bt.default_item_tax_2_rate'): </label>
    {{ html()->select('setting[itemTax2Rate]', $taxRates, config('bt.itemTax2Rate'))->class('form-select') }}
</div>
