<h3 class="offset-2">@lang('bt.set_recurrence')</h3>
<br>
<div class="mb-3">
    {{ html()->modelForm($rrule)->open() }}
    <div class="mb-3 d-flex align-items-center">
        {{ html()->label(trans('bt.frequency_string'), 'frequency')->class('col-sm-2 text-end fw-bold pe-3') }}
        <div class="col-sm-6 ">
            {{ html()->text('frequency',null)->class('form-control')->placeholder(__('bt.frequency'))->isReadonly() }}
        </div>
    </div>
    <div class="mb-3 d-flex align-items-center">
        {{ html()->label(trans('bt.frequency_text'), 'freqtext')->class('col-sm-2 text-end fw-bold pe-3') }}
        <div class="col-sm-6">
            {{ html()->text('freqtext',null)->class('form-control')->placeholder(__('bt.frequency_text'))->isReadonly() }}
        </div>
    </div>
    <div class="mb-3 d-flex align-items-center">
        {{ html()->label(__('bt.frequency'), 'freq')->class('col-sm-2 text-end fw-bold pe-3')->attribute('title', __('bt.frequency')) }}
        <label class="btn btn-primary ">
            {{ html()->radio('freq', null, 'YEARLY') }}<span> @lang('bt.yearly')</span></label>
        <label class="btn btn-primary">
            {{ html()->radio('freq', null, 'MONTHLY') }}<span> @lang('bt.monthly')</span></label>
        <label class="btn btn-primary">
            {{ html()->radio('freq', null, 'WEEKLY') }}<span> @lang('bt.weekly')</span></label>
        <label class="btn btn-primary">
            {{ html()->radio('freq', null, 'DAILY') }}<span> @lang('bt.daily')</span></label>
        {{--{!! Form::radio('freq','HOURLY',false,['disabled' => 'true']) !!}{!! Form::label('HOURLY',null,['style'=>'margin-right: 10px']) !!}--}}
        {{--{!! Form::radio('freq','MINUTELY',false,['disabled' => 'true']) !!}{!! Form::label('MINUTELY',null,['style'=>'margin-right: 10px']) !!}--}}
        {{--{!! Form::radio('freq','SECONDLY',false,['disabled' => 'true']) !!}{!! Form::label('SECONDLY',null,['style'=>'margin-right: 10px']) !!}--}}
    </div>
</div>
<div class="mb-3 d-flex align-items-center">
    {{ html()->label(__('bt.start_datetime'), 'eventfrom')->class('col-sm-2 text-end fw-bold pe-3')->attribute('title', 'The recurrence start. Besides being the base for the recurrence, missing parameters in the final recurrence instances will also be extracted from this date. If not given, "new Date" will be used instead.') }}
    <div class="col-sm-2">
        <x-fp_datetime
                name="start_date"
                id="eventfrom"
                class="form-control"
                style="cursor: pointer"
                :value="$rrule['start_date']"
        ></x-fp_datetime>
    </div>
    {{ html()->label(__('bt.end_datetime'), 'eventto')->class('col-sm-1 col-form-label text-end fw-bold pe-3')->attribute('title', 'The recurrence end. Besides being the base for the recurrence, missing parameters in the final recurrence instances will also be extracted from this date. If not given, "new Date" will be used instead.') }}
    <div class="col-sm-2">
        <x-fp_datetime
                name="end_date"
                id="eventto"
                class="form-control"
                style="cursor: pointer"
                :value="$rrule['end_date']"
        ></x-fp_datetime>
    </div>
    {{ html()->label(__('bt.until_datetime'), 'eventuntil')->class('col-sm-1 col-form-label text-end fw-bold pe-3')->attribute('title', 'until - If given, this must be a "Date" instance, that will specify the limit of the recurrence. If a recurrence instance happens to be the same as the"Date" instance given in the "until" argument, this will be the last occurrence.')}}
    <div class="col-sm-2">
        <x-fp_datetime
                name="until"
                id="eventuntil"
                class="form-control"
                style="cursor: pointer"
                :value="$rrule['until']"
        ></x-fp_datetime>
    </div>
</div>
<div class="mb-3 d-flex align-items-center">
    {{ html()->label(__('bt.count'), 'count')->class('col-sm-2 text-end fw-bold pe-3')->attribute('title', 'How many occurrences will be generated.') }}
    <div class="col-sm-3">
        {{ html()->number('count',null, '1', '500', '1')->attribute('id', 'count')->class('form-control') }}
    </div>
    {{ html()->label(__('bt.interval'), 'interval')->class('col-sm-2 text-end fw-bold pe-3')->attribute('title', 'The interval between each freq iteration. For example, when using "RRule.YEARLY", an interval of "2" means once every two years, but with "RRule.HOURLY", it means once every two hours. The default interval is "1".') }}
    <div class="col-sm-3">
        {{ html()->number('interval',null, '0', '50', '1')->attribute('id', 'interval')->class('form-control') }}
    </div>
</div>
<div class="mb-3 d-flex align-items-center">
    {{ html()->label(__('bt.week_start'), 'wkst')->class('col-sm-2 text-end fw-bold pe-3')->attribute('title', 'The week start day. Must be one of the "RRule.MO", "RRule.TU", "RRule.WE" constants, or an integer, specifying the first day of the week. This will affect recurrences based on weekly periods. The default week start is "RRule.MO".') }}
    <div class="mb-3">
        <label class="btn btn-primary ">
            {{ html()->radio('wkst', null, 'MO') }}<span> @lang('bt.day_short_mo')</span></label>
        <label class="btn btn-warning">
            {{ html()->radio('wkst', null, 'TU') }}<span> @lang('bt.day_short_tu')</span></label>
        <label class="btn btn-warning">
            {{ html()->radio('wkst', null, 'WE') }}<span> @lang('bt.day_short_we')</span></label>
        <label class="btn btn-warning">
            {{ html()->radio('wkst', null, 'TH') }}<span> @lang('bt.day_short_th')</span></label>
        <label class="btn btn-warning">
            {{ html()->radio('wkst', null, 'FR') }}<span> @lang('bt.day_short_fr')</span></label>
        <label class="btn btn-warning">
            {{ html()->radio('wkst', null, 'SA') }}<span> @lang('bt.day_short_sa')</span></label>
        <label class="btn btn-warning">
            {{ html()->radio('wkst', null, 'SU') }}<span> @lang('bt.day_short_su')</span></label>
    </div>
</div>
<div class="mb-3 d-flex align-items-center">
    {{ html()->label(__('bt.week_days'), 'byday')->class('col-sm-2 text-end fw-bold pe-3')->attribute('title', 'If given, it must be either an integer ("0 == RRule.MO"), a sequence of integers, one of the weekday constants ("RRule.MO", "RRule.TU", etc), or a sequence of these constants. When given, these variables will define the weekdays where the recurrence will be applied. It is also possible to use an argument n for the weekday instances, which will mean the nth occurrence of this weekday in the period. For example, with "RRule.MONTHLY", or with "RRule.YEARLY" and "BYMONTH", using "RRule.FR.clone(+1)" in "byweekday" will specify the first friday of the month where the recurrence happens. Notice that the RFC documentation, this is specified as "BYDAY", but was renamed to avoid the ambiguity of that argument.') }}
    <div class="mb-3">
        @foreach($days as $key => $value)
            <label class="btn btn-primary">
                {{ html()->checkbox('byday[]', isset($rrule['byday'][$key]) ? 1 : null, $value)->class('byday form-check-input') }}
                <span> @lang('bt.day_short_' . strtolower($value))</span></label>
        @endforeach
    </div>
</div>
<div class="mb-3 d-flex align-items-center">
    {{ html()->label(__('bt.months_sp'), 'bymonth')->class('col-sm-2 text-end fw-bold pe-3')->attribute('title', 'If given, it must be either an integer, or a sequence of integers, meaning the months to apply the recurrence to.') }}
    <div class="mb-3">
        @foreach($months as $key => $value)
            <label class="btn btn-primary">
                {{ html()->checkbox('bymonth[]', isset($rrule['bymonth'][$key]) ? 1 : null, $value)->class('bymonth form-check-input') }}
                <span> @lang('bt.month_short_' . $value)</span></label>
        @endforeach
    </div>
</div>
<div class="mb-3 d-flex align-items-center">
    {{ html()->label(__('bt.position'), 'bysetpos')->class('col-sm-2 text-end fw-bold pe-3')->attribute('title', 'If given, it must be either an integer, or a sequence of integers, positive or negative. Each given integer will specify an occurrence number, corresponding to the nth occurrence of the rule inside the frequency period. For example, a "bysetpos" of "-1" if combined with a "RRule.MONTHLY" frequency, and a byweekday of ("RRule.MO", "RRule.TU", "RRule.WE", "RRule.TH", "FR"), will result in the last work day of every month.') }}
    <div class="col-sm-2">
        {{ html()->text('bysetpos',null)->attribute('id', 'bysetpos')->class('form-control') }}
    </div>
</div>
<div class="mb-3 d-flex align-items-center">
    {{ html()->label(__('bt.monthday'), 'bymonthday')->class('col-sm-2 text-end fw-bold pe-3')->attribute('title', 'If given, it must be either an integer, or a sequence of integers, meaning the month days to apply the recurrence to.') }}
    <div class="col-sm-2">
        {{ html()->text('bymonthday',null)->attribute('id', 'bymonthday')->class('form-control') }}
    </div>
    {{ html()->label(__('bt.yearday'), 'byyearday')->class('col-sm-1 text-end fw-bold pe-3')->attribute('title', 'If given, it must be either an integer, or a sequence of integers, meaning the year days to apply the recurrence to.') }}
    <div class="col-sm-2">
        {{ html()->text('byyearday',null)->attribute('id', 'byyearday')->class('form-control') }}
    </div>
    {{ html()->label(__('bt.weeknumber'), 'byweekno')->class('col-sm-1 text-end fw-bold pe-3')->attribute('title', 'If given, it must be either an integer, or a sequence of integers, meaning the week numbers to apply the recurrence to. Week numbers have the meaning described in ISO8601, that is, the first week of the year is that containing at least four days of the new year.') }}
    <div class="col-sm-2">
        {{ html()->text('byweekno',null)->attribute('id', 'byweekno')->class('form-control') }}
    </div>
</div>
{{--<div class="mb-3">
    {!! Form::label('Hour',null,['for'=>'byhour', 'class'=>'col-sm-2 text-end','title'=> 'byhour - If given, it must
                    be either an integer, or a sequence of integers, meaning the hours to apply the recurrence to.']) !!}
    <div class="col-sm-2">
        {!! Form::input('text','byhour',null, ['class'=>'form-control','disabled'=>'true']) !!}
    </div>
    {!! Form::label('Minute',null,['for'=>'byminute', 'class'=>'col-sm-1 col-form-label','title'=> 'byminute - If given,
                    it must be either an integer, or a sequence of integers, meaning the minutes to apply the recurrence to.']) !!}
    <div class="col-sm-2">
        {!! Form::input('text','byminute',null, ['class'=>'form-control','disabled'=>'true']) !!}
    </div>
    {!! Form::label('Second',null,['for'=>'bysecond', 'class'=>'col-sm-1 col-form-label','title'=> 'bysecond - If given,
                    it must be either an integer, or a sequence of integers, meaning the seconds to apply the recurrence to.']) !!}
    <div class="col-sm-2">
        {!! Form::input('text','bysecond',null, ['class'=>'form-control','disabled'=>'true']) !!}
    </div>
</div>--}}
<hr>
<div class="mb-3 d-flex align-items-center offset-3 mt-5">
    {{ html()->button(__('bt.show_proposed_recurrence'), 'button')->attribute('onclick', 'return showhuman()')->class('col-sm-4 btn btn-warning') }}
    {{--{!! Form::close() !!}--}}
    <script>
        function showhuman() {
            axios.post('{!! route("scheduler.gethuman") !!}', {
                'title': 'Show Proposed',
                'freq': document.querySelector('input[name="freq"]:checked').value,
                'start_date': document.querySelector("#eventfrom").value,
                'end_date': document.querySelector("#eventto").value,
                'until': document.querySelector("#eventuntil").value,
                'count': document.querySelector("#count").value,
                'interval': document.querySelector("#interval").value,
                'wkst': document.querySelector('input[name="wkst"]:checked').value,
                'byday': Array.from(document.querySelectorAll(".byday:checked")).map(function (e) {
                    return e.value.toString();
                }).join(","),
                'bymonth': Array.from(document.querySelectorAll(".bymonth:checked")).map(function (e) {
                    return e.value.toString();
                }).join(","),
                'bysetpos': document.querySelector("#bysetpos").value,
                'bymonthday': document.querySelector("#bymonthday").value,
                'byyearday': document.querySelector("#byyearday").value,
                'byweekno': document.querySelector("#byweekno").value,
            }).then(function (response) {
                Swal.fire({
                    title: 'Proposed Occurrence',
                    text: 'Frequency to text is  ' + response.data.result,
                    icon: 'info',
                    showConfirmButton: false,
                    timer: 5000
                });
            }).catch(function (error) {
                if (error.response) {
                    notify(error.response.data.message, 'error')
                } else if (error.request) {
                    console.log(error.request);
                } else {
                    // Something happened in setting up the request that triggered an Error
                    console.log('Error', error.message);
                }
                //console.log(error.config);
            });
        }
    </script>
</div>
