{{--@once--}}
{{--    @push('styles')--}}
{{--        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">--}}
{{--    @endpush--}}

{{--    @push('head_scripts')--}}
{{--        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>--}}
{{--    @endpush--}}
{{--@endonce--}}

{{--@props(['options' => "{dateFormat:'Y-m-d', altFormat:'F j, Y', altInput:true, }"])--}}
{{--@props(['options' => "{dateFormat:'Y-m-d', altFormat:'{{config('bt.dateFormat')}}', altInput:true, position: 'auto center', }"])--}}
@props(['options' => [], 'value' => date("Y-m-d")])

@php
    $options = array_merge([
                    'dateFormat' => 'Y-m-d',
                    'altFormat' =>  config('bt.dateFormat'),
                    'altInput' => true,
                    'position' => 'auto center'
                    ], $options);
@endphp

<div class="input-group text-bg-light" wire:ignore>
    <input
            x-data
            x-init="flatpickr($refs.input, {{json_encode((object)$options)}} );"
            x-ref="input"
            type="text"
            value="{{$value}}"
            data-input
            class="text-bg-light"
            {{$attributes}}
{{--            {{ $attributes->merge(['class' => 'block w-full disabled:bg-gray-200 p-2 border border-gray-300 rounded-md focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 sm:text-sm sm:leading-5']) }}--}}
    />
    <span class="input-group-text"><i class="fas fa-calendar-alt"></i> </span>
</div>
