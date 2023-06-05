@extends('layouts.master')

@section('content')
    <script type="text/javascript">
        ready(function () {
            // document.getElementById('name').focus()
            @if ($editMode)
            addEvent(document, 'click', '#btn-delete-logo', (e) => {
                axios.post("{{ route('companyProfiles.deleteLogo', [$companyProfile->id]) }}").then(function () {
                    document.getElementById('div-logo').innerHTML = ''
                });
            });
            @endif
        });
    </script>

    @if ($editMode)
        {{ html()->modelForm($companyProfile, 'POST', route('companyProfiles.update', $companyProfile->id))->acceptsFiles()->open() }}
    @else
        {{ html()->form('POST', route('companyProfiles.store'))->acceptsFiles()->open() }}
    @endif

    <section class="app-content-header">
        <div class="container-fluid">
            <div class="col-sm-12">
                <div class="fs-3 float-start">@lang('bt.company_profile_form')</div>
        <a class="btn btn-warning float-end" href={!! route('companyProfiles.index')  !!}><i
                    class="fa fa-ban"></i> @lang('bt.cancel')</a>
        <button type="submit" class="btn btn-primary float-end"><i
                    class="fa fa-save"></i> @lang('bt.save') </button>
        <div class="clearfix"></div>
            </div></div>
    </section>
    <section class="container-fluid">
        @include('layouts._alerts')
        <div class=" card card-light">
            <div class="card-body">
                @include('company_profiles._form')
            </div>
        </div>
    </section>
    @if ($editMode)
        {{ html()->closeModelForm() }}
    @else
        {{ html()->form()->close() }}
    @endif
@stop
