@extends('layouts.master')

@section('content')
    <!--basic form starts-->
    @include('layouts._alerts')
    <section class="app-content-header">
        {{ html()->form('POST', route('products.store'))->attribute('autocomplete', 'off')->class('form-horizontal')->open() }}
        <div class="card card-light">
            <div class="card-header">
                <div class="card-title h4 mt-2">
                    @lang('bt.create_product')
                </div>
                <a class="btn btn-warning float-end" href="{{ $returnUrl }}"><i
                            class="fa fa-ban"></i> @lang('bt.cancel')</a>
                <button type="submit" class="btn btn-primary float-end"><i
                            class="fa fa-save"></i> @lang('bt.save') </button>
            </div>
            <div class="card-body">
                <!-- Name input-->
                 <div class="row col-md-6 mb-3">
                    <div class="col-md-4 text-end">
                        <label class="col-form-label fw-bold"
                           for="name">@lang('bt.product_name')</label>
                    </div>
                    <div class="col-md-8">
                        {{ html()->text('name',old('name'))->class('form-control') }}
                    </div>
                </div>
                <!-- Description input-->
                 <div class="row col-md-6 mb-3">
                    <div class="col-md-4 text-end">
                        <label class="col-form-label fw-bold"
                           for="description">@lang('bt.product_description')</label>
                    </div>
                    <div class="col-md-8">
                        {{ html()->text('description',old('description'))->class('form-control') }}
                    </div>
                </div>
                <!-- Serial Number input-->
                 <div class="row col-md-6 mb-3">
                    <div class="col-md-4 text-end">
                        <label class="col-form-label fw-bold"
                           for="serialnum">@lang('bt.product_partnum')</label>
                    </div>
                    <div class="col-md-8">
                        {{ html()->text('serialnum',old('serialnum'))->class('form-control') }}
                    </div>
                </div>
                <!-- Sales Price input-->
                <div class="row col-md-6 mb-3">
                    <div class="col-md-4 text-end">
                        <label class="col-form-label fw-bold"
                           for="price">@lang('bt.price_sales')</label>
                </div>
                    <div class="col-md-8">
                        {{ html()->text('price',old('price'))->class('form-control') }}
                    </div>
                </div>
                <!-- Active Checkbox-->
                <div class="row col-md-6 mb-3 align-items-center">
                    <div class="col-md-4 text-end">
                        <label class="form-check-label fw-bold"
                           for="active">@lang('bt.product_active')</label>
                    </div>
                    <div class="col-md-8 form-check form-switch form-switch-md ps-5">
                        {{ html()->checkbox('active', old('active'), 1)->class('form-check-input') }}
                    </div>
                </div>
                <!-- Vendor input-->
                 <div class="row col-md-6 mb-3">
                    <div class="col-md-4 text-end">
                        <label class="col-form-label fw-bold"
                           for="vendor">@lang('bt.vendor_preferred')</label>
                    </div>
                    <div class="col-md-8">
                        {{ html()->text('vendor',old('vendor'))->class('form-control')->attribute('list','vendlistid') }}
                        <datalist id='vendlistid'>
                            @foreach($vendors as $vendor)
                                <option>{!! $vendor !!}</option>
                            @endforeach
                        </datalist>
                    </div>
                </div>
                <!-- Cost input-->
                 <div class="row col-md-6 mb-3">
                    <div class="col-md-4 text-end">
                        <label class="col-form-label fw-bold"
                           for="cost">@lang('bt.product_cost')</label>
                    </div>
                    <div class="col-md-8">
                        {{ html()->text('cost',old('cost'))->class('form-control') }}
                    </div>
                </div>
                <!-- Category input-->
                 <div class="row col-md-6 mb-3">
                    <div class="col-md-4 text-end">
                        <label class="col-form-label fw-bold"
                           for="category">@lang('bt.product_category')</label>
                    </div>
                    <div class="col-md-8">
                        {{ html()->text('category',old('category'))->class('form-control')->attribute('list', 'prodlistid') }}
                        <datalist id='prodlistid'>
                            @foreach($categories as $category)
                                <option>{!! $category !!}</option>
                            @endforeach
                        </datalist>
                    </div>
                </div>
                <!-- Type input-->
                 <div class="row col-md-6 mb-3">
                    <div class="col-md-4 text-end">
                        <label class="col-form-label fw-bold"
                           for="type">@lang('bt.product_type')</label>
                    </div>
                    <div class="col-md-8">
{{--                        {!! Form::select('type', $inventorytypes, 1,['id'=>'type', 'class'=>'form-select'], $optionAttributes) !!}--}}
{{--                        {{ html()->select('type', $inventorytypes, $products->inventorytype ? $products->inventorytype->id : 1)->class('form-select') }}--}}
{{--                        Spatie laravel-html does not have the $optionAttributes ability of laravel collective--}}
                        <select name="type" id="type" class="form-select">
                            @foreach($inventorytypes as $k => $v)
                                <option value="{{ $k }}"
                                        @if($k == 1) selected="selected" @endif
                                        @if(array_key_exists($k,$optionAttributes)) style="background-color:lightgray" @endif
                                >{{ $v }}</option>
                            @endforeach
                        </select>

                    </div>
                </div>
                <!-- Numstock input-->
                 <div class="row col-md-6 mb-3">
                    <div class="col-md-4 text-end">
                        <label class="col-form-label fw-bold"
                           for="numstock">@lang('bt.product_numstock')</label>
                    </div>
                    <div class="col-md-8">
                        {{ html()->text('numstock',old('numstock'))->class('form-control') }}
                    </div>
                </div>
                <!-- taxrate inputs-->
                 <div class="row col-md-6 mb-3">
                    <div class="col-md-4 text-end">
                        <label class="col-form-label fw-bold">@lang('bt.tax_1'): </label>
                    </div>
                    <div class="col-md-8">
                        {{ html()->select('tax_rate_id', $taxRates, null)->class('form-select') }}
                    </div>
                </div>
                 <div class="row col-md-6 mb-3">
                    <div class="col-md-4 text-end">
                        <label class="col-form-label fw-bold">@lang('bt.tax_2'): </label>
                    </div>
                    <div class="col-md-8">
                        {{ html()->select('tax_rate_2_id', $taxRates, null)->class('form-select') }}
                    </div>
                </div>
            </div>
        </div>
        {{ html()->form()->close() }}
    </section>
@stop
