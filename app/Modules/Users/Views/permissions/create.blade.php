@extends('layouts.master')

@section('content')
    @include('layouts._alerts')
    <section class="app-content-header">
        <form method='POST' action="{{ route('users.permissions.store') }}">
            @csrf
            <div class="card card-light">
                <div class="card-header">
                    <h3 class="card-title"><i
                                class="fa fa-edit fa-fw float-start"></i>
                        @lang('bt.acl_edit_permission')
                    </h3>
                    <a class="btn btn-warning float-end" href="{{ $returnUrl }}"><i
                                class="fa fa-ban"></i> @lang('bt.cancel')</a>
                    <button type="submit" class="btn btn-primary float-end"><i
                                class="fa fa-save"></i> @lang('bt.save') </button>
                </div>
                <div class="card-body">
                    <div class="form-group col-md-3 mb-3">
                        <label class="fw-bold mb-1" for="name">@lang('bt.acl_perm_name')</label>
                        <input type="text" name="name" value="" class='form-control'
                               placeholder='@lang('bt.acl_perm_name')'>
                    </div>
                    <div class="form-group col-md-3 mb-3">
                        <label class="fw-bold mb-1" for="description">@lang('bt.description')</label>
                        <textarea name="description" class='form-control' placeholder='@lang('bt.description')'></textarea>
                    </div>
                    <div class="form-group col-md-3 mb-3">
                        <label class="fw-bold mb-1" for="name">@lang('bt.acl_perm_group')</label>
                        <input type="text" name="group" value="" class='form-control'
                               placeholder='@lang('bt.acl_perm_group')'>
                    </div>
                    <div class="form-group col-md-3 mb-3">
                        <label class="fw-bold mb-1" for="guard_name">@lang('bt.acl_guard_name')</label>
                        <input type="text" name="guard_name" value="web" class='form-control'
                               placeholder='@lang('bt.acl_guard_name')'>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection
