@props(['options' => [], 'value' => date("Y-m-d H:i:s")])
<div class="input-group" wire:ignore>
    <input
            x-data
            x-init="flatpickr($refs.input, {
                    altFormat: '{{config('bt.dateFormat') . (!config('bt.use24HourTimeFormat') ? ' h:i K' : ' H:i')}}',
                    dateFormat: 'Y-m-d H:i:S',
                    altInput: true,
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
                    plugins: [
                        new confirmDatePlugin({
                        confirmIcon: '',
                        confirmText: 'Select',
                        showAlways: false,
                        }),
                    ],
            })"
            x-ref="input"
            type="text"
            value="{{$value}}"
            data-input
            class="text-bg-light"
            {{$attributes}}
    />
    <span class="input-group-text"><i class="fas fa-calendar-alt"></i> </span>
</div>
