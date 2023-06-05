@extends('layouts.master')

@section('content')
    @include('layouts._alerts')

    <section class="app-content-header">
        {{ html()->modelForm($categories, 'PUT', route('scheduler.categories.update', $categories->id))->attribute('id', 'categories_form')->class('form-horizontal')->open() }}
        <div class="card card-light">
            <div class="card-header">
                <h3 class="card-title"><i
                            class="fa fa-edit fa-fw"></i>
                    @lang('bt.edit_category')
                </h3>
                    <a class="btn btn-warning float-end" href={!! URL::previous()  !!}><i
                                class="fa fa-ban"></i> @lang('bt.cancel') </a>
                    <button type="submit" class="btn btn-primary float-end"><i
                                class="fa fa-save"></i> @lang('bt.update')</button>
            </div>
            <div class="card-body">

                <!-- Name input-->
                <div class="mb-3">
                    {{ html()->label(trans('bt.category_name'), 'name')->class('col-sm-2 col-form-label') }}
                    <div class="col-md-3">
                        {{ html()->text('name',$categories->name)->class('form-control')->placeholder('Category Name')->attribute('autocomplete', 'off') }}
                    </div>
                </div>
            <!-- text_color input-->
                <div id="cp1" class="mb-3">
                    {{ html()->label(trans('bt.category_text_color'), 'text_color')->class('col-sm-2 col-form-label') }}
                    <div class="col-md-3">
                        {{ html()->input('color', 'text_color', $categories->text_color)->placeholder('Text Color')->class('form-control')->attribute('autocomplete', 'off') }}
                    </div>
                </div>
                <!-- text_color input-->
                <div id="cp2" class="mb-3">
                    {{ html()->label(trans('bt.category_bg_color'), 'bg_color')->class('col-sm-2 col-form-label') }}
                    <div class="col-md-3">
                        {{ html()->input('color', 'bg_color', $categories->bg_color)->placeholder('Background Color')->class('form-control')->attribute('autocomplete', 'off') }}
                    </div>
                </div>
            </div>
        </div>

    </section>
    {{ html()->closeModelForm() }}
@stop
