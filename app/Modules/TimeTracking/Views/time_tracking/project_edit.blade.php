@extends('layouts.master')

@section('javaScript')
    <script src='{{ asset('plugins/moment/moment.min.js') }}'></script>
    <script src='{{ asset('plugins/vanilla-datetimerange-picker/vanilla-datetimerange-picker.js') }}'></script>
    <link href="{{ asset('plugins/vanilla-datetimerange-picker/vanilla-datetimerange-picker.css') }}" rel="stylesheet" type="text/css"/>

    @include('time_tracking._task_list_refresh_js')
    @include('time_tracking._project_edit_totals_refresh')

    <script type="text/javascript">
        ready(function () {

            const timers = [];
            // sortablejs
            var el = document.getElementById("project-task-list")
            Sortable.create(el, {
                onEnd: function () {
                    axios.post('{{ route('timeTracking.tasks.updateDisplayOrder') }}', {task_ids: this.toArray()})
                },
                ghostClass: 'sort-highlight',
                handle: '.handle',
                animation: 150
            })

            addEvent(document, 'click', "#btn-add-task", (e) => {
                loadModal('{{ route('timeTracking.tasks.create') }}', {
                    project_id: {{ $project->id }}
                })
            });

            addEvent(document, 'click', "#btn-bulk-bill-tasks", (e) => {
                const ids = [];
                document.querySelectorAll('.checkbox-bulk-action:checked').forEach(function (e) {
                    ids.push(parseInt(e.dataset.taskId))
                })
                if (ids.length > 0) {
                    submitTaskBill(ids);
                }
            });

            addEvent(document, 'click', "#btn-bulk-delete-tasks", (e) => {
                const ids = [];
                document.querySelectorAll('.checkbox-bulk-action:checked').forEach(function (e) {
                    ids.push(e.dataset.taskId)
                })
                if (ids.length > 0) {
                    Swal.fire({
                        title: '@lang('bt.confirm_trash_task')',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d68500',
                        confirmButtonText: '@lang('bt.yes_sure')'
                    }).then((result) => {
                        if (result.value) {
                            submitTaskDelete(ids);
                        } else if (result.dismiss === Swal.DismissReason.cancel) {

                        }
                    });
                }
            });

            addEvent(document, 'click', "#btn-bulk-select-all", (e) => {
                document.querySelectorAll('.checkbox-bulk-action').forEach(function (e) {
                    e.checked = true
                })
            });

            addEvent(document, 'click', "#btn-bulk-deselect-all", (e) => {
                document.querySelectorAll('.checkbox-bulk-action').forEach(function (e) {
                    e.checked = false
                })
            });
            // button selectors
            document.querySelector('.todo-list').addEventListener('click', ({target}) => {
                if (target.matches('.btn-start-timer')) {
                    taskId = target.dataset.taskId
                    axios.post('{{ route('timeTracking.timers.start') }}', {
                        task_id: taskId
                    }).then(function () {
                        refreshTaskList();
                        startTimer(taskId);
                    });
                } else if (target.matches('.btn-stop-timer')) {
                    clearInterval(timers[target.dataset.taskId]);
                    axios.post('{{ route('timeTracking.timers.stop') }}', {
                        timer_id: target.dataset.timerId
                    }).then(function () {
                        refreshTaskList();
                        refreshTotals();
                    });
                } else if (target.matches('.btn-show-timers')) {
                    loadModal('{{ route('timeTracking.timers.show') }}', {
                        time_tracking_task_id: target.dataset.taskId
                    })
                } else if (target.matches('.btn-bill-task')) {
                    submitTaskBill([parseInt(target.dataset.taskId)]);
                } else if (target.matches('.btn-edit-task')) {
                    loadModal('{{ route('timeTracking.tasks.edit') }}', {
                        id: target.dataset.taskId
                    })
                } else if (target.matches('.btn-delete-task')) {
                    Swal.fire({
                        title: '@lang('bt.confirm_trash_task')',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d68500',
                        confirmButtonText: '@lang('bt.yes_sure')'
                    }).then((result) => {
                        if (result.value) {
                            submitTaskDelete([target.dataset.taskId]);
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            //
                        }
                    });
                }
            });
            // end button selectors

            addEvent(document, 'click', "#btn-save-settings", (e) => {
                let client_id, client_name
                //client-select returns client_id and client_name when selected. ONLY client_name when new client entered
                // which get passed to update controller -> firstOrCreateByName
                if (!document.querySelector('input[name="client_id"]')) {
                    client_id = 0
                    client_name = document.querySelector('input[name="client_name"]').value
                    swalSaving('@lang('bt.creating_new_client')');
                } else {
                    client_name = document.querySelector('input[name="client_name"]').value
                    client_id = document.querySelector('input[name="client_id"]').value
                }
                axios.post('{{ route('timeTracking.projects.update', [$project->id]) }}', {
                    name: document.getElementById('project_name').value,
                    company_profile_id: document.getElementById('company_profile_id').value,
                    client_name: client_name,
                    client_id: client_id,
                    hourly_rate: document.getElementById('hourly_rate').value,
                    status_id: document.getElementById('status_id').value,
                    due_at: document.getElementById('due_at').value
                }).then(function () {
                    notify('@lang('bt.settings_successfully_saved')', 'success');
                    window.location.reload()
                }).catch(function (error) {
                    if (error.response.status === 422) {
                        let msg = ''
                        for (let [id, message] of Object.entries(error.response.data.errors)) {
                            msg += message + '<br>';
                        }
                        notify(msg, 'error');
                    } else {
                        notify('@lang('bt.unknown_error')', 'error');
                    }
                });
            });

            function submitTaskBill(ids) {
                loadModal('{{ route('timeTracking.bill.create') }}', {
                    projectId: {{ $project->id }},
                    taskIds: JSON.stringify(ids)
                })
            }

            function submitTaskDelete(ids) {
                axios.post('{{ route('timeTracking.tasks.delete') }}', {
                    ids: ids
                }).then(function () {
                    refreshTaskList();
                    refreshTotals();
                });
            }

            function startTimer(taskId) {
                axios.post('{{ route('timeTracking.timers.seconds') }}', {
                    task_id: taskId
                }).then(function (sec) {
                    setTimerInterval(taskId, sec.data);
                });
            }

            function pad(val) {
                return val > 9 ? val : "0" + val;
            }

            function setTimerInterval(taskId, sec) {
                timerInterval = setInterval(function () {
                    document.getElementById('seconds_' + taskId).innerHTML = pad(++sec % 60)
                    document.getElementById('minutes_' + taskId).innerHTML = pad(parseInt(sec / 60 % 60, 10))
                    document.getElementById('hours_' + taskId).innerHTML = pad(parseInt(sec / 60 / 60, 10))
                }, 1000);

                timers[taskId] = timerInterval;
            }

            @foreach ($tasks as $task)
            @if ($task->activeTimer)
            startTimer({{ $task->id }});
            @endif
            @endforeach
        });
    </script>
@stop

@section('content')
    <section class="app-content-header">
        <h3 class="float-start px-3">@lang('bt.time_tracking')
            <small>{{ $project->name }}</small>
        </h3>
        <div class="float-end">
            <a href="#" class="btn btn-secondary"
               onclick="swalConfirm('@lang('bt.confirm_trash_project')', '', '{{ route('timeTracking.projects.delete', [$project->id]) }}');"><i
                        class="fa fa-trash-alt"></i> @lang('bt.trash_project')</a>
            <a href="{{ route('timeTracking.projects.index') }}" class="btn btn-secondary"><i
                        class="fa fa-backward"></i> @lang('bt.back')</a>
            <button class="btn btn-primary" id="btn-save-settings"><i class="fa fa-save"></i> @lang('bt.save')</button>
        </div>
        <div class="clearfix"></div>
    </section>
    <section class="container-fluid">
        <div class="row">
            <div class="col-lg-10">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fa fa-list"></i> @lang('bt.tasks')</h3>
                        <div class="card-tools float-end">
                            <button class="btn btn-sm btn-primary" id="btn-add-task">
                                <i class="fa fa-plus"></i> @lang('bt.add_task')
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="btn-group">
                                <button type="button" class="btn btn-secondary" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                    @lang('bt.bulk_actions')
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="javascript:void(0)" id="btn-bulk-bill-tasks"><i
                                                class="fa fa-dollar-sign"></i> @lang('bt.bill_tasks')</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="javascript:void(0)" id="btn-bulk-delete-tasks"><i
                                                class="fa fa-trash"></i> @lang('bt.trash_tasks')</a>
                                </div>
                            </div>
                        </div>
                        <span class="small"><a href="javascript:void(0)" id="btn-bulk-select-all">Select All</a> | <a
                                    href="javascript:void(0)" id="btn-bulk-deselect-all">Deselect All</a></span>
                        <ul class="todo-list" id="project-task-list">
                            @include('time_tracking._task_list')
                        </ul>
                    </div>
                </div>
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fa fa-list"></i> @lang('bt.billed_tasks')</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>@lang('bt.task')</th>
                                <th class="text-end">@lang('bt.hours')</th>
                                <th>@lang('bt.invoice')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($tasksBilled as $task)
                                <tr>
                                    <td>{{ $task->name }}</td>
                                    <td class="text-end">{{ $task->formatted_hours }}</td>
                                    @if(empty($task->invoice->number))
                                        <td style="color:red">Invoice #{{$task->invoice_id}} Trashed</td>
                                    @else
                                        <td>
                                            <a href="{{ route('invoices.edit', [$task->invoice_id]) }}">{{ $task->invoice->number }}</a>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-2">
                <div id="div-totals">
                    @include('time_tracking._project_edit_totals')
                </div>
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">@lang('bt.options')</h3>
                    </div>
                    <div class="card-body" id="create-project">
                        <div class="mb-3">
                            <label>@lang('bt.project_name'):</label>
                            {!! Form::text('project_name', $project->name, ['id' => 'project_name', 'class' => 'form-control form-control-sm']) !!}
                        </div>
                        <div class="mb-3">
                            <label>@lang('bt.company_profile'):</label>
                            {!! Form::select('company_profile_id', $companyProfiles, $project->company_profile_id, ['id' => 'company_profile_id', 'class' => 'form-select form-select-sm']) !!}
                        </div>
                        <div class="mb-3">
                            <label>* @lang('bt.client'):</label>
                            <livewire:client-search
                                    {{-- module base name, adds hidden fields with _id and _name --}}
                                    name="client"
                                    value="{!! $project->client_id !!}"
                                    description="{!! $project->client->name !!}"
                                    placeholder="{{ __('bt.select_or_create_client') }}"
                                    :searchable="true"
                                    noResultsMessage="{{__('bt.client_not_found_create')}}"
                                    :readonly="$readonly ?? null"
                            />
                        </div>
                        <div class="mb-3">
                            <label>* @lang('bt.due_date'):</label>
                            <x-fp_common
                                    name="due_at"
                                    id="due_at"
                                    class="form-control form-control-sm"
                                    value="{{$project->due_at}}"></x-fp_common>
                        </div>
                        <div class="mb-3">
                            <label>@lang('bt.hourly_rate'):</label>
                            {!! Form::text('hourly_rate', $project->hourly_rate, ['id' => 'hourly_rate', 'class' => 'form-control form-control-sm']) !!}
                        </div>
                        <div class="mb-3">
                            <label>@lang('bt.status'):</label>
                            {!! Form::select('status_id', $statuses, $project->status_id, ['id' => 'status_id', 'class' => 'form-select form-select-sm']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
