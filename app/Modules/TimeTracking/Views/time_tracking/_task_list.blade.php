@if ($tasks->count() == 0)
    <li>@lang('bt.task_notice')</li>
@else
    @foreach ($tasks as $task)
        <li id="task_id_{{ $task->id }}" data-id="task_id_{{ $task->id }}">
            <span class="handle">
                <i class="fas fa-arrows-alt-v"></i>
            </span>
            <input type="checkbox" class="checkbox-bulk-action" data-task-id="{{ $task->id }}">
            @if (!$task->activeTimer)
                <button class="btn btn-sm btn-green btn-start-timer" data-task-id="{{ $task->id }}"><i
                            class="fa fa-play"></i> <strong>@lang('bt.start_timer')<br>
                        {{ $task->formatted_hours }} @lang('bt.hours')</strong>
                </button>
            @else
                <button class="btn btn-sm btn-red btn-stop-timer" data-timer-id="{{ $task->activeTimer->id }}"
                        data-task-id="{{ $task->id }}"><i class="fa fa-stop"></i> <strong>@lang('bt.stop_timer')<br>
                        <span id="hours_{{ $task->id }}">00</span>:<span id="minutes_{{ $task->id }}">00</span>:<span
                                id="seconds_{{ $task->id }}">00</span></strong>
                </button>
            @endif
            <span class="text">{{ $task->name }}</span>
            <div class="tools" style="font-size: 1.25em;">
                <button class="btn btn-link btn-lg btn-show-timers p-0 m-0" data-task-id="{{ $task->id }}"
                   data-toggle="tooltip" title="@lang('bt.show_timers')"><i class="fa fa-clock"></i></button>
                <button class="btn btn-link btn-lg btn-bill-task p-0 m-0" data-task-id="{{ $task->id }}" data-toggle="tooltip"
                   title="@lang('bt.bill_task')"><i class="fa fa-dollar-sign"></i></button>
                <button class="btn btn-link btn-lg btn-edit-task p-0 m-0" data-task-id="{{ $task->id }}" data-toggle="tooltip"
                   title="@lang('bt.edit_task')"><i class="fa fa-edit"></i></button>
                <button class="btn btn-link btn-lg btn-delete-task p-0 m-0" data-task-id="{{ $task->id }}"
                   data-toggle="tooltip" title="@lang('bt.trash_task')"><i class="fa fa-trash-alt"></i></button>

            </div>
        </li>
    @endforeach
@endif
