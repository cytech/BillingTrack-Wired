@extends('layouts.master')

@section('javaScript')
    @include('layouts._daterangepicker')
    <script type="text/javascript">
        ready(function () {
            addEvent(document, 'click', "#btn-run-report", (e) => {
                const from_date = document.getElementById('from_date').value
                const to_date = document.getElementById('to_date').value
                const company_profile_id = document.getElementById('company_profile_id').value

                axios.post("{{ route('reports.itemSales.validate') }}", {
                    from_date: from_date,
                    to_date: to_date,
                    company_profile_id: company_profile_id
                }).then(function () {
                    document.getElementById('form-validation-placeholder').innerHTML = ''
                    output_type = document.querySelector('input[name=output_type]:checked').value
                    query_string = "?from_date=" + from_date + "&to_date=" + to_date + "&company_profile_id=" + company_profile_id;
                    if (output_type === 'preview') {
                        document.getElementById('preview').style.display = 'block'
                        document.getElementById('preview-results').setAttribute('src', "{{ route('reports.itemSales.html') }}" + query_string)
                    } else if (output_type === 'pdf') {
                        window.location.href = "{{ route('reports.itemSales.pdf') }}" + query_string;
                    }
                }).catch(function (error) {
                    showErrors(error.response.data.errors, '#form-validation-placeholder');
                });
            });
        });
    </script>
@stop

@section('content')
    <section class="app-content-header">
        <div class="container-fluid">
            <div class="col-sm-12">
                <div class="fs-3 float-start">@lang('bt.item_sales')</div>
                <div class="btn-group float-end">
                    <button class="btn btn-primary" id="btn-run-report">@lang('bt.run_report')</button>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </section>
    <section class="container-fluid">
        <div id="form-validation-placeholder"></div>
        <div class="card card-light">
            <div class="card-header">
                <h3 class="card-title">@lang('bt.options')</h3>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">@lang('bt.company_profile'):</label>
                        {!! Form::select('company_profile_id', $companyProfiles, null, ['id' => 'company_profile_id', 'class' => 'form-select'])  !!}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">@lang('bt.date_range'):</label>
                        {!! Form::hidden('from_date', null, ['id' => 'from_date']) !!}
                        {!! Form::hidden('to_date', null, ['id' => 'to_date']) !!}
                        <div class="input-group">
                            {!! Form::text('date_range', null, ['id' => 'date_range', 'class' => 'form-control', 'readonly' => 'readonly']) !!}
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i> </span>
                        </div>                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mt-3">
                        <label class="form-label fw-bold pe-1">@lang('bt.output_type'):</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="output_type" value="preview"
                                   checked="checked">
                            <label class="form-check-label">@lang('bt.preview')</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="output_type" value="pdf">
                            <label class="form-check-label ">@lang('bt.pdf')</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="preview"
             style="height: 100%; background-color: #e6e6e6; padding: 25px; margin: 0; display: none;">
            <div class="col-lg-8 offset-lg-2" style="background-color: white;">
                <iframe src="about:blank" id="preview-results" style="border: 0;width: 100%;overflow:hidden;"
                        onload="resizeIframe(this, 500);"></iframe>
            </div>
        </div>
    </section>
@stop
