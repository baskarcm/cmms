<?php

namespace App\Http\Controllers\Admin;
use Auth;
use App\User;
use App\Gender;
use App\UserType;
use App\Event;
use App\BreakdownModule;
use App\PmModule;
use App\Schedule;
use App\ModuleType;
use App\ProductType;
use App\NotifyStatus;
use App\Notification;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Events\EventCreationEvent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Exports\ScheduleExport;
use App\Exports\PlanvsActualExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
     
        $response_data["title"]         = __("title.private.schedule_list");
        $response_data["products"]      = productType::with('equipment')->whereActive(1)->get();
        $response_data["user"]          = User::whereUserType(1)->whereActive(1)->get();
        $response_data["engineer"]       = User::whereUserType(2)->whereActive(1)->get();
        $response_data["manager"]       = User::whereUserType(3)->whereActive(1)->get();
        $response_data["moduleType"]    = ModuleType::whereActive(1)->get();
      
        return view("admin.schedule.list")->with($response_data);
    }

    public function productType(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
          'data' => 'required|exists:product_types,id,deleted_at,NULL',
        ]);

        if (!$validator->fails())
        {
            $data = productType::whereId($request->data)->first();
            if($data)
            {
                $response_data = ["success" => 1,  "data" => $data];
            }
            else
            {
                $response_data = ["success" => 0, "message" => __("site.server_error")];
            }
        }
        else
        {
            $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()];
        }
        return response()->json($response_data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $new_request = $request->all();
        $validator = Validator::make($new_request,
            [
              'product'         => 'required|exists:product_types,id,active,1,deleted_at,NULL',
              'user'            => 'nullable|exists:users,id,active,1,deleted_at,NULL',
              'engineer'        => 'nullable|exists:users,id,active,1,deleted_at,NULL',
              'manager'         => 'nullable|exists:users,id,active,1,deleted_at,NULL',
              'date'            => 'required',
              'module_type'     => 'required|exists:module_types,id,active,1,deleted_at,NULL',
              'title'           => 'nullable',
              'ref_no'          => 'nullable',
              'failure'         => 'nullable',
              'reporting'       => 'nullable',
            ]);

        if(!$validator->fails()){

            if($request->module_type == 2)
            {
                $check = Schedule::whereProductType($request->product)->whereUserId($request->user)->whereScheduleStatus(0)->whereModuleType(2)->first();
                if($check)
                {
                    $response_data = ["success" => 0, "message" => "The employee  as same pending  breakdown schedule"];
                }else
                {
                    $data = new Schedule();
                    $data->product_type     = $request->product;
                    $data->user_id          = $request->user;
                    $data->engineer_id       = $request->engineer;
                    $data->manager_id       = $request->manager;
                    $data->schedule_date    = $request->date;
                    $data->module_type      = $request->module_type;
                    if($request->title != "")
                    {
                        $data->title            = $request->title;
                        $data->ref_no           = $request->ref_no;
                        $data->failure          = $request->failure;
                        $data->reporting        = $request->reporting;
                    }
                    $data->active           = 1;
                    $data->created_by       = Auth::id();
                    $data->updated_by       = Auth::id();
                    if($data->save()){

                        $read = new NotifyStatus();
                        $read->schedule_id = $data->id;
                        $read->save();

                        $notification = [];
                        if(!empty($request->user))
                        {
                            $notification[] = $request->user;
                        }
                        if(!empty($request->engineer))
                        {
                            $notification[] = $request->engineer;
                        }
                        if(!empty($request->manager))
                        {
                            $notification[] = $request->manager;
                        }
                        
                        $spectator = User::whereUserType(4)->whereActive(1)->get();
                        if($spectator)
                        {
                            foreach($spectator as $spec)
                            {
                                $notification[] = $spec->id;
                            }
                        }

                        foreach($notification as $notify_record)
                        {
                            $notify = new Notification();
                            $notify->schedule_id = $data->id;
                            $notify->user_id = $notify_record;
                            $notify->module_type = 2;
                            $notify->type = 1;
                            $notify->created_by       = Auth::id();
                            $notify->updated_by       = Auth::id();
                            $notify->save();
                            $check = User::whereId($notify_record)->first();
                            if(!empty($check->device_token))
                            {
                                //Log::info($check->device_token);
                              event(new EventCreationEvent($notify, Auth::id()));
                            }
                        }

                        $response_data = ["success" => 1,"message" => __("validation.create_success",['attr'=>'Breakdown schedule'])];
                    }else{
                        $response_data = ["success" => 0, "message" => __("site.server_error")];
                    }
                }
            }

            if($request->module_type == 1)
            {
                $check2 = Schedule::whereProductType($request->product)->whereUserId($request->user)->whereScheduleStatus(0)->whereModuleType(1)->first();
                if($check2)
                {
                    $response_data = ["success" => 0, "message" => "The employee  as same pending PM schedules"];
                }else
                {
                    $data = new Schedule();
                    $data->product_type     = $request->product;
                    $data->user_id          = $request->user;
                    $data->engineer_id       = $request->engineer;
                    $data->manager_id       = $request->manager;
                    $data->schedule_date    = $request->date;
                    $data->module_type      = $request->module_type;
                    if($request->title != "")
                    {
                        $data->title            = $request->title;
                    }
                    $data->active           = 1;
                    $data->created_by       = Auth::id();
                    $data->updated_by       = Auth::id();
                    if($data->save()){

                        $read = new NotifyStatus();
                        $read->schedule_id = $data->id;
                        $read->save();

                        $notification = [];
                        if(!empty($request->user))
                        {
                            $notification[] = $request->user;
                        }
                        if(!empty($request->engineer))
                        {
                            $notification[] = $request->engineer;
                        }
                        if(!empty($request->manager))
                        {
                            $notification[] = $request->manager;
                        }
                        
                        $spectator = User::whereUserType(4)->whereActive(1)->get();
                        if($spectator)
                        {
                            foreach($spectator as $spec)
                            {
                                $notification[] = $spec->id;
                            }
                        }
                        
                        foreach($notification as $notify_record)
                        {
                            $notify = new Notification();
                            $notify->schedule_id = $data->id;
                            $notify->user_id = $notify_record;
                            $notify->module_type = 1;
                            $notify->type = 1;
                            $notify->created_by       = Auth::id();
                            $notify->updated_by       = Auth::id();
                            $notify->save();
                            $check = User::whereId($notify_record)->first();
                            if(!empty($check->device_token))
                            {
                                //Log::info($check->device_token);
                              event(new EventCreationEvent($notify, Auth::id()));
                            }
                        }

                        $response_data = ["success" => 1,"message" => __("validation.create_success",['attr'=>'pm schedule'])];
                    }else{
                        $response_data = ["success" => 0, "message" => __("site.server_error")];
                    }
                }
            }


        }else{
            $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()];
        }
        return response()->json($response_data);
    }

    public function updateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
                    "value" => "required|in:0,1",
                    "pk" => "required|exists:schedules,id",
                ]);
        if(!$validator->fails()){
            $data = Schedule::find($request->pk);
            
            $data->active = $request->value;
            if($data->save()){
              
                $response_data = ["success" => 1, "message" => __("validation.update_success", ["attr" => "Status"])];
            }else{
                $response_data = ["success" => 0, "message" => __("site.server_error")];
            }
        }else{
            $response_data = ["success" =>  0, "message" => __("validation.refresh_page")];
        }
       return response()->json($response_data);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function getList(Request $request)
    {
        $query = Schedule::with(['equipment.product','users:id,name','engineer:id,name','manager:id,name','moduleType:id,name'])->select();
        if($request->has("status") && $request->status != ""){
            $query->whereActive($request->status);
        }
        if($request->has("module") && $request->module != ""){
            $query->whereModuleType($request->module);
        }
        if($request->has("engineer") && $request->engineer != ""){
            $query->whereEngineerId($request->engineer);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('schedule_date', '>=', "{$request->get('start_date')}");
        }

        if ($request->filled('end_date')) {
            $query->whereDate('schedule_date', '<=', "{$request->get('end_date')}");
        }
        return Datatables::of($query->get())->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
          'data' => 'required|exists:schedules,id,deleted_at,NULL',
        ]);

        if (!$validator->fails())
        {
            $data = Schedule::whereId($request->data)->first();
            if($data)
            {
                $response_data = ["success" => 1,  "data" => $data];
            }
            else
            {
                $response_data = ["success" => 0, "message" => __("site.server_error")];
            }
        }
        else
        {
            $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()];
        }
        return response()->json($response_data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $new_request = $request->all();
        $validator = Validator::make($new_request,
            [
              'data'            => 'required|exists:schedules,id,active,1,deleted_at,NULL',
              'product'         => 'required|exists:product_types,id,active,1,deleted_at,NULL',
              'user'            => 'nullable|exists:users,id,active,1,deleted_at,NULL',
              'engineer'         => 'nullable|exists:users,id,active,1,deleted_at,NULL',
              'manager'         => 'nullable|exists:users,id,active,1,deleted_at,NULL',
              'date'            => 'required',
              'module_type'     => 'required|exists:module_types,id,active,1,deleted_at,NULL',
              'title'           => 'nullable',
              'ref_no'          => 'nullable',
              'failure'         => 'nullable',
              'reporting'       => 'nullable',
            ]);

        if(!$validator->fails()){

            $data = Schedule::find($request->data);
            $data->product_type     = $request->product;
            $data->user_id          = $request->user;
            $data->manager_id       = $request->manager;
            $data->engineer_id       = $request->engineer;
            $data->schedule_date    = $request->date;
            $data->module_type      = $request->module_type;
            if($request->title != "")
            {
                $data->title            = $request->title;
                $data->ref_no           = $request->ref_no;
                $data->failure          = $request->failure;
                $data->reporting        = $request->reporting;
            }
            $data->active           = 1;
            $data->created_by       = Auth::id();
            $data->updated_by       = Auth::id();
            if($data->save()){

                $read = NotifyStatus::whereScheduleId($request->data)->first();
                $read->user_status = 0;
                $read->save();

                $notification = [];
                        if(!empty($request->user))
                        {
                            $notification[] = $request->user;
                        }
                        if(!empty($request->engineer))
                        {
                            $notification[] = $request->engineer;
                        }
                        if(!empty($request->manager))
                        {
                            $notification[] = $request->manager;
                        }
                        
                        $spectator = User::where('user_type',4)->whereActive(1)->get();
                        if($spectator)
                        {
                            foreach($spectator as $spec)
                            {
                                $notification[] = $spec->id;
                            }
                        }
                        
                        $record_notify  = Notification::whereScheduleId($request->data)->delete();
                        foreach($notification as $notify_record)
                        {
                            $notify         = new Notification();
                            $notify->schedule_id      = $request->data;
                            $notify->user_id          = $notify_record;
                            $notify->module_type      = $data->module_type;
                            $notify->type             = 1;
                            $notify->created_by       = Auth::id();
                            $notify->updated_by       = Auth::id();
                            $notify->save();
                        }

                $response_data = ["success" => 1,"message" => __("validation.update_success",['attr'=>'schedule'])];
            }else{
                $response_data = ["success" => 0, "message" => __("site.server_error")];
            }

        }else{
            $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()];
        }
        //Log::debug($response_data);

        return response()->json($response_data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
              'data' => 'required|exists:schedules,id,deleted_at,NULL',
            ]);

        if (!$validator->fails())
        {
            $data = Schedule::find($request->data);

            // $check = $this->module($data->module_type,$request->data);

            // if($check == 1)
            // {
            //     $response_data =  ['success' => 0, 'message' => "The schedule is already allocated employee"];
            // }else
            // {
            //         if($data->delete()){

            //             $read = NotifyStatus::whereScheduleId($request->data)->first();
            //             $read->delete();
            //                 $response_data =  ['success' => 1, 'message' => __('validation.delete_success',['attr'=>'schedule'])];
            //         }else{
            //             $response_data =  ['success' => 0, 'message' => __('validation.refresh_page')];
            //         }
            // }
            if($data->delete()){
            $response_data =  ['success' => 1, 'message' => __('validation.delete_success',['attr'=>'schedule'])];
                   }else{
                      $response_data =  ['success' => 0, 'message' => __('validation.refresh_page')];
            }
        }else
        {
            $response_data =  ['success' => 0, 'message' => __('validation.refresh_page')];
        }

        return response()->json($response_data);
    }

    public function module($module_type,$schedule_id)
    {
        if($module_type == 2)
            {
                $bk_module = BreakdownModule::whereScheduleId($schedule_id)->first();
                if($bk_module)
                {
                    return 1;
                }else
                {
                    return 0;
                }
            }else
            {
                $pm_module = PmModule::whereScheduleId($schedule_id)->whereActive(1)->first();

                if($pm_module)
                {
                    return 1;
                }else
                {
                    return 0;
                }
            }
    }

    public function dataExports(Request $request)
    {
        //dd($request);

        $current = strtotime(now());
        $file = "Schedule-".$current.".xlsx";
        Excel::store(new ScheduleExport($request), $file,'local');
        $downloadUrl =url('/')."/storage/app/".$file;
        $response_data = ["success" => 1,"message" => __("validation.create_success",['attr'=>'Schedule Export']),"data"=> $downloadUrl];
        return response()->json($response_data);
    }

    public function actual()
    {
        $response_data["title"] = __("title.private.actual");
        return view("admin.planvsactual.list")->with($response_data);
    }

    public function getActualList(Request $request)
    {
        $dateMonthArray = explode('-', $request->month);
        //dd($dateMonthArray);
        $month = $dateMonthArray[0];
        $year = $dateMonthArray[1];

        $query = Schedule::with(['equipment.product','users:id,name','actualScheduledate'])->whereRaw('MONTH(schedule_date) = ?',[$month])->whereRaw('YEAR(schedule_date) = ?',[$year])->select();
        if($request->has("status") && $request->status != ""){
            $query->whereActive($request->status);
        }
        return Datatables::of($query->get())->make(true);
    }

    public function PlanDataExports(Request $request)
    {
        $current = strtotime(now());
        $file = "Plan vs Actual-".$current.".xlsx";
        Excel::store(new PlanvsActualExport($request), $file,'local');
        $downloadUrl =url('/')."/storage/app/".$file;
        $response_data = ["success" => 1,"message" => __("validation.create_success",['attr'=>'Plan vs Actual Export']),"data"=> $downloadUrl];
        return response()->json($response_data);
    }
}
