@include('time_tracking._task_list_refresh_js')
<script type="text/javascript">
    ready(function () {
        const modal = bsModal('modal-add-task')
        modal.show()
        modaleL = document.getElementById('modal-add-task')
        modaleL.addEventListener('shown.bs.modal', function () {
            document.getElementById('add_task_name').focus();
        });

        document.querySelectorAll('.btn-submit-task').forEach(function (e) {
            e.addEventListener('click', () => {
                document.getElementById('modal-status-placeholder').innerHTML = ''
                axios.post('{{ route('timeTracking.tasks.store') }}', {
                    time_tracking_project_id: {{ $project->id }},
                    name: document.getElementById('add_task_name').value
                }).then(function (response) {
                    document.getElementById('add_task_name').focus()
                    refreshTaskList();
                }).catch(function (error) {
                    showErrors(error.response.data.errors);
                });
            });
        });
    })
</script>
<div class="modal fade" id="modal-add-task">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('bt.add_task')</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div id="modal-status-placeholder"></div>
                <div class="mb-3">
                    <label class="col-form-label">@lang('bt.task'):</label>
                    {!! Form::text('name', null, ['id' => 'add_task_name', 'class' => 'form-control']) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('bt.cancel')</button>
                <button type="button" class="btn btn-primary btn-submit-task"
                        id="btn-submit-task-add-another">@lang('bt.submit_and_add_another_task')</button>
                <button type="button" class="btn btn-primary btn-submit-task"
                        data-bs-dismiss="modal">@lang('bt.submit_and_close')</button>
            </div>
        </div>
    </div>
</div>
