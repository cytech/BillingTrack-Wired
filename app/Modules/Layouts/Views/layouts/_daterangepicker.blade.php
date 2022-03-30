<script src='{{ asset('plugins/moment/moment.min.js') }}'></script>
<script src='{{ asset('plugins/vanilla-datetimerange-picker/vanilla-datetimerange-picker.js') }}'></script>
<link href="{{ asset('plugins/vanilla-datetimerange-picker/vanilla-datetimerange-picker.css') }}" rel="stylesheet"
      type="text/css"/>
<script type="text/javascript">
    ready(function () {
        const sentStart = document.getElementById('from_date')
        const sentEnd = document.getElementById('to_date')
        const startDate = (sentStart.value === '' ? moment().startOf('month') : moment(sentStart.value));
        const endDate = (sentEnd.value === '' ? moment().endOf('month') : moment(sentEnd.value));
        if (sentStart.value === '') sentStart.value = startDate.format('YYYY-MM-DD');
        if (sentEnd.value === '') sentEnd.value = endDate.format('YYYY-MM-DD');
        window.addEventListener("load", function (event) {
            new DateRangePicker('date_range', { //});
                    autoApply: true,
                    startDate: startDate,
                    endDate: endDate,
                    locale: {
                        format: "{{ strtoupper(config('bt.datepickerFormat')) }}",
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
                    },
                    ranges: {
                        '@lang('bt.today')': [moment(), moment()],
                        '@lang('bt.yesterday')': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        '@lang('bt.tomorrow')': [moment().add(1, 'days'), moment().add(1, 'days')],
                        '@lang('bt.lastweek')': [moment().subtract(1, 'weeks').startOf('isoWeek'), moment().subtract(1, 'weeks').endOf('isoWeek')],
                        '@lang('bt.twoweeksago')': [moment().subtract(2, 'weeks').startOf('isoWeek'), moment().subtract(2, 'weeks').endOf('isoWeek')],
                        '@lang('bt.last_7_days')': [moment().subtract(6, 'days'), moment()],
                        '@lang('bt.last_30_days')': [moment().subtract(29, 'days'), moment()],
                        '@lang('bt.this_month')': [moment().startOf('month'), moment().endOf('month')],
                        '@lang('bt.last_month')': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                        '@lang('bt.this_year')': [moment().startOf('year'), moment().endOf('year')],
                        '@lang('bt.last_year')': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
                        '@lang('bt.this_quarter')': [moment().startOf('quarter'), moment().endOf('quarter')],
                        '@lang('bt.last_quarter')': [moment().subtract(1, 'quarter').startOf('quarter'), moment().subtract(1, 'quarter').endOf('quarter')],
                        '@lang('bt.first_quarter')': [moment().startOf('quarter').quarter(1), moment().endOf('quarter').quarter(1)],
                        '@lang('bt.second_quarter')': [moment().startOf('quarter').quarter(2), moment().endOf('quarter').quarter(2)],
                        '@lang('bt.third_quarter')': [moment().startOf('quarter').quarter(3), moment().endOf('quarter').quarter(3)],
                        '@lang('bt.fourth_quarter')': [moment().startOf('quarter').quarter(4), moment().endOf('quarter').quarter(4)]
                    }
                },
                function (start, end) {
                    //alert(start.format() + " - " + end.format());
                });

            window.addEventListener('apply.daterangepicker', function (ev) {
                document.getElementById('from_date').value = ev.detail.startDate.format('YYYY-MM-DD');
                document.getElementById('to_date').value = ev.detail.endDate.format('YYYY-MM-DD');

            });
        })
    });
</script>
