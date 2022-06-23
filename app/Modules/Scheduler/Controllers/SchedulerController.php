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
use BT\Modules\Purchaseorders\Models\Purchaseorder;
use BT\Modules\Scheduler\Requests\ReplaceRequest;
use BT\Modules\Employees\Models\Employee;
use BT\Modules\Products\Models\Product;
use BT\Modules\Scheduler\Models\Schedule;
use BT\Modules\Scheduler\Models\ScheduleOccurrence;
use BT\Modules\Scheduler\Models\ScheduleResource;
use BT\Modules\Scheduler\Models\Category;
use BT\Modules\Settings\Models\Setting;
use BT\Modules\Workorders\Models\WorkorderItem;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use BT\Modules\CompanyProfiles\Models\CompanyProfile;
use BT\Modules\Scheduler\Requests\EventRequest;

//for coreevnts
use BT\Modules\Scheduler\Support\CalendarEventPresenter;
use BT\Modules\Quotes\Models\Quote;
use BT\Modules\Workorders\Models\Workorder;
use BT\Modules\Invoices\Models\Invoice;
use BT\Modules\Payments\Models\Payment;
use BT\Modules\Expenses\Models\Expense;
use BT\Modules\TimeTracking\Models\TimeTrackingProject;
use BT\Modules\TimeTracking\Models\TimeTrackingTask;

class SchedulerController extends Controller
{
    public function index()
    {
        $today = new Carbon();

        $thismonthstart = $today->copy()->modify('0:00 first day of this month');
        $thismonthend = $today->copy()->modify('23:59:59 last day of this month');
        $lastmonthstart = $today->copy()->modify('0:00 first day of last month');
        $lastmonthend = $today->copy()->modify('23:59:59 last day of last month');
        $nextmonthstart = $today->copy()->modify('0:00 first day of next month');
        $nextmonthend = $today->copy()->modify('23:59:59 last day of next month');

// alternate eloquent way...
//		$data['monthEvent'] = Schedule::whereHas('occurrences',function($q) use($today){
//			$q->where( 'start_date', '>=', $today->copy()->modify( '0:00 first day of this month' ) )
//			  ->where( 'schedule_occurrences.start_date', '<=', $today->copy()->modify( '23:59:59 last day of this month' ) );
//			})->count();

        $data['monthEvent'] = Schedule::withOccurrences()->whereBetween('schedule_occurrences.start_date', [$thismonthstart, $thismonthend])->count();
        $data['lastMonthEvent'] = Schedule::withOccurrences()->whereBetween('schedule_occurrences.start_date', [$lastmonthstart, $lastmonthend])->count();
        $data['nextMonthEvent'] = Schedule::withOccurrences()->whereBetween('schedule_occurrences.start_date', [$nextmonthstart, $nextmonthend])->count();
        $data['fullMonthEvent'] = Schedule::withOccurrences()->select(DB::raw("count('id') as total, DATE_FORMAT(schedule_occurrences.start_date, '%Y%m%d') as start_date"))
            ->where('schedule_occurrences.start_date', '>=', date('Y-m-01'))
            ->where('schedule_occurrences.start_date', '<=', date('Y-m-t'))
            ->groupBy('start_date')
            ->get();

        $data['fullYearMonthEvent'] = Schedule::withOccurrences()
            ->select(DB::raw("count('id') as total, DATE_FORMAT(schedule_occurrences.start_date, '%Y%m%d') as start_date"))
            ->where('start_date', '>=', date('Y-01-01'))
            ->where('start_date', '<=', date('Y-12-31'))
            ->groupBy(DB::raw("DATE_FORMAT(start_date, '%Y%m')"))
            ->get();

        $data['reminders_occ'] = ScheduleOccurrence::whereHas('schedule')
            ->whereRaw('? between reminder_date and end_date', [$today->copy()->modify('0:00')])
            ->orderBy('reminder_date', 'asc')
            ->get();

        $data['thisquotes'] = Quote::approved()->whereBetween('quote_date', [$thismonthstart, $thismonthend])->count();
        $data['lastquotes'] = Quote::approved()->whereBetween('quote_date', [$lastmonthstart, $lastmonthend])->count();
        $data['nextquotes'] = Quote::approved()->whereBetween('quote_date', [$nextmonthstart, $nextmonthend])->count();

        $data['thisworkorders'] = Workorder::approved()->whereBetween('job_date', [$thismonthstart, $thismonthend])->count();
        $data['lastworkorders'] = Workorder::approved()->whereBetween('job_date', [$lastmonthstart, $lastmonthend])->count();
        $data['nextworkorders'] = Workorder::approved()->whereBetween('job_date', [$nextmonthstart, $nextmonthend])->count();

        $data['thisinvoices'] = Invoice::sent()->whereBetween('invoice_date', [$thismonthstart, $thismonthend])->count();
        $data['lastinvoices'] = Invoice::sent()->whereBetween('invoice_date', [$lastmonthstart, $lastmonthend])->count();
        $data['nextinvoices'] = Invoice::sent()->whereBetween('invoice_date', [$nextmonthstart, $nextmonthend])->count();

        $data['thispayments'] = Payment::whereBetween('paid_at', [$thismonthstart, $thismonthend])->count();
        $data['lastpayments'] = Payment::whereBetween('paid_at', [$lastmonthstart, $lastmonthend])->count();
        $data['nextpayments'] = Payment::whereBetween('paid_at', [$nextmonthstart, $nextmonthend])->count();

        return view('schedule.dashboard', $data);
    }

    public function calendar()
    {
        //only fetch back configured amount of days
        $data['status'] = (request('status')) ?: 'now';
        $data['events'] = Schedule::withOccurrences()->with('resources')->whereDate('start_date', '>=',
            Carbon::now()->subDays(config('bt.schedulerPastdays')))->get();
        $data['categories'] = Category::pluck('name', 'id');
        $data['catbglist'] = Category::pluck('bg_color', 'id');
        $data['cattxlist'] = Category::pluck('text_color', 'id');
        $data['companyProfiles'] = CompanyProfile::getList();

        //retrieve configured coreevents
        $coreevents = [];
        $filter = request()->filter ?: (new Setting())->coreeventsEnabled();

        $coredata = [
            //quote sent or approved,based on displayinvoiced setting, with client
            'quote'         => (config('bt.schedulerDisplayInvoiced') == 1) ?
                Quote::where(function ($query) {
                    $query->sentorapproved();
                })
                    ->with('client') :
                Quote::where(function ($query) {
                    $query->notinvoiced();
                })
                    ->where(function ($query) {
                        $query->sentorapproved();
                    })
                    ->with('client'),
            //workorder sent or approved, based on displayinvoiced setting, with client
            'workorder'     => (config('bt.schedulerDisplayInvoiced') == 1) ?
                Workorder::where(function ($query) {
                    $query->sentorapproved();
                })
                    ->with('client', 'workorderItems.employees') :
                Workorder::where(function ($query) {
                    $query->notinvoiced();
                })
                    ->where(function ($query) {
                        $query->sentorapproved();
                    })
                    ->with('client', 'workorderItems.employees'),
            'invoice'       => Invoice::sent()->with('client'),
            'payment'       => Payment::with(['invoice', 'paymentMethod']),
            'expense'       => Expense::status('not_billed')->with(['category']),
            'project'       => TimeTrackingProject::statusid('1'),
            'task'          => TimeTrackingTask::unbilled()->with(['project', 'timers']),
            'purchaseorder' => Purchaseorder::sentorpartial()->with(['vendor']),
        ];

        foreach ($coredata as $type => $source) {
            if (!count($filter) || in_array($type, $filter)) {
                $source->where(function ($query) {
                    $start = Carbon::now()->subDays(config('bt.schedulerPastdays'));
                    $end = Carbon::now()->addCentury();//really.....
                    return $query->dateRange($start, $end);
                });

                foreach ($source->get() as $entity) {
                    $coreevents[] = (new CalendarEventPresenter())->calendarEvent($entity, $type);
                }
            }
        }

        $data['coreevents'] = $coreevents;

        return view('schedule.calendar', $data);
    }

    public function showSchedule()
    {
        if (!isset($_POST['back']) && !isset($_POST['forward'])) {
            $date = new Carbon();
        }

        if (isset($_POST['forward'])) {
            $date = Carbon::parse($_POST['sdate']);
            $date->addDays(4);
        }

        if (isset($_POST['back'])) {
            $date = Carbon::parse($_POST['sdate']);
            $date->subDays(4);
        }

        $mySdate = $date->copy()->format('Y-m-d');
        $my1date = $date->copy()->addDays(1)->format('Y-m-d');
        $my2date = $date->copy()->addDays(2)->format('Y-m-d');
        $myEdate = $date->copy()->addDays(3)->format('Y-m-d');

        $dates = [$mySdate, $my1date, $my2date, $myEdate];

        $companyProfiles = CompanyProfile::getList();


        $scheduled_employees = Workorder::with(['client', 'workorderItems.employees', 'workorderItems' => function ($q) {
            $q->where('resource_table', 'employees');
        }])->whereBetween('job_date', [$mySdate, $myEdate])->approved()->get();

        $scheduled_products = Workorder::with(['client', 'workorderItems' => function ($q) {
            $q->where('resource_table', 'products');
        }])->whereBetween('job_date', [$mySdate, $myEdate])->approved()->get();

        $scheduled_calemployees = Schedule::withOccurrences()->with(['resources' => function ($q) {
            $q->where('resource_table', 'employees');
        }])->whereDate('start_date', '>=', $mySdate)->whereDate('start_date', '<=', $myEdate)->get();

        $aedata = [];
        $ardata = [];

        foreach ($dates as $date) {
            list($available_employees, $available_resources) = $this->getResourceStatus($date);

            $aedata[$date] = $available_employees;
            $ardata[$date] = $available_resources;
        }

        return view('schedule.showschedule')
            ->with('dates', $dates)
            ->with('aedata', $aedata)
            ->with('ardata', $ardata)
            ->with('scheduledemp', $scheduled_employees)
            ->with('scheduledprod', $scheduled_products)
            ->with('scheduledcalemp', $scheduled_calemployees)
            ->with('companyProfiles', $companyProfiles);
    }

    public function tableEvent(EventRequest $request)
    {
        return view('schedule.tableEvent');
    }

    public function tableRecurringEvent(Request $request)
    {
        return view('schedule.tableRecurringEvent');
    }

    public function scheduledResources($date)
    {
        list($available_employees, $available_resources) = $this->getResourceStatus($date);

        return response()->json(['success' => true, 'available_employees' => $available_employees, 'available_resources' => $available_resources], 200);
    }

    //trash
    public function trashEvent($id)
    {
        $event = Schedule::find($id);
        $event->delete();

        return back()->with('alertSuccess', trans('bt.record_successfully_trashed'));
    }

    public function trashReminder(Request $request)
    {
        $event = ScheduleOccurrence::find($request->id);
        $event->reminder_qty = 0;
        $event->reminder_interval = 'none';
        $event->reminder_date = null;
        $event->save();

        return back()->with('alertSuccess', trans('bt.record_successfully_trashed'));
    }

    public function bulkDelete()
    {
        Schedule::destroy(request('ids'));
        return response()->json(['success' => trans('bt.record_successfully_trashed')], 200);
    }

    public function checkSchedule()
    {
        $today = new Carbon();
        $employees = Employee::where('schedule', 1)->where('active', 1)->pluck('id');
        $empresources = WorkorderItem::whereHas('workorder', function ($q) use ($today) {
            $q->whereDate('job_date', '>=', $today->subDay(1))->where('workorder_status_id', 3)->where('invoice_id', 0);
        })->with('workorder')->where('resource_table', 'employees')->whereNotIn('resource_id', $employees)->get();

        return view('schedule.orphanCheck')->with('empresources', $empresources);

    }

    public function getReplaceEmployee($item_id, $name, $date)
    {
        $inactive_employee = Employee::where('short_name', $name)->first();

        list($available_employees) = $this->getResourceStatus($date);

        if (empty($available_employees)) {
            $available_employees[0] = trans('bt.no_emp_available');
        }

        return view('schedule.modal_replace_employee')
            ->with('item_id', $item_id)
            ->with('inactive_employee', $inactive_employee)
            ->with('available_employees', $available_employees->pluck('short_name', 'id'));
    }

    public function setReplaceEmployee(ReplaceRequest $request)
    {
        $item = WorkorderItem::find($request->id);
        $item->resource_id = $request->resource_id;
        $item->name = $request->name;
        $item->description = substr_replace($item->description, $request->resource_id, strpos($item->description, "-") + 1);
        $item->save();

        return response()->json(['success' => trans('bt.employee_successfully_replaced')], 200);
    }

    public function getResourceStatus($date)
    {

        $employees_appointments = ScheduleResource::whereHas('occurrence', function ($q) use ($date) {
            $q->whereDate('start_date', '=', $date);
        })->orderBy('value')->get('resource_id');

        $employees_scheduled = WorkorderItem::whereHas('workorder', function ($q) use ($date) {
            $q->whereDate('job_date', '=', $date)->approved();
        })->where('resource_table', 'employees')->orderBy('name')->get('resource_id');

        $employees_unscheduled = Employee::where('active', '=', '1')->where('schedule', '=', '1')
            ->where(function ($query) use ($date){
                $query->whereNull('term_date')
                    ->orWhere('term_date', '>', $date);
            })
            ->whereNotIn('id', $employees_appointments)
            ->whereNotIn('id', $employees_scheduled)
            ->orderBy('short_name')->get(['id', 'short_name', 'driver']);

        $resources_scheduled = WorkorderItem::whereHas('workorder', function ($q) use ($date) {
            $q->whereDate('job_date', '=', $date)->approved();
        })->where('resource_table', 'products')->orderBy('name')->get(['resource_id', 'name', 'quantity']);

        $resources_unscheduled = Product::where('active', '=', '1')
            ->orderBy('category_id')->orderBy('name')
            ->get(['id', 'name', 'numstock']);

        //check against numstock and remove if necessary
        foreach ($resources_scheduled as $key => $equip) {
            foreach ($resources_unscheduled as $key1 => $active) {
                if ($equip->resource_id == $active->id) {
                    if ($equip->quantity >= $active->numstock) {
                        $resources_unscheduled->forget($key1);
                    }
                }
            }
        }
        return [$employees_unscheduled, $resources_unscheduled];
    }
}

