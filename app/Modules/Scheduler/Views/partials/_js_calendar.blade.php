@section('javaScript')

    {!! Html::style('plugins/bootstrap-icons/font/bootstrap-icons.css') !!}

    <style>
        .fc-day-today {
            background: {{config('bt.schedulerFcTodaybgColor')}}    !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            /* init first - init first */
            var calendarEl = document.getElementById('calendar');

            let calendar = new Calendar(calendarEl, {
                plugins: [
                    dayGridPlugin,
                    interactionPlugin,
                    dayGridPlugin,
                    timeGridPlugin,
                    listPlugin,
                    bootstrap5Plugin
                ],
                initialView: 'dayGridMonth',
                themeSystem: '{!! config('bt.schedulerFcThemeSystem') !!}', //'standard' 'bootstrap5'
                headerToolbar: {
                    start: 'prev,next today',
                    center: 'title',
                    end: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth,listWeek,listDay'
                },
                buttonText: {
                    today: '@lang('bt.today')',
                    month: '@lang('bt.month')',
                    week: '@lang('bt.week')',
                    day: '@lang('bt.day')',
                    listMonth: '@lang('bt.month_list')',
                    listWeek: '@lang('bt.week_list')',
                    listDay: '@lang('bt.day_list')'
                },
                aspectRatio: '{!! config('bt.schedulerFcAspectRatio') !!}',//1.35 default
                eventOrder: "-category,start",
                eventDisplay: 'block',
                initialDate: "{!! date('Y-m-d') !!}",
                @if($status == 'last')
                initialDate: "{!! date('Y-m-d', strtotime("first day of previous month")) !!}",
                @elseif($status == 'next')
                initialDate: "{!! date('Y-m-d', strtotime("first day of next month")) !!}",
                @else
                initialDate: "{!! date('Y-m-d') !!}",
                @endif
                selectable: false,
                selectMirror: false,

                @if(config('bt.schedulerCreateWorkorder'))
                datesSet: function (info) {
                    // Add the "button" to the day headers
                    const headers = document.querySelectorAll('.fc-daygrid-day-top')
                    const prepend = "<div id='cwo'><button type='button' id='createWorkorder' class='btn btn-link btn-sm createwobutton' style='position: absolute; left: 0' title='@lang('bt.create_workorder')'><i class='createwobutton far fa-file-alt' ></i></button> </div>"
                    headers.forEach( (e) => {
                        e.style.position = 'relative'
                        e.insertAdjacentHTML('beforeend',prepend)
                    })
                },
                @endif

                dateClick: function (info) {
                    // If Workorder Button Icon Selected
                    if (info.jsEvent.target.classList.contains('createwobutton')) {
                        //                                                                       date, returnurl
                        window.livewire.emit('showModal', 'modals.create-seeded-workorder-modal', info.date, 'fullcalendar')
                    } else {
                        window.livewire.emit('showModal', 'modals.create-event-modal', null, true, info.date)
                    }
                },

                eventClick: function (info) {
                    info.jsEvent.preventDefault();
                    // added link to core events
                    if (info.event.url) {
                        window.open(info.event.url, '_parent');
                        return false;
                    }
                    if (info.event.extendedProps.isrecurring === '1') {
                        window.open('{{ route('scheduler.editrecurringevent') }}' + '/' + info.event.id, '_parent');
                        return false;
                    }
                    //                                                              model, fromcalendar
                    window.livewire.emit('showModal', 'modals.create-event-modal', info.event, true)
                },

                // added tooltip mouseover
                eventDidMount: function (info) {
                    let rstr = "";
                    let tooltippy = "";
                    if (info.event.extendedProps.type === 'Workorder') {
                        let wrstr = "@lang('bt.employees'): ";
                        if (info.event.extendedProps.willcall === '1') {
                            wrstr = "<span style='color:magenta'>@lang('bt.employees'): </span>";
                        }
                        let erstr = "Resources: ";
                        if (info.event.extendedProps.hasOwnProperty("resource")) {
                            info.event.extendedProps.resource.forEach( function (value) {
                                if (value.resource_table === 'employees' && value.resource_value) { //employees and not empty
                                    wrstr += " " + value.resource_value;
                                }
                                if (value.resource_table === 'products') { // Resources
                                    erstr += " " + value.resource_value;
                                }
                            });
                        }
                        if ((wrstr === "@lang('bt.employees'): ") || (wrstr === "<span style='color:magenta'>@lang('bt.employees'): </span>")) {
                            wrstr = "";
                        }
                        if (erstr === "Resources: ") {
                            erstr = "";
                        }
                        rstr = wrstr + "<br>" + erstr;
                    }
                    if (info.event.extendedProps.type === 'Workorder' || info.event.extendedProps.type === undefined) {

                        tooltippy = flatpickr.formatDate(info.event.start, '{{ !config('bt.use24HourTimeFormat') ? 'M d h:i K' : 'M d H:i' }}')
                            + ' to '
                            + flatpickr.formatDate(info.event.end, '{{ !config('bt.use24HourTimeFormat') ? 'M d h:i K' : 'M d H:i' }}')
                            + '<br>'
                            + (info.event.extendedProps.location_str ?? '')
                            + '<br>'
                            + info.event.extendedProps.description
                            + '<br>'
                            + rstr;
                        //themes: light, light-border, material, translucent
                        var tooltip = new Tippy(info.el, {
                            allowHTML: true,
                            content: tooltippy,
                            placement: 'auto',
                            trigger: 'mouseenter focus',
                            appendTo: () => document.body,
                            theme: 'light-border'
                        });
                    }
                },

                dayMaxEventRows: parseInt({!! config('bt.schedulerEventLimit') !!}) ? parseInt({!! config('bt.schedulerEventLimit') !!}) : false, // allows "more" link when too many events

                events: [
                        @foreach($events as $event)
                    {
                        //schedule
                        id: "{!! $event->id !!}",
                        title: "{!! $event->title !!}",
                        location_str: "{!! addslashes($event->location_str) !!}",
                        description: "{!! addslashes($event->description) !!}",
                        isrecurring: "{!! $event->isRecurring !!}",
                        category: "{!! $event->category_id !!}",
                        @isset($event->category_id)
                        color: "{!! $catbglist[$event->category_id] !!}",
                        textColor: "{!! $cattxlist[$event->category_id] !!}",
                        @endisset
                        url: "{!! $event->url !!}",
                        willcall: "{!! $event->will_call !!}",
                        //occurrences
                        oid: "{!! $event->oid !!}",
                        start: '{{$event->start_date}}',
                        end: '{{$event->end_date}}',
                        reminder_qty: '{{$event->reminder_qty}}',
                        reminder_interval: '{{$event->reminder_interval}}',
                        //resources
                        @if(!$event->resources->isEmpty())
                        resource: [
                                @foreach($event->resources as $resource)
                            {
                                resource_table: "{!! $resource->resource_table !!}",
                                resource_value: "{!! $resource->value !!}"
                            },
                            @endforeach
                        ],
                        @endif
                    },
                    @endforeach
                    // coreevents
                        @if($coreevents)
                        @foreach($coreevents as $coreevent){
                        id: "{!! $coreevent->id !!}",
                        type: "{!! strtok($coreevent->id, ':') !!}",
                        url: "{!! $coreevent->url !!}",
                        title: "{!! $coreevent->title !!}",
                        @isset($coreevent->description)
                        description: "{!! $coreevent->description !!}",
                        @endisset
                                @isset($coreevent->will_call)
                        willcall: "{!! $coreevent->will_call !!}",
                        @endisset
                                @isset($coreevent->category_id)
                        color: "{!! $catbglist[$coreevent->category_id] !!}",
                        textColor: "{!! $cattxlist[$coreevent->category_id] !!}",
                        @endisset
                        start: "{!! $coreevent->start !!}",
                        @isset($coreevent->end)
                        end: "{!! $coreevent->end !!}",
                        @endisset
                                @isset($coreevent->resources)
                        resource: [
                                @foreach($coreevent->resources as $resource)
                            {
                                resource_table: "{!! $resource->resource_table !!}",
                                resource_value: "{!! addslashes($resource->name) !!}"
                            },
                            @endforeach
                        ],
                        @endisset
                    },
                    @endforeach
                    @endif
                ],
            });

            calendar.render();

            @if(Session::has('success'))
            notify('{!! Session::get('success') !!}', 'success');
            @endif

        });
    </script>

    <style>
        #calendar {
            font-family: "Lucida Grande", Helvetica, Arial, Verdana, sans-serif;
            font-size: 14px;
            margin: 0 auto 0 0;
        }
    </style>

@stop
