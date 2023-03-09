@extends('setup.master')

@section('javaScript')
    <script type="text/javascript">
        function ready(fn) {
            if (document.readyState !== 'loading'){
                fn();
            } else {
                document.addEventListener('DOMContentLoaded', fn);
            }
        }
        // function to add event listener with callback
        function addEvent(parent, evt, selector, handler) {
            parent.addEventListener(evt, function (event) {
                if (event.target.matches(selector + ', ' + selector + ' *')) {
                    handler.apply(event.target.closest(selector), arguments);
                }
            }, false);
        }

        ready(function () {
            addEvent(document, 'click', "#btn-run-migration", (e) => {
                document.getElementById('btn-run-migration').style.display = 'none'
                document.getElementById('btn-running-migration').style.display = 'block'

                axios.post('{{ route('setup.postMigration') }}').then(function () {
                    document.getElementById('div-exception').style.display = 'none'
                    document.getElementById('btn-running-migration').style.display = 'none'
                    document.getElementById('btn-migration-complete').style.display = 'block'
                }).catch(function (error) {
                    if (error.response.status === 400) {
                        document.getElementById('div-exception').innerHTML = error.response.data.errors
                        document.getElementById('div-exception').style.display = 'block'
                    } else {
                        alert('@lang('bt.unknown_error')');
                        document.getElementById('div-exception').style.display = 'none'
                        document.getElementById('btn-running-migration').style.display = 'none'
                        document.getElementById('btn-migration-complete').style.display = 'block'
                    }
                });
            });
        });
    </script>
@stop

@section('content')
    <section class="app-content-header">
        <h1>@lang('bt.database_setup')</h1>
    </section>
    <section class="content">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class=" card card-light">
                    <div class="card-body">
                        <div class="alert alert-error" id="div-exception" style="display: none;"></div>
                        <p>@lang('bt.step_database_setup')</p>
                        <a class="btn btn-primary" id="btn-run-migration">@lang('bt.continue')</a>
                        <a class="btn btn-secondary" id="btn-running-migration" style="display: none;"
                           disabled="disabled">@lang('bt.installing_please_wait')</a>
                        <a href="{{ route('setup.account') }}" class="btn btn-success" id="btn-migration-complete"
                           style="display: none;">@lang('bt.step_database_complete')</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
