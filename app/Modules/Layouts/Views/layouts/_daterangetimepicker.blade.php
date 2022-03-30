
<script type="text/javascript">
    ready(function () {

        const startDate = moment().startOf('day');
        const endDate = moment().startOf('day');

        new DateRangePicker('date_time_range', {
                timePicker: true,
                timePickerIncrement: 15,
                autoApply: true,
                startDate: startDate,
                endDate: endDate,
                    @if (config('bt.use24HourTimeFormat'))
                    timePicker24Hour: true,
                    @endif
                    locale: {
                        @if (config('bt.use24HourTimeFormat'))
                        format: "{{ strtoupper(config('bt.datepickerFormat')) }} H:mm",
                        @else
                        format: "{{ strtoupper(config('bt.datepickerFormat')) }} h:mm A",
                        @endif
                        customRangeLabel: "@lang('bt.custom')",
                        daysOfWeek: [
                            "@lang('bt.day_short_sunday')",
                            "@lang('bt.day_short_monday')",
                            "@lang('bt.day_short_tuesday')",
                            "@lang('bt.day_short_wednesday')",
                            "@lang('bt.day_short_thursday')",
                            "@lang('bt.day_short_friday')",
                            "@lang('bt.day_short_saturday')"
                        ],
                        monthNames: [
                            "@lang('bt.month_january')",
                            "@lang('bt.month_february')",
                            "@lang('bt.month_march')",
                            "@lang('bt.month_april')",
                            "@lang('bt.month_may')",
                            "@lang('bt.month_june')",
                            "@lang('bt.month_july')",
                            "@lang('bt.month_august')",
                            "@lang('bt.month_september')",
                            "@lang('bt.month_october')",
                            "@lang('bt.month_november')",
                            "@lang('bt.month_december')"
                        ],
                        firstDay: 1
                    }
            },
            function (start, end) {
                //alert(start.format() + " - " + end.format());
            });

        addEvent(document, 'click', ".open-daterangetimepicker", (e) => {
            document.getElementById('date_time_range').click()
        })
    });
</script>
