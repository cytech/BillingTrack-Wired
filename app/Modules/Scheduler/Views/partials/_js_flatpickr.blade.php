<script>
    var fpeventfromcfg = {
        altFormat: "{{config('bt.dateFormat') . (!config('bt.use24HourTimeFormat') ? ' h:i K' : ' H:i')}}",
        dateFormat: 'Y-m-d H:i:S',
        altInput: true,
        enableTime: true,
        minuteIncrement: {!! config('bt.schedulerTimestep') !!},
        time_24hr: "{{(!config('bt.use24HourTimeFormat') ? 'false' : 'true')}}",
        maxDate: "",
        position: "auto center",
        plugins: [
            new confirmDatePlugin({
                //confirmIcon: "<i class='fa fa-check'></i>",
                confirmIcon: "",
                //theme: "light", // or "dark"
                confirmText: 'Select',
                showAlways: false,
            }),
        ],
        onChange: function (dateStr, dateObj) {
            fpeventto.set("minDate", dateObj);
        }
    }

    var fpeventtocfg = {
        altFormat: "{{config('bt.dateFormat') . (!config('bt.use24HourTimeFormat') ? ' h:i K' : ' H:i')}}",
        dateFormat: 'Y-m-d H:i:S',
        altInput: true,
        enableTime: true,
        minuteIncrement: {!! config('bt.schedulerTimestep') !!},
        time_24hr: "{{(!config('bt.use24HourTimeFormat') ? 'false' : 'true')}}",
        minDate: "",
        position: "auto center",
        plugins: [
            new confirmDatePlugin({
                confirmIcon: "",
                confirmText: 'Select',
                showAlways: false,
            }),
        ],
        onChange: function (dateStr, dateObj) {
            fpeventfrom.set("maxDate", dateObj);
        }
    }

    var fpeventuntilcfg = {
        altFormat: "{{config('bt.dateFormat') . (!config('bt.use24HourTimeFormat') ? ' h:i K' : ' H:i')}}",
        dateFormat: 'Y-m-d H:i:S',
        //defaultDate: '+1970/02/01',//+1 month
        altInput: true,
        enableTime: true,
        minuteIncrement: {!! config('bt.schedulerTimestep') !!},
        time_24hr: "{{(!config('bt.use24HourTimeFormat') ? 'false' : 'true')}}",
        minDate: "",
        position: "auto center",
        plugins: [
            new confirmDatePlugin({
                confirmIcon: "",
                confirmText: 'Select',
                showAlways: false,
            }),
        ],
        onChange: function (dateStr, dateObj) {
            fpeventfrom.set("maxDate", dateObj);
        }
    }

    var fpeventremcfg = {
        altFormat: "{{config('bt.dateFormat') . (!config('bt.use24HourTimeFormat') ? ' h:i K' : ' H:i')}}",
        dateFormat: 'Y-m-d H:i:S',
        altInput: true,
        enableTime: true,
        minuteIncrement: {!! config('bt.schedulerTimestep') !!},
        time_24hr: "{{(!config('bt.use24HourTimeFormat') ? 'false' : 'true')}}",
        position: "auto center",
        plugins: [
            new confirmDatePlugin({
                confirmIcon: "",
                confirmText: 'Select',
                showAlways: false,
            }),
        ],
    }

    // var fpeventfrom = flatpickr("#eventfrom", fpeventfromcfg)
    // var fpeventto = flatpickr("#eventto", fpeventtocfg)

    function addHours(date, hours) {
        const newDate = new Date(date);
        newDate.setHours(newDate.getHours() + hours);
        return newDate;
    }

    function addMonths(date, months) {
        const newDate = new Date(date);
        newDate.setMonth(newDate.getMonth() + months);
        return newDate;
    }
</script>
