@props(['options' => [], 'value' => date("H:i")])
{{--@php--}}
{{--    $options = array_merge([--}}
{{--                    'altFormat'=> (!config('bt.use24HourTimeFormat') ? ' h:i K' : ' H:i'),--}}
{{--                    'dateFormat'=> 'H:i',--}}
{{--                    'altInput'=> true,--}}
{{--                    'noCalendar'=> true,--}}
{{--                    'enableTime'=> true,--}}
{{--                    'maxDate'=> "",--}}
{{--                    'minuteIncrement'=> config('bt.schedulerTimestep'),--}}
{{--                    'time_24hr'=> (!config('bt.use24HourTimeFormat') ? 'false' : 'true'),--}}
{{--                    'position'=> "auto center",--}}
{{--                    ], $options);--}}
{{--@endphp--}}
<div class="input-group" wire:ignore>
    <input
            x-data
{{--            x-init="flatpickr($refs.input, {{json_encode((object)$options)}} );"--}}
            x-init="flatpickr($refs.input, {
                    altFormat: '{{(!config('bt.use24HourTimeFormat') ? ' h:i K' : ' H:i')}}',
                    dateFormat: 'H:i',
                    altInput: true,
                    noCalendar: true,
                    enableTime: true,
                    minuteIncrement: '{{ config('bt.schedulerTimestep') }}',
                    time_24hr: '{{(!config('bt.use24HourTimeFormat') ? 'false' : 'true')}}',
                    position: 'auto center',
                    onOpen: function(selectedDates, dateStr, instance){
                        document.getElementById('laravel-livewire-modals').removeAttribute('tabindex');
                        },
                    onClose: function(selectedDates, dateStr, instance){
                        document.getElementById('laravel-livewire-modals').setAttribute('tabindex', -1);
                        },
                    })"
            x-ref="input"
            type="text"
            value="{{$value}}"
            data-input
            class="text-bg-light"
            {{$attributes}}
    />
    <span class="input-group-text p-1"><i class="far fa-clock"></i> </span>
</div>
