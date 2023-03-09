@extends('layouts.master')

@section('content')
    @include('layouts._alerts')

    <section class="app-content-header">
        {!! Form::model($categories, array('route' => array('scheduler.categories.update', $categories->id),
                               'id'=>'categories_form','action'=>'#','method' => 'PUT', 'class'=>'form-horizontal')) !!}
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
                    {!! Form::label('name',trans('bt.category_name'),['class'=>'col-sm-2 col-form-label']) !!}
                    <div class="col-md-3">
                        {!! Form::text('name',$categories->name,['id'=>'name', 'placeholder'=>'Category Name', 'class'=>'form-control', 'autocomplete' => 'off']) !!}
                    </div>
                </div>
            <!-- text_color input-->
                <div id="cp1" class="mb-3">
                    {!! Form::label('text_color',trans('bt.category_text_color'),['class'=>'col-sm-2 col-form-label']) !!}
                    <div class="col-md-3">
                        {!! Form::color('text_color',$categories->text_color,['id'=>'text_color', 'placeholder'=>'Text Color', 'class'=>'form-control', 'autocomplete' => 'off']) !!}
                    </div>
                </div>
                <!-- text_color input-->
                <div id="cp2" class="mb-3">
                    {!! Form::label('bg_color',trans('bt.category_bg_color'),['class'=>'col-sm-2 col-form-label']) !!}
                    <div class="col-md-3">
                        {!! Form::color('bg_color',$categories->bg_color,['id'=>'bg_color', 'placeholder'=>'Background Color', 'class'=>'form-control', 'autocomplete' => 'off']) !!}
                    </div>
                </div>
            </div>
        </div>

    </section>
    {!! Form::close() !!}
@stop
