@include('time_tracking._task_list_refresh_js')
<script type="text/javascript">
    ready(function () {
        const modal = bsModal('modal-edit-task')
        modal.show()
        modaleL = document.getElementById('modal-edit-task')
        modaleL.addEventListener('shown.bs.modal', function () {
            document.getElementById('edit_task_name').focus();
        });

        addEvent(document, 'click', "#btn-submit-task", (e) => {
            axios.post('{{ route('timeTracking.tasks.update') }}', {
                id: {{ $task->id }},
                time_tracking_project_id: {{ $task->time_tracking_project_id }},
                name: document.getElementById('edit_task_name').value
            }).then(function (response) {
                refreshTaskList();
            }).catch(function (response) {
                showErrors(error.response.data.errors);
            });
        });
    })
</script>
<div class="modal fade" id="modal-edit-task">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('bt.edit_task')</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div id="modal-status-placeholder"></div>
                <div class="mb-3">
                    <label class="col-form-label">@lang('bt.task'):</label>
                    {!! Form::text('name', $task->name, ['id' => 'edit_task_name', 'class' => 'form-control']) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('bt.cancel')</button>
                <button type="button" class="btn btn-primary" id="btn-submit-task"
                        data-bs-dismiss="modal">@lang('bt.submit')</button>
            </div>
        </div>
    </div>
</div>
