@extends('layouts.master')

@section('javaScript')
    <script type="text/javascript">
        ready(function () {
            addEvent(document, 'click', "#btn-run-report", (e) => {
                const company_profile_id = document.getElementById('company_profile_id').value
                const year = document.getElementById('year').value

                axios.post("{{ route('reports.revenueByClient.validate') }}", {
                    company_profile_id: company_profile_id,
                    year: year
                }).then(function () {
                    document.getElementById('form-validation-placeholder').innerHTML = ''
                    output_type = document.querySelector('input[name=output_type]:checked').value
                    query_string = "?company_profile_id=" + company_profile_id + "&year=" + year;
                    if (output_type === 'preview') {
                        document.getElementById('preview').style.display = 'block'
                        document.getElementById('preview-results').setAttribute('src', "{{ route('reports.revenueByClient.html') }}" + query_string)
                    } else if (output_type === 'pdf') {
                        window.location.href = "{{ route('reports.revenueByClient.pdf') }}" + query_string;
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
                <div class="fs-3 float-start">@lang('bt.revenue_by_client')</div>
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
                        <label class="form-label fw-bold">@lang('bt.year'):</label>
                        {!! Form::select('year', $years, date('Y'), ['id' => 'year', 'class' => 'form-select']) !!}
                    </div>
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
            <div class="col-lg-10 offset-1" style="background-color: white;">
                <iframe src="about:blank" id="preview-results" style="border: 0;width: 100%;overflow:hidden;"
                        onload="resizeIframe(this, 500);"></iframe>
            </div>
        </div>
    </section>
@stop
