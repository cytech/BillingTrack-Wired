@include('clients._js_unique_name')
<script type="text/javascript">
    ready(function () {
        document.getElementById('name').focus()
    })
</script>
<div class="container-fluid m-0 p-0">
    <div class="card-group">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">@lang('bt.client')</h4>
            </div>
            <div class="card-body">
{{--                <div class="row col-md-12 mb-1" id="col-client-type">--}}
{{--                    <div class="col-md-4 fw-bold text-end">--}}
{{--                        <label class="col-form-label">@lang('bt.client_type'): </label>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-8">--}}
{{--                         {{ html()->select('client_type', ['0' => trans('bt.lead'), '1' => trans('bt.prospect'), '2' => trans('bt.customer'), '3' => trans('bt.affiliate')], ((isset($editMode) and $editMode) ? null : 0))->class('form-select') }}--}}
{{--                    </div>--}}
{{--                </div>--}}
                <div class="row col-md-12 mb-1" id="col-client-is_company">
                    <div class="col-md-4 fw-bold text-end">
                        <label class="col-form-label">@lang('bt.company_or_individual'): </label>
                    </div>
                    <div class="col-md-8">
                        {{ html()->select('is_company', ['0' => trans('bt.individual'), '1' => trans('bt.company')], ((isset($editMode) and $editMode) ? null : 0))->class('form-select') }}
                    </div>
                </div>
                <div class="row col-md-12  " id="col-client-name">
                    <div class="col-md-4 fw-bold text-end">
                        <label class="col-form-label">* @lang('bt.client_name'): </label>
                    </div>
                    <div class="col-md-8">
                        {{ html()->text('name', null)->class('form-control')->attribute('autocomplete', 'off') }}
                        <div class="form-text text-muted">
                            <small>@lang('bt.help_text_client_name')
{{--                                @if(!$editMode)--}}
                                    <button class="btn btn-link btn-sm" type="button" id="btn-show-unique-name"
                                            tabindex="-1">@lang('bt.view_unique_name')</button>
{{--                                @endif--}}
                            </small>
                        </div>
                    </div>
                </div>
                @if($editMode)
                    <div class="row col-md-12" id="col-client-unique-name" style="display: flex">
                        <div class="col-md-4 fw-bold text-end">
                            <label class="col-form-label">* @lang('bt.unique_name'): </label>
                        </div>
                        <div class="col-md-4">
                            {{ html()->text('unique_name_pre', $client->unique_name_prefix)->class('form-control')->isReadonly() }}
                        </div>
                        <div class="col-md-4">
                            {{ html()->text('unique_name_suf', $client->unique_name_suffix)->class('form-control') }}
                        </div>
                        <div>
                            {{ html()->hidden('unique_name', $client->unique_name)->class('form-control') }}
                        </div>
                    </div>
                    <br/>
                @else
                    <div class="row col-md-12 " id="col-client-unique-name" style="display: none;">
                        <div class="col-md-4 fw-bold text-end">
                            <label class="col-form-label">* @lang('bt.unique_name'): </label>
                        </div>
                        <div class="col-md-4">
                            {{ html()->text('unique_name_pre', null)->class('form-control')->isReadonly() }}
                        </div>
                        <div class="col-md-4">
                            {{ html()->text('unique_name_suf', null)->class('form-control') }}
                        </div>
                        <div>
                            {{ html()->hidden('unique_name', null)->class('form-control') }}
                        </div>
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-8">
                            <p id="help_text_client_unique_name" class="form-text text-muted">
                                <small>@lang('bt.help_text_client_unique_name')</small>
                            </p>
                        </div>
                    </div>
                @endif
                <div class="row col-md-12 mb-1" id="col-client-email">
                    <div class="col-md-4 fw-bold text-end">
                        <label class="col-form-label">@lang('bt.email_address'): </label>
                    </div>
                    <div class="col-md-8">
                        {{ html()->text('client_email', null)->class('form-control') }}
                    </div>
                </div>
                <div class="row col-md-12 mb-1">
                    <div class="col-md-4 fw-bold text-end">
                        <label class="col-form-label">@lang('bt.phone_number'): </label>
                    </div>
                    <div class="col-md-8">
                        {{ html()->text('phone', null)->class('form-control') }}
                    </div>
                </div>
                <div class="row col-md-12 mb-1">
                    <div class="col-md-4 fw-bold text-end">
                        <label class="col-form-label">@lang('bt.fax_number'): </label>
                    </div>
                    <div class="col-md-8">
                        {{ html()->text('fax', null)->class('form-control') }}
                    </div>
                </div>
                <div class="row col-md-12 mb-1">
                    <div class="col-md-4 fw-bold text-end">
                        <label class="col-form-label">@lang('bt.mobile_number'): </label>
                    </div>
                    <div class="col-md-8">
                        {{ html()->text('mobile', null)->class('form-control') }}
                    </div>
                </div>
                <div class="row col-md-12 mb-1">
                    <div class="col-md-4 fw-bold text-end">
                        <label class="col-form-label">@lang('bt.web_address'): </label>
                    </div>
                    <div class="col-md-8">
                        {{ html()->text('web', null)->class('form-control') }}
                    </div>
                </div>
                <div class="row col-md-12 mb-1">
                    <div class="col-md-4 fw-bold text-end">
                        <label class="col-form-label">@lang('bt.id_number'): </label>
                    </div>
                    <div class="col-md-8">
                        {{ html()->text('id_number', null)->class('form-control') }}
                    </div>
                </div>
                <div class="row col-md-12 mb-1">
                    <div class="col-md-4 fw-bold text-end">
                        <label class="col-form-label">@lang('bt.vat_number'): </label>
                    </div>
                    <div class="col-md-8">
                        {{ html()->text('vat_number', null)->class('form-control') }}
                    </div>
                </div>
                <div class="row col-md-12" id="col-client-active">
                    <div class="col-md-4 fw-bold text-end">
                        <label class="col-form-label">@lang('bt.active'): </label>
                    </div>
                    <div class="col-md-8">
                        {{ html()->select('active', ['0' => trans('bt.no'), '1' => trans('bt.yes')], ((isset($editMode) and $editMode) ? null : 1))->class('form-select') }}
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
                            {{ html()->textarea('address', null)->rows(2)->class('form-control mb-3') }}
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label fw-bold mb-1">@lang('bt.city'): </label>
                                    {{ html()->text('city', null)->class('form-control') }}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label fw-bold mb-1">@lang('bt.state'): </label>
                                    {{ html()->text('state', null)->class('form-control') }}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label fw-bold mb-1">@lang('bt.postal_code'): </label>
                                    {{ html()->text('zip', null)->class('form-control') }}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label fw-bold mb-1">@lang('bt.country'): </label>
                                    {{ html()->text('country', null)->class('form-control') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane " id="tab-address_2">
                        <div class=" d-flex mt-3 mb-3 justify-content-center">
                            <div class="form-check form-switch form-switch-md">
                                {{ html()->checkbox('fill_shipping', null, 1)->class('form-check-input') }}
                                <label class="form-check-label fw-bold ps-3 pt-2" for="fill_shipping">@lang('bt.copy_billing')</label>
                            </div>
                        </div>
                        <div class="row mx-1">
                            <label class="form-label fw-bold mb-1">@lang('bt.shipping_address'): </label>
                            {{ html()->textarea('address_2', null)->rows(2)->class('form-control mb-3') }}
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label fw-bold mb-1">@lang('bt.city'): </label>
                                    {{ html()->text('city_2', null)->class('form-control') }}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label fw-bold mb-1">@lang('bt.state'): </label>
                                    {{ html()->text('state_2', null)->class('form-control') }}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label fw-bold mb-1">@lang('bt.postal_code'): </label>
                                    {{ html()->text('zip_2', null)->class('form-control') }}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label fw-bold mb-1">@lang('bt.country'): </label>
                                    {{ html()->text('country_2', null)->class('form-control') }}
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
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label fw-bold mb-1 text-danger">@lang('bt.client') @lang('bt.flag_text'): </label>
                            {{ html()->text('flag_text', null)->maxlength(50)->class('form-control mb-3') }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold mb-1">@lang('bt.default_currency'): </label>
                            {{ html()->select('currency_code', $currencies, ((isset($client)) ? $client->currency_code : config('bt.baseCurrency')))->class('form-select mb-3') }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold mb-1">@lang('bt.language'): </label>
                            {{ html()->select('language', $languages, ((isset($client)) ? $client->language : config('bt.language')))->class('form-select mb-3') }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold mb-1">@lang('bt.payment_terms'): </label>
                            {{ html()->select('paymentterm_id', $payment_terms, null)->class('form-select mb-3') }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold mb-1">@lang('bt.industry'): </label>
                            {{ html()->select('industry_id', $industries, null)->class('form-select mb-3') }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold mb-1">@lang('bt.size'): </label>
                            {{ html()->select('size_id', $sizes, null)->class('form-select mb-3') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($customFields->count())
        @if ($editMode)
            @include('custom_fields._custom_fields', ['object' => $client])
        @else
            @include('custom_fields._custom_fields')
        @endif
    @endif
</div>
