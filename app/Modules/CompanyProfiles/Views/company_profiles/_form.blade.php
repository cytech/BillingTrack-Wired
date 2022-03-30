<script type="text/javascript">
    ready(function () {
        document.getElementById('name').focus()
    })
</script>
<div class="container-fluid m-0 p-0">
    <div class="card-group">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">@lang('bt.company_profile')</h4>
            </div>
            <div class="card-body">
                <div class="row col-md-12 mb-1" id="col-companyprofile-name">
                    <div class="col-md-4 fw-bold text-end">
                        <label class="col-form-label">* @lang('bt.name'): </label>
                    </div>
                    <div class="col-md-8">
                        {!! Form::text('company', null, ['id' => 'name', 'class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="row col-md-12 mb-1" id="col-companyprofile-email">
                    <div class="col-md-4 fw-bold text-end">
                        <label class="col-form-label">@lang('bt.email_address'): </label>
                    </div>
                    <div class="col-md-8">
                        {!! Form::text('email', null, ['id' => 'email', 'class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="row col-md-12 mb-1">
                    <div class="col-md-4 fw-bold text-end">
                        <label class="col-form-label">@lang('bt.phone_number'): </label>
                    </div>
                    <div class="col-md-8">
                        {!! Form::text('phone', null, ['id' => 'phone', 'class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="row col-md-12 mb-1">
                    <div class="col-md-4 fw-bold text-end">
                        <label class="col-form-label">@lang('bt.fax_number'): </label>
                    </div>
                    <div class="col-md-8">
                        {!! Form::text('fax', null, ['id' => 'fax', 'class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="row col-md-12 mb-1">
                    <div class="col-md-4 fw-bold text-end">
                        <label class="col-form-label">@lang('bt.mobile_number'): </label>
                    </div>
                    <div class="col-md-8">
                        {!! Form::text('mobile', null, ['id' => 'mobile', 'class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="row col-md-12 mb-1">
                    <div class="col-md-4 fw-bold text-end">
                        <label class="col-form-label">@lang('bt.web_address'): </label>
                    </div>
                    <div class="col-md-8">
                        {!! Form::text('web', null, ['id' => 'web', 'class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="row col-md-12 mb-1">
                    <div class="col-md-4 fw-bold text-end">
                        <label class="col-form-label">@lang('bt.id_number'): </label>
                    </div>
                    <div class="col-md-8">
                        {!! Form::text('id_number', null, ['id' => 'id_number', 'class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="row col-md-12 mb-1">
                    <div class="col-md-4 fw-bold text-end">
                        <label class="col-form-label">@lang('bt.vat_number'): </label>
                    </div>
                    <div class="col-md-8">
                        {!! Form::text('vat_number', null, ['id' => 'vat_number', 'class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">@lang('bt.address')</h4>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs p-2">
                    <li class="nav-item"><a class="nav-link active show" href="#tab-address"
                                            data-bs-toggle="tab">@lang('bt.billing_address')</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tab-address_2"
                                            data-bs-toggle="tab">@lang('bt.shipping_address')</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab-address">
                        <div class="row mx-1 mt-3">
                            <label for="address" class="fw-bold mb-1">@lang('bt.billing_address'): </label>
                            {!! Form::textarea('address', null, ['id' => 'address', 'class' => 'form-control mb-3', 'rows' => 2]) !!}
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label fw-bold mb-1">@lang('bt.city'): </label>
                                    {!! Form::text('city', null, ['id' => 'city', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label fw-bold mb-1">@lang('bt.state'): </label>
                                    {!! Form::text('state', null, ['id' => 'state', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label fw-bold mb-1">@lang('bt.postal_code'): </label>
                                    {!! Form::text('zip', null, ['id' => 'zip', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label fw-bold mb-1">@lang('bt.country'): </label>
                                    {!! Form::text('country', null, ['id' => 'country', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane " id="tab-address_2">
                        <div class=" d-flex mt-3 mb-3 justify-content-center">
                            <div class="form-check form-switch form-switch-md">
                                {!! Form::checkbox('fill_shipping', 1, null, ['id' => 'fill_shipping', 'class' => 'form-check-input']) !!}
                                <label class="form-check-label fw-bold ps-3 pt-2" for="fill_shipping">@lang('bt.copy_billing')</label>
                            </div>
                        </div>
                        <div class="row mx-1">
                            <label class="form-label fw-bold mb-1">@lang('bt.shipping_address'): </label>
                            {!! Form::textarea('address_2', null, ['id' => 'address_2', 'class' => 'form-control mb-3', 'rows' => 2]) !!}
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label fw-bold mb-1">@lang('bt.city'): </label>
                                    {!! Form::text('city_2', null, ['id' => 'city_2', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label fw-bold mb-1">@lang('bt.state'): </label>
                                    {!! Form::text('state_2', null, ['id' => 'state_2', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label fw-bold mb-1">@lang('bt.postal_code'): </label>
                                    {!! Form::text('zip_2', null, ['id' => 'zip_2', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label fw-bold mb-1">@lang('bt.country'): </label>
                                    {!! Form::text('country_2', null, ['id' => 'country_2', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        addEvent(document, 'change', '#fill_shipping', (e) => {
                            if (e.target.checked) {
                                document.getElementById('address_2').value = document.getElementById('address').value
                                document.getElementById('city_2').value = document.getElementById('city').value
                                document.getElementById('state_2').value = document.getElementById('state').value
                                document.getElementById('zip_2').value = document.getElementById('zip').value
                                document.getElementById('country_2').value = document.getElementById('country').value
                            } else {
                                document.getElementById('address_2').value = ''
                                document.getElementById('city_2').value = ''
                                document.getElementById('state_2').value = ''
                                document.getElementById('zip_2').value = ''
                                document.getElementById('country_2').value = ''
                            }
                        })
                    </script>
                </div>
            </div>
        </div>
    </div>
    <div class="card-group">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">@lang('bt.other')</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label fw-bold mb-1">@lang('bt.logo'): </label>
                            @if (!config('app.demo'))
                                <div id="div-logo">
                                    @if ($editMode and $companyProfile->logo)
                                        <p>{!! $companyProfile->logo(100) !!}</p>
                                        <button class="btn btn-link" type="button"
                                                id="btn-delete-logo">@lang('bt.remove_logo')</button>
                                    @endif
                                </div>
                                {!! Form::file('logo') !!}
                            @else
                                Disabled for demo
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label fw-bold mb-1">@lang('bt.default_quote_template'):</label>
                            {!! Form::select('quote_template', $quoteTemplates, ((isset($companyProfile)) ? $companyProfile->quote_template : config('bt.quoteTemplate')), ['id' => 'invoice_template', 'class' => 'form-select']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label fw-bold mb-1">@lang('bt.default_workorder_template'):</label>
                            {!! Form::select('workorder_template', $workorderTemplates, ((isset($companyProfile)) ? $companyProfile->workorder_template : config('bt.workorderTemplate')), ['id' => 'invoice_template', 'class' => 'form-select']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label fw-bold mb-1">@lang('bt.default_invoice_template'):</label>
                            {!! Form::select('invoice_template', $invoiceTemplates, ((isset($companyProfile)) ? $companyProfile->invoice_template : config('bt.invoiceTemplate')), ['id' => 'invoice_template', 'class' => 'form-select']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label fw-bold mb-1">@lang('bt.default_purchaseorder_template'):</label>
                            {!! Form::select('purchaseorder_template', $purchaseorderTemplates, ((isset($companyProfile)) ? $companyProfile->purchaseorder_template : config('bt.purchaseorderTemplate')), ['id' => 'purchaseorder_template', 'class' => 'form-select']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold mb-1">@lang('bt.default_currency'): </label>
                            {!! Form::select('currency_code', $currencies, ((isset($companyProfile)) ? $companyProfile->currency_code : config('bt.baseCurrency')), ['id' => 'currency_code', 'class' => 'form-select']) !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold mb-1">@lang('bt.language'): </label>
                            {!! Form::select('language', $languages, ((isset($companyProfile)) ? $companyProfile->language : config('bt.language')), ['id' => 'language', 'class' => 'form-select']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($customFields->count())
        @include('custom_fields._custom_fields')
    @endif
</div>
