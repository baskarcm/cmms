<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\AdminUser;
use App\Product;
use App\AdminUserType;
use App\ProductForm;
use App\PmModule;
use App\ProductType;
use App\BreakdownModule;
use App\Schedule;
use App\ProductionUptime;
use App\BreakdownProblemDetail;
use Auth;
use DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
    	$response_data["title"]         = __("title.private.dashboard");
        $response_data['user_count']    =     User::select()->count();
        $response_data['admin_count']   =     AdminUser::whereActive(1)->count();
        $response_data['manager_count'] =     User::whereUserType(3)->whereActive(1)->count();

        //Form count
        // $formgrp = ProductForm::whereActive(1)->get();
        // $collection = collect($formgrp);
        // $grouped = $collection->groupBy('product_type');
        // $grouped->toArray();
        //$response_data['form_count']    = count($grouped);

        $response_data['form_count']    = ProductType::whereActive(1)->count();
        //Form count end
        $response_data['schedule_count']    = Schedule::whereActive(1)->count();
        $response_data['tech_count']     =     User::whereUserType(1)->whereActive(1)->count();
        
        $response_data['engineer_count']     =     User::whereUserType(2)->whereActive(1)->count();
        return view('admin.dashboard')->with($response_data);
    }

    public function barChart(Request $request)
    {
        $start_date=$request->startDate;
        $end_date=$request->endDate;
        $dates = [];
        $days  = [];
        $target= [];
        $acctual =[];
        $response_data = [];
        $currentMonth  = date('M');
        $currentDate   = date('D');
        $currentyear   = date('Y');
        $carbon_Date   = new Carbon($start_date);
        $getMonth      = $carbon_Date->format('M');
        $getYear       = $carbon_Date->format('Y');
        $carbonEndDate = new Carbon($end_date);
        $getEndyear    = $carbonEndDate->format('Y');

        $period = CarbonPeriod::create($start_date, $end_date);
        $i=0;
        foreach ($period as $date)
        {
            $date_count= $i++;
        }

       if($date_count < 32)
       {
            foreach ($period as $date){
                $day = $date->format('Y-m-d');
                if($request->Status == 0)
                {
                    $record_module = [];
                    //PM SCHEDULE
                    $record_schedule        =  Schedule::whereModuleType(1)->whereDate('schedule_date',$day)->whereActive(1)->get();

                    foreach($record_schedule as $record)
                    {
                        $rd= PmModule::whereScheduleId($record->id)->whereEngineerStatus(2)->whereActive(1)->first();
                        if($rd)
                        {
                            $record_module[]          = $rd;
                        }
                    }

                    $schedule               = count($record_schedule);
                    $module                 = count($record_module);
                    if($schedule > 0 )
                    {
                        $percentage = $module/$schedule *100;
                    }else
                    {
                        $percentage = 0;
                    }
                }else{
                    //BREAKDOWN SCHEDULE
                    $record_module =[];
                    $record_schedule        =  Schedule::whereModuleType(2)->whereDate('schedule_date',$day)->whereActive(1)->get();

                    foreach($record_schedule as $record)
                    {

                        $rd= BreakdownModule::whereScheduleId($record->id)->whereEngineerStatus(2)->whereActive(1)->first();
                        if($rd)
                        {
                            $record_module[]          = $rd;
                        }
                    }

                    $schedule               = count($record_schedule);
                    $module                 = count($record_module);
                    if($schedule > 0 )
                    {
                        $percentage = $module/$schedule *100;
                    }else
                    {
                        $percentage = 0;
                    }
                }

                $target[] =  $schedule;
                $acctual[]   =  $module;
                $dates[]    = ceil($percentage);
                $now        = new Carbon($day);
                $days[]     = $now->format('d-M');
            }
            if($getYear == $getEndyear)
            {
                $response_data['chart_year']= $getYear;
            }else
            {
                $response_data['chart_year']= $getYear."-".$getEndyear;
            }
       }
       elseif($currentMonth == $getMonth && $getYear == $getEndyear)
       {
            foreach ($period as $date) {
                $day = $date->format('Y-m-d');
                if($request->Status == 0)
                {
                    //PM SCHEDULE
                    $record_module = [];
                    $record_schedule        =  Schedule::whereModuleType(1)->whereDate('schedule_date',$day)->get();
                    foreach($record_schedule as $record)
                    {
                        $rd= PmModule::whereEngineerStatus(2)->whereScheduleId($record->id)->whereActive(1)->first();
                        if($rd)
                        {
                            $record_module[]          = $rd;
                        }
                    }
                    $schedule               = count($record_schedule);
                    $module                 = count($record_module);
                    if($schedule > 0 )
                    {
                        $percentage = $module/$schedule *100;
                    }else
                    {
                        $percentage = 0;
                    }
                }else{
                    //BREAKDOWN SCHEDULE
                    $record_module = [];
                    $record_schedule        =  Schedule::whereModuleType(2)->whereDate('schedule_date',$day)->get();
                    foreach($record_schedule as $record)
                    {
                        $rd= BreakdownModule::whereEngineerStatus(2)->whereScheduleId($record->id)->whereActive(1)->first();
                        if($rd)
                        {
                            $record_module[]          = $rd;
                        }
                    }
                    $schedule               = count($record_schedule);
                    $module                 = count($record_module);
                    if($schedule > 0 )
                    {
                        $percentage = $module/$schedule *100;
                    }else
                    {
                        $percentage = 0;
                    }
                }
                $target[] =  $schedule;
                $acctual[]   =  $module;
                $dates[]    = ceil($percentage);
                $now        = new Carbon($day);
                $days[]     = $now->format('d-M');
            }
            if($getYear == $getEndyear)
            {
                $response_data['chart_year']= $getYear;
            }else
            {
                $response_data['chart_year']= $getYear."-".$getEndyear;
            }
        }elseif($currentMonth != $getMonth && $getYear == $getEndyear)
        {
            $monthStartDate         = new Carbon($start_date);
            $monthEndDate           = new Carbon($end_date);
            $getstartMonth          = round($monthStartDate->format('m'));
            $getEndMonth            = round($monthEndDate->format('m'));
            $month =[
                '1' => 'Jan',
                '2' => 'Feb',
                '3' => 'Mar',
                '4' => 'Apr',
                '5' => 'May',
                '6' => 'Jun',
                '7' => 'July',
                '8' => 'Aug',
                '9' => 'Sep',
                '10' => 'Oct',
                '11' => 'Nov',
                '12' => 'Dec',
            ];

            for($getstartMonth; $getstartMonth <= $getEndMonth;$getstartMonth++)
            {
                if($request->Status == 0)
                {
                    //PM SCHEDULE
                    $record_module = [];
                    $record_schedule        =  Schedule::whereModuleType(1)->whereRaw('MONTH(schedule_date) = ?',[$getstartMonth])->get();
                    foreach($record_schedule as $record)
                    {
                        $rd= PmModule::whereEngineerStatus(2)->whereScheduleId($record->id)->whereActive(1)->first();
                        if($rd)
                        {
                            $record_module[]          = $rd;
                        }
                    }
                    $schedule               = count($record_schedule);
                    $module                 = count($record_module);
                    if($schedule > 0 )
                    {
                        $percentage = $module/$schedule *100;
                    }else
                    {
                        $percentage = 0;
                    }
                }else{
                    //BREAKDOWN SCHEDULE
                    $record_module = [];
                    $record_schedule        =  Schedule::whereModuleType(2)->whereRaw('MONTH(schedule_date) = ?',[$getstartMonth])->get();
                    foreach($record_schedule as $record)
                    {
                        $rd= BreakdownModule::whereEngineerStatus(2)->whereScheduleId($record->id)->whereActive(1)->first();
                        if($rd)
                        {
                            $record_module[]          = $rd;
                        }
                    }
                    $schedule               = count($record_schedule);
                    $module                 = count($record_module);
                    if($schedule > 0 )
                    {
                        $percentage = $module/$schedule *100;
                    }else
                    {
                        $percentage = 0;
                    }
                }
                $target[] =  $schedule;
                $acctual[]   =  $module;
                $dates[]    = ceil($percentage);
                $days[]=$month[$getstartMonth];
            }

            if($getYear == $getEndyear)
            {
                $response_data['chart_year']= $getYear;
            }else
            {
                $response_data['chart_year']= $getYear."-".$getEndyear;
            }
        }elseif($getYear != $getEndyear){
            $yearStartDate          = new Carbon($start_date);
            $yearEndDate            = new Carbon($end_date);
            $getstartYear           = $yearStartDate->format('Y');
            $getEndYear             = $yearEndDate->format('Y');
            for($getstartYear; $getstartYear <= $getEndYear;$getstartYear++)
            {
                if($request->Status == 0)
                {
                    //PM SCHEDULE
                    $record_module = [];
                    $record_schedule        =  Schedule::whereModuleType(1)->whereRaw('YEAR(schedule_date) = ?',[$getstartYear])->get();
                    foreach($record_schedule as $record)
                    {
                        $rd= PmModule::whereEngineerStatus(2)->whereScheduleId($record->id)->whereActive(1)->first();
                        if($rd)
                        {
                            $record_module[]          = $rd;
                        }
                    }
                    $schedule               = count($record_schedule);
                    $module                 = count($record_module);
                    if($schedule > 0 )
                    {
                        $percentage = $module/$schedule *100;
                    }else
                    {
                        $percentage = 0;
                    }
                }else{
                    $record_module = [];
                    $record_schedule        =  Schedule::whereModuleType(2)->whereRaw('YEAR(schedule_date) = ?',[$getstartYear])->get();
                    foreach($record_schedule as $record)
                    {
                        $rd= BreakdownModule::whereEngineerStatus(2)->whereScheduleId($record->id)->whereActive(1)->first();
                        if($rd)
                        {
                            $record_module[]          = $rd;
                        }
                    }
                    $schedule               = count($record_schedule);
                    $module                 = count($record_module);
                    if($schedule > 0 )
                    {
                        $percentage = $module/$schedule *100;
                    }else
                    {
                        $percentage = 0;
                    }
                }
                $target[] =  $schedule;
                $acctual[]   =  $module;
                $dates[]    = ceil($percentage);
                $days[]     = $getstartYear;
            }

            if($getYear == $getEndyear)
            {
                $response_data['chart_year']= $getYear;
            }else
            {
                $response_data['chart_year']= $getYear."-".$getEndyear;
            }
        }

        $response_data['days']        = $days;
        $response_data['target']      = $target;
        $response_data['acctual']     = $acctual;
        $response_data['day_count']   = $dates;
        $response_data['status']      = $request->Status;

        if($request->Status == 0)
            {
                //Total percentage
                $record_t_schedule        =     Schedule::whereModuleType(1)->whereActive(1)->get();
                $record_t_module          =     PmModule::whereEngineerStatus(2)->whereActive(1)->get();
                $total_t_schedule         = count($record_t_schedule);
                $total_module             = count($record_t_module);
                if($total_t_schedule > 0 )
                {
                    $total_percentage = $total_module /$total_t_schedule *100;
                }else
                {
                    $total_percentage = 0;
                }
                $response_data['total_count'] = ceil($total_percentage);

                //Month
                $record_m_module = [];
                $record_m_schedule        =  Schedule::whereModuleType(1)->whereRaw('MONTH(schedule_date) = ?',[date('m')])->whereRaw('YEAR(schedule_date) = ?',[date('Y')])
                                            ->whereActive(1)->get();

                    foreach($record_m_schedule as $record_m)
                    {

                            $rd_m        =  PmModule::whereScheduleId($record_m->id)->whereEngineerStatus(2)->whereActive(1)->first();
                            if($rd_m)
                            {
                                $record_m_module[]         =  $rd_m;
                            }
                    }
               $month_schedule           =  count($record_m_schedule);
               $month_module             =  count($record_m_module);
               if($month_schedule > 0 )
               {
                   $month_percentage = $month_module/$month_schedule *100;
               }else
               {
                   $month_percentage = 0;
               }
               $response_data['one_month'] = ceil($month_percentage);

                //YEAR
                $record_y_module = [];
                $record_y_schedule        =  Schedule::whereModuleType(1)->whereRaw('YEAR(schedule_date) = ?',[date('Y')])
                                             ->whereActive(1)->get();
                foreach($record_y_schedule as $record_y)
                {
                    $rd_y         =  PmModule::whereScheduleId($record_y->id)->whereEngineerStatus(2)->whereActive(1)->first();
                    if($rd_y)
                    {
                        $record_y_module[]         = $rd_y;
                    }
                }
                $year_schedule           =  count($record_y_schedule);
                $year_module             =  count($record_y_module);
                if($year_schedule > 0 )
                {
                    $year_percentage = $year_module/$year_schedule*100;
                }else
                {
                    $year_percentage = 0;
                }
                $response_data['one_year'] = ceil($year_percentage);


                 //Day
                $record_d_module =[];
                $record_d_schedule  =  Schedule::whereModuleType(1)->whereRaw('DAY(schedule_date) = ?',[date('d')])->whereRaw('MONTH(schedule_date) = ?',[date('m')])->whereRaw('YEAR(schedule_date) = ?',[date('Y')])
                                                ->whereActive(1)->get();
                foreach($record_d_schedule as $record_d)
                {
                    $rd_d        =  PmModule::whereScheduleId($record_d->id)->whereEngineerStatus(2)->whereActive(1)->first();
                    if($rd_d)
                    {
                        $record_d_module[]         =  $rd_d;
                    }
                }
                $day_schedule               =  count($record_d_schedule);
                $day_module                 =  count($record_d_module);
                if($day_schedule > 0 )
                {
                    $day_percentage = $day_module/$day_schedule *100;
                }else
                {
                    $day_percentage = 0;
                }
                $response_data['today'] = ceil($day_percentage);

            }else{

                //Total percentage
                $record_t_schedule        =     Schedule::whereModuleType(2)->whereActive(1)->get();
                $record_t_module          =     BreakdownModule::whereEngineerStatus(2)->whereActive(1)->get();
                $total_t_schedule         = count($record_t_schedule);
                $total_module             = count($record_t_module);
                if($total_t_schedule > 0 )
                {
                    $total_percentage = $total_module /$total_t_schedule *100;
                }else
                {
                    $total_percentage = 0;
                }
                $response_data['total_count'] = ceil($total_percentage);

                //Month
                $record_m_module = [];
                $record_m_schedule        =  Schedule::whereModuleType(2)->whereRaw('MONTH(schedule_date) = ?',[date('m')])->whereRaw('YEAR(schedule_date) = ?',[date('Y')])
                                            ->whereActive(1)->get();

                    foreach($record_m_schedule as $record_m)
                    {

                            $rd_m        =  BreakdownModule::whereScheduleId($record_m->id)->whereEngineerStatus(2)->whereActive(1)->first();
                            if($rd_m)
                            {
                                $record_m_module[]         =  $rd_m;
                            }
                    }
               $month_schedule           =  count($record_m_schedule);
               $month_module             =  count($record_m_module);
               if($month_schedule > 0 )
               {
                   $month_percentage = $month_module/$month_schedule *100;
               }else
               {
                   $month_percentage = 0;
               }
               $response_data['one_month'] = ceil($month_percentage);

                //YEAR
                $record_y_module = [];
                $record_y_schedule        =  Schedule::whereModuleType(2)->whereRaw('YEAR(schedule_date) = ?',[date('Y')])
                                             ->whereActive(1)->get();
                foreach($record_y_schedule as $record_y)
                {
                    $rd_y         =  BreakdownModule::whereScheduleId($record_y->id)->whereEngineerStatus(2)->whereActive(1)->first();
                    if($rd_y)
                    {
                        $record_y_module[]         = $rd_y;
                    }
                }
                $year_schedule           =  count($record_y_schedule);
                $year_module             =  count($record_y_module);
                if($year_schedule > 0 )
                {
                    $year_percentage = $year_module/$year_schedule*100;
                }else
                {
                    $year_percentage = 0;
                }
                $response_data['one_year'] = ceil($year_percentage);


                 //Day
                $record_d_module =[];
                $record_d_schedule  =  Schedule::whereModuleType(2)->whereRaw('DAY(schedule_date) = ?',[date('d')])->whereRaw('MONTH(schedule_date) = ?',[date('m')])->whereRaw('YEAR(schedule_date) = ?',[date('Y')])
                                                ->whereActive(1)->get();
                foreach($record_d_schedule as $record_d)
                {
                    $rd_d        =  BreakdownModule::whereScheduleId($record_d->id)->whereEngineerStatus(2)->whereActive(1)->first();
                    if($rd_d)
                    {
                        $record_d_module[]         =  $rd_d;
                    }
                }
                $day_schedule               =  count($record_d_schedule);
                $day_module                 =  count($record_d_module);
                if($day_schedule > 0 )
                {
                    $day_percentage = $day_module/$day_schedule *100;
                }else
                {
                    $day_percentage = 0;
                }
                $response_data['today'] = ceil($day_percentage);
            }
        return response()->json($response_data);
    }

    public function pmChart(Request $request)
    {
        $record = [];
        $pm_record = [];
        $record_schedule        =  Schedule::whereModuleType(1)->whereActive(1)
                                    ->whereDate('schedule_date', '>=', $request->startdate)
                                    ->whereDate('schedule_date', '<=', $request->enddate )
                                    ->get();
                                    
        foreach($record_schedule as $value){
            
            $record_module          =  PmModule::whereScheduleId($value->id)->whereEngineerStatus(2)->whereActive(1)->first();
            if($record_module)
            {
                 $pm_record[] = $record_module;
            }
        }
        
        $schedule               = count($record_schedule);
        $module                 = count($pm_record);
        if($schedule > 0 )
        {
            $pending = $schedule - $module;
        }else
        {
            $pending = 0;
        }
        $record = [ $schedule,$module ,$pending];
        $response_data['data'] = $record;
        $response_data['schedule'] = $schedule;
        $response_data['module'] = $module;
        $response_data['pending'] = $pending;
        return response()->json($response_data);
    }

    public function bkChart(Request $request)
    {
        $record = [];
        $pm_record = [];
        $record_schedule        =  Schedule::whereModuleType(2)->whereActive(1)
                                    ->whereDate('schedule_date', '>=', $request->startdate)
                                    ->whereDate('schedule_date', '<=', $request->enddate )
                                    ->get();
                                    
        foreach($record_schedule as $value){
            
            $record_module          =  BreakdownModule::whereScheduleId($value->id)->whereEngineerStatus(2)->whereActive(1)->first();
            if($record_module)
            {
                 $pm_record[] = $record_module;
            }
        }
        
        $schedule               = count($record_schedule);
        $module                 = count($pm_record);
        if($schedule > 0 )
        {
            $pending = $schedule - $module;
        }else
        {
            $pending = 0;
        }
        $record = [ $schedule,$module ,$pending];
        $response_data['data'] = $record;
        $response_data['schedule'] = $schedule;
        $response_data['module'] = $module;
        $response_data['pending'] = $pending;
        return response()->json($response_data);
        
        // $record = [];
        // $record_schedule        =  Schedule::whereModuleType(2)->whereActive(1)->get();
        // $record_module          =  BreakdownModule::whereEngineerStatus(2)->whereActive(1)->get();
        // $schedule               = count($record_schedule);
        // $module                 = count($record_module);
        // if($schedule > 0 )
        // {
        //     $pending = $schedule - $module;
        // }else
        // {
        //     $pending = 0;
        // }
        // $record = [ $schedule,$module ,$pending];
        // $response_data['data'] = $record;
        // $response_data['schedule'] = $schedule;
        // $response_data['module'] = $module;
        // $response_data['pending'] = $pending;
        // return response()->json($response_data);
    }

    public function lineChart(Request $request)
    {
        if($request->type ==1)
        {
            $dateMonthArray = explode('-', $request->month);
            $month = $dateMonthArray[0];
            $year = $dateMonthArray[1];
            $line = $request->line;
            $name = [];
            $total = [];
            $product = ProductType::whereLocation($line)->get();
            foreach($product as $keys => $products)
            {
                $sum = 0;
                $product_form = ProductType::whereId($products->id)->with('product')->whereLocation($line)->first();
                $name[] = isset($product_form->product) ? $product_form->product->name : [];
                $bk = BreakdownModule::select('product_type',DB::raw('sum(downtime_minute) as total'))->whereProductType($products->id)->whereRaw('MONTH(date) = ?',[$month])->whereRaw('YEAR(date) = ?',[$year])->groupBy('product_type')->first();
                if($bk)
                {
                    $sum +=  $bk->total;
                    $total[] =  $sum;
                }else{
                    $total[] = 0;
                }

            }
            $response_data['name'] = $name;
            $response_data['total'] = $total;
            return response()->json($response_data);
        }else
        {

            $year = $request->year;
            $line = $request->line;
            $name = [];
            $total = [];
            $product = ProductType::whereLocation($line)->get();
            foreach($product as $keys => $products)
            {
                $sum = 0;
                $product_form = ProductType::whereId($products->id)->with('product')->whereLocation($line)->first();
                $name[] = $product_form->product->name;
                $bk = BreakdownModule::select('product_type',DB::raw('sum(downtime_minute) as total'))->whereProductType($products->id)->whereRaw('YEAR(date) = ?',[$year])->groupBy('product_type')->first();
                if($bk)
                {
                    $sum +=  $bk->total;
                    $total[] =  $sum;
                }else{
                    $total[] = 0;
                }
            }
            $response_data['name'] = $name;
            $response_data['total'] = $total;
            return response()->json($response_data);
        }
    }

    public function performance(Request $request){

        $dateMonthArray = explode('-', $request->month);
            $month = $dateMonthArray[0];
            $year = $dateMonthArray[1];

        // $month =[
        //     '01' => 'jan', '02' => 'feb', '03' => 'mar', '04' => 'apr', '05' => 'may', '06' => 'jun',
        //     '07' => 'july', '08' => 'aug', '09' => 'sep', '10' => 'oct', '11' => 'nov', '12' => 'dec',
        // ];

        $pro = [];
        $dt = [];
        $ut = [];
        $production_time= [];
        $locations = [];

        $total1 = 0;  $total2 = 0; $total3 = 0; $total4 = 0; $total5 = 0; $total6 = 0; $total7 = 0; $total8 = 0; $total9 = 0;
        $total10 = 0; $total11 = 0; $total12 = 0; $totalsum = 0;

        $production = ProductionUptime::where('year',$year)->where('type',1)->get();
        $downtime   = ProductionUptime::where('year',$year)->where('type',2)->get();
        $uptime     = ProductionUptime::where('year',$year)->where('type',6)->get();

        $data = ProductionUptime::select('line')->where('year',$year)->groupBy('line')->get();
        
        if($data){
            $line_count = count($data);
            foreach($data as $datas)
            {
                $pdown  = 0;
                $location = $datas->line;
                $locations[] = $datas->line;
                $line     = ProductionUptime::whereLine($datas->line)->where('year',$year)->whereType(6)->first();
                $total1 += $line->jan;  $total2 += $line->feb;  $total3 += $line->mar;  $total4 += $line->apr;
                $total5 += $line->may;  $total6 += $line->jun;  $total7 += $line->jul;  $total8 += $line->aug;
                $total9 += $line->sep;  $total10 += $line->oct; $total11 += $line->nov; $total12 += $line->dec;

                //PRODUCTION TIME COUNT
                $bk = BreakdownModule::with('equipment')->select('product_type',DB::raw('sum(downtime_minute) as total'))->whereRaw('MONTH(date) = ?',[$month])->whereRaw('YEAR(date) = ?',[$year])->whereHas('equipment', function ($query) use($location) {
                    $query->where('location', $location);
                })->groupBy('product_type')->get();
                if(count($bk) > 0 )
                {
                    foreach($bk as $key => $value)
                    {
                        $productType = ProductType::whereId($value->product_type)->with('product')->first();
                        $problem = BreakdownProblemDetail::whereProductType($value->product_type)->first();
                        if($problem)
                        {
                            $pdown += $problem->pdown;
                        }
                    }
                }

                $production_time[] = $pdown;
            }
                $data1 = $total1 ? $total1/$line_count : 0 ;
                $data2 = $total2 ? $total2/$line_count : $total2 ; 
                $data3 = $total3 ? $total3/$line_count : $total3 ;
                $data4 = $total4 ? $total4/$line_count : $total4 ;
                $data5 = $total5 ? $total5/$line_count : $total5 ;
                $data6 = $total6 ? $total6/$line_count :$total6 ; 
                $data7 = $total7 ? $total7/$line_count : $total7 ;
                $data8 = $total8 ? $total8/$line_count : $total8 ;
                $data9 = $total9 ? $total9/$line_count :$total9 ; 
                $data10 = $total10 ? $total10/$line_count : $total10;
                $data11 = $total11 ? $total11/$line_count :$total11 ; 
                $data12 = $total12 ? $total12/$line_count :$total12 ;


                    switch  ($month) {
                        case "01":
                                foreach($production as $productions) { $pro[] = $productions->jan; }
                                foreach($downtime as $downtimes)     { $dt[] = $downtimes->jan; }
                                foreach($uptime as $uptimes)         { $ut[] = $uptimes->jan;}
                                break;
                        case "02":
                                foreach($production as $productions) { $pro[] = $productions->feb; }
                                foreach($downtime as $downtimes)     { $dt[] = $downtimes->feb; }
                                foreach($uptime as $uptimes)         { $ut[] = $uptimes->feb;}
                                break;
                        case "03":
                                foreach($production as $productions) { $pro[] = $productions->mar; }
                                foreach($downtime as $downtimes)     { $dt[] = $downtimes->mar; }
                                foreach($uptime as $uptimes)         { $ut[] = $uptimes->mar;}
                                break;
                        case "04":
                                foreach($production as $productions) { $pro[] = $productions->apr; }
                                foreach($downtime as $downtimes)     { $dt[] = $downtimes->apr; }
                                foreach($uptime as $uptimes)         { $ut[] = $uptimes->apr;}
                                break;
                        case "05":
                                foreach($production as $productions) { $pro[] = $productions->may; }
                                foreach($downtime as $downtimes)     { $dt[] = $downtimes->may; }
                                foreach($uptime as $uptimes)         { $ut[] = $uptimes->may;}
                                break;
                        case "06":
                                foreach($production as $productions) { $pro[] = $productions->jun; }
                                foreach($downtime as $downtimes)     { $dt[] = $downtimes->jun; }
                                foreach($uptime as $uptimes)         { $ut[] = $uptimes->jun;}
                                break;
                        case "07":
                                foreach($production as $productions) { $pro[] = $productions->jul; }
                                foreach($downtime as $downtimes)     { $dt[] = $downtimes->jul; }
                                foreach($uptime as $uptimes)         { $ut[] = $uptimes->jul;}
                                break;
                        case "08":
                                foreach($production as $productions) { $pro[] = $productions->aug; }
                                foreach($downtime as $downtimes)     { $dt[] = $downtimes->aug; }
                                foreach($uptime as $uptimes)         { $ut[] = $uptimes->aug;}
                                break;
                        case "09":
                                foreach($production as $productions) { $pro[] = $productions->sep; }
                                foreach($downtime as $downtimes)     { $dt[] = $downtimes->sep; }
                                foreach($uptime as $uptimes)         { $ut[] = $uptimes->sep;}
                                break;
                        case "10":
                                foreach($production as $productions) { $pro[] = $productions->oct; }
                                foreach($downtime as $downtimes)     { $dt[] = $downtimes->oct; }
                                foreach($uptime as $uptimes)         { $ut[] = $uptimes->oct;}
                                break;
                        case "11":
                                foreach($production as $productions) { $pro[] = $productions->nov; }
                                foreach($downtime as $downtimes)     { $dt[] = $downtimes->nov; }
                                foreach($uptime as $uptimes)         { $ut[] = $uptimes->nov;}
                                break;
                        case "12":
                                foreach($production as $productions) { $pro[] = $productions->dec; }
                                foreach($downtime as $downtimes)     { $dt[] = $downtimes->dec; }
                                foreach($uptime as $uptimes)         { $ut[] = $uptimes->dec;}
                                break;
                        }

                // $response_data['production'] = $pro;
                // $response_data['downtime'] = $dt;
                // $response_data['uptime'] = $ut;
                // $response_data['pro_time'] = $production_time;
                // $response_data['production_total'] = ;
                    $response_data['breakdown_total'] = array_sum($dt);
                    $response_data['uptime_total'] = array_sum($ut);
                // $response_data['pro_time_total'] = array_sum($production_time);
                // $response_data['line'] = $locations;
                $production_reocrd = [];
                foreach($locations as $key => $lines )
                {
                    $production_reocrd[$key]['line'] = $locations[$key];
                    $production_reocrd[$key]['pro'] = $pro[$key];
                    $production_reocrd[$key]['dt'] = $dt[$key];
                    $production_reocrd[$key]['ut'] = $ut[$key];
                    $production_reocrd[$key]['pro_down'] = $production_time[$key];

                }
                $totals = ['line'=>"Total",'pro'=>array_sum($pro),'dt'=>array_sum($dt),'ut'=> $ut ? array_sum($ut)/$line_count:0,'pro_down'=>array_sum($production_time)];
                array_push($production_reocrd, $totals);
                $response_data['records'] =  $production_reocrd;

                $percentage = [$data1,$data2,$data3,$data4,$data5,$data6,$data7,$data8,$data9,$data10,$data11,$data12];
                $avg = array_sum($percentage)/12;
                array_push( $percentage,round($avg, 2));
                $response_data['performance'] = $percentage;

        }else{
            $response_data['records'] =  [];
            $response_data['performance'] = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
        }

            return response()->json( $response_data);
    }
}
