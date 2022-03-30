@include('layouts._daterangetimepicker')
<script type="text/javascript">
    ready(function () {
        const modal = bsModal('modal-show-timers')
        modal.show()
        window.addEventListener('apply.daterangepicker', function (ev) {
            document.getElementById('from_date_time').value = ev.detail.startDate.format('YYYY-MM-DD H:mm');
            document.getElementById('to_date_time').value = ev.detail.endDate.format('YYYY-MM-DD H:mm');
            axios.post('{{ route('timeTracking.timers.store') }}', {
                time_tracking_task_id: {{ $task->id }},
                start_at: document.getElementById('from_date_time').value,
                end_at: document.getElementById('to_date_time').value
            }).then(function (response) {
                refreshTaskList();
                refreshTimerList();
                refreshTotals();
            }).catch(function (error) {
                showErrors(error.response.data.errors);
            });
        });

        addEvent(document, 'click', ".btn-delete-timer", (e) => {
            Swal.fire({
                title: '@lang('bt.trash_record_warning')',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d68500',
                confirmButtonText: '@lang('bt.yes_sure')'
            }).then((result) => {
                if (result.value) {
                    axios.post('{{ route('timeTracking.timers.delete') }}', {
                        id: e.target.dataset.timerId
                    }).then(function () {
                        refreshTaskList();
                        refreshTimerList();
                        refreshTotals();
                    });

                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    //
                }
            });

        });

        function refreshTimerList() {
            axios.post('{{ route('timeTracking.timers.refreshList') }}', {
                time_tracking_task_id: {{ $task->id }}
            })
                .then(response => {
                    document.getElementById('task-timer-list').innerHTML = response.data
                })
        }
    })
</script>
<div class="modal fade" id="modal-show-timers">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('bt.timers')</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div id="modal-status-placeholder"></div>
                <div class="row">
                    <div class="col-md-6">
                        <label>@lang('bt.add_timer')</label>
                        <div class="input-group">
                            {!! Form::text('date_time_range', null, ['id' => 'date_time_range', 'class' => 'form-control', 'readonly' => 'readonly']) !!}
                            <span class="input-group-text open-daterangetimepicker">
                                <i class="fa fa-calendar"></i>
                            </span>
                        </div>
                        {!! Form::hidden('from_date_time', null, ['id' => 'from_date_time']) !!}
                        {!! Form::hidden('to_date_time', null, ['id' => 'to_date_time']) !!}
                    </div>
                </div>
                <div id="task-timer-list">
                    @include('time_tracking._timer_list')
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('bt.close')</button>
            </div>
        </div>
    </div>
</div>
