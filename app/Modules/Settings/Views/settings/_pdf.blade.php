@section('javaScript')
    @parent
    <script type="text/javascript">
        ready(function () {
            updatePDFOptions();

            addEvent(document, 'change', "#pdfDriver", (e) => {
                updatePDFOptions();
            });

            function updatePDFOptions() {
                document.querySelectorAll('.wkhtmltopdf-option').forEach(function (e) {
                    e.style.display = 'none'
                })

                pdfDriver = document.getElementById('pdfDriver').value

                if (pdfDriver === 'wkhtmltopdf') {
                    document.querySelectorAll('.wkhtmltopdf-option').forEach(function (e) {
                        e.style.display = 'flex'
                    })
                }
            }
        });
    </script>
@stop

<div class="row">
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.paper_size'): </label>
            {{ html()->select('setting[paperSize]', $paperSizes, config('bt.paperSize'))->class('form-select') }}
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.paper_orientation'): </label>
            {{ html()->select('setting[paperOrientation]', $paperOrientations, config('bt.paperOrientation'))->class('form-select') }}
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('bt.pdf_disposition'): </label>
            {{ html()->select('setting[pdfDisposition]', $pdfDisposition, config('bt.pdfDisposition'))->class('form-select') }}
        </div>
    </div>
</div>
<div class="mb-3">
    <label class="form-label fw-bold">@lang('bt.pdf_driver'): </label>
    {{ html()->select('setting[pdfDriver]', $pdfDrivers, config('bt.pdfDriver'))->class('form-select')->attribute('id', 'pdfDriver') }}
</div>
<div class="mb-3 wkhtmltopdf-option">
    <label class="form-label fw-bold">@lang('bt.binary_path'): </label>
    {{ html()->text('setting[pdfBinaryPath]', config('bt.pdfBinaryPath'))->class('form-control') }}
</div>
