<?php

/**
 * This file is part of BillingTrack.
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Scheduler\Controllers;

use BT\Http\Controllers\Controller;
use BT\Modules\Employees\Models\Employee;
use BT\Modules\Scheduler\Models\Category;
use BT\Modules\Scheduler\Models\Schedule;
use BT\Modules\Scheduler\Models\ScheduleOccurrence;
use BT\Modules\Scheduler\Models\ScheduleResource;
use BT\Modules\Scheduler\Requests\EventRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Recurr;

class SchedulerEditController extends Controller
{
    //recurring event create or edit
    public function editRecurringEvent($id = null)
    {
        if ($id) { //if edit route called with id parameter
            $schedule = Schedule::find($id);
            $schedule->employee_id = $schedule->resource->resource_id ?? null;
            $schedule->employee_name = $schedule->resource->employee->full_name ?? $schedule->title;
            $rule = Recurr\Rule::createFromString($schedule->rrule);
            $textTransformer = new Recurr\Transformer\TextTransformer();

            $rrule = [
                "frequency"  => $rule->getString(),
                "freqtext"   => $textTransformer->transform($rule),
                "freq"       => $rule->getFreqAsText(),
                "start_date" => $rule->getStartDate()->format('Y-m-d H:i:s'),
                "end_date"   => $rule->getEndDate()->format('Y-m-d H:i:s'),
                "until"      => ($rule->getUntil()) ? $rule->getUntil()->format('Y-m-d H:i:s') : '',
                "count"      => $rule->getCount(),
                "interval"   => $rule->getInterval(),
                "wkst"       => $rule->getWeekStart(),
                "byday"      => $rule->getByDay(),
                "bysetpos"   => $rule->getBySetPosition(),
                "bymonthday" => $rule->getByMonthDay(),
                "byyearday"  => $rule->getByYearDay(),
                "byweekno"   => $rule->getByWeekNumber(),
                "bymonth"    => $rule->getByMonth(),
            ];

            $data = [
                'schedule'   => $schedule,
                'categories' => Category::pluck('name', 'id'),
                'url'        => 'schedule\edit_event',
                'title'      => 'update_recurring_event',
                'message'    => 'recurring_event_updated',
                'rrule'      => $rrule,
            ];

            return view('schedule.recurringEventEdit', $data)
                ->with('reminder_interval', ScheduleOccurrence::reminderinterval());

        } else {// no id - create new
            $schedule = new Schedule();
            $data = [
                'schedule'   => $schedule,
                'rrule'      => [
                    "freq"       => 'WEEKLY',
                    "start_date" => Carbon::now()->startOfDay()->addHours(8),
                    "end_date"   => Carbon::now()->startOfDay()->addHours(16),
                    "until"      => Carbon::now()->startOfDay()->addMonth()->addHours(16),
                    "wkst"       => 'MO',
                ],
                'url'        => 'schedule\edit_event',
                'title'      => 'create_recurring_event',
                'message'    => 'recurring_event_created',
                'categories' => Category::pluck('name', 'id')
            ];
            //defaults
            $schedule['category_id'] = 3;

            return view('schedule.recurringEventEdit', $data)
                ->with('reminder_interval', ScheduleOccurrence::reminderinterval());
        }
    }

    //recurring event store or update
    public function updateRecurringEvent(EventRequest $request)
    {
        //generate rrule
        $allfields = $request->all();

        //remap start and end to RRULE types
        $allfields['DTSTART'] = $allfields['start_date'];
        $allfields['DTEND'] = $allfields['end_date'];
        unset($allfields['start_date']);
        unset($allfields['end_date']);
        isset($allfields['byday']) ? $allfields['byday'] = implode(',', $allfields['byday']) : null;
        isset($allfields['bymonth']) ? $allfields['bymonth'] = implode(',', $allfields['bymonth']) : null;

        $allfields = array_change_key_case($allfields, CASE_UPPER);
        //clear all empty
        $allfields = array_filter($allfields);

        $timezone = config('bt.timezone');

        $rule = Recurr\Rule::createFromArray($allfields);
        $transformer = new Recurr\Transformer\ArrayTransformer();
        $textTransformer = new Recurr\Transformer\TextTransformer();
        $recurrences = $transformer->transform($rule);

        $event = ($request->id) ? Schedule::find($request->id) : new Schedule();

        $event->title = $request->employee_name;
        $event->location_str = $request->location_str;
        $event->description = $request->description;
        $event->isRecurring = 1;
        $event->rrule = $rule->getString();

        $event->category_id = $request->category_id;
        $event->user_id = Auth::user()->id;

        $event->save();

        $event->occurrences()->forceDelete();
        foreach ($recurrences as $index => $item) {
            $occurrence = new ScheduleOccurrence();
            $occurrence->schedule_id = $event->id;
            $occurrence->start_date = $item->getStart();
            $occurrence->end_date = $item->getEnd();
            $occurrence->reminder_qty = $request->reminder_qty;
            $occurrence->reminder_interval = $request->reminder_interval;
            $occurrence->reminder_date = ScheduleOccurrence::reminderDate($request->reminder_qty, $request->reminder_interval, $occurrence->start_date);

            $occurrence->save();
        }

        //delete existing resources for the event
        foreach ($event->resources as $resource) {
            $resource->forceDelete();
        }

        if ($request->employee_id) { //if employee
            $employee = Employee::where('id', $request->employee_id)->where('active', 1)->first();
            if ($employee && $employee->schedule == 1) { //employee exists and is scheduleable...
                foreach ($event->occurrences as $occurrence) {
                    $scheduleItem = ScheduleResource::firstOrNew(['occurrence_id' => $occurrence->id]);
                    $scheduleItem->occurrence_id = $occurrence->id;
                    $scheduleItem->resource_table = 'employees';
                    $scheduleItem->resource_id = $employee->id;
                    $scheduleItem->value = $employee->short_name;
                    $scheduleItem->qty = 1;
                    $scheduleItem->save();
                }
            }
        }
        $msg = $request->id ? trans('bt.record_successfully_updated') : trans('bt.record_successfully_created');
        return redirect()->route('scheduler.tablerecurringevent')->with('alertSuccess', $msg);
    }

    public function getHuman(Request $request)
    {
        //get human readable rule from dialog
        //generate rrule
        $allfields = $request->all();
        $allfields['DTSTART'] = $allfields['start_date'];
        $allfields['DTEND'] = $allfields['end_date'];
        unset($allfields['start_date']);
        unset($allfields['end_date']);
//        isset($allfields['byday']) ? $allfields['byday'] = implode(',',$allfields['byday']) : null;
//        isset($allfields['bymonth']) ? $allfields['bymonth'] = implode(',',$allfields['bymonth']) : null;
        $allfields = array_change_key_case($allfields, CASE_UPPER);
        //clear all empty
        $allfields = array_filter($allfields);

        $timezone = config('bt.timezone');

        $rule = Recurr\Rule::createFromArray($allfields);
        $textTransformer = new Recurr\Transformer\TextTransformer();
        $textTrans = $textTransformer->transform($rule);

        $response['type'] = 'success';
        $response['result'] = $textTrans;

        return Response::json($response);
    }
}
