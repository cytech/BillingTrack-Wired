@extends('layouts.master')

@section('javaScript')
    @include('layouts._alerts')
@endsection

@section('content')

    <div id="div-workorder-edit">

        @include('workorders._edit')

    </div>

@endsection
