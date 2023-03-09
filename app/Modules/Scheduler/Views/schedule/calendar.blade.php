{{-- allow reload on back button --}}
{!! header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0") !!}
{!! header("Cache-Control: post-check=0, pre-check=0", false) !!}
{!! header("Pragma: no-cache")!!}
@extends('layouts.master')
@include('partials._js_calendar')

@section('content')
    @include('layouts._alerts')

    <section class="app-content-header">
        <div class="card">
            <div class="col-lg-12">
                <div class="card card-light">
{{--                    <div class="card-header">--}}
{{--                        <h6 class="card-title"><i class="fa fa-fw fa-th fa-fw"></i><a--}}
{{--                                    href="{{ route('scheduler.index') }}">@lang('bt.schedule')</a> @lang('bt.calendar')--}}
{{--                        </h6>--}}
{{--                    </div>--}}
                    <div class="card-body">
                        <div id="calendar">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop



