<?php

namespace App\Http\Controllers\Api;
use Auth;
use App\User;
use App\Schedule;
use App\ModuleType;
use App\BreakdownModule;
use App\ProductType;
use App\Inventory;
use App\InventoryStock;
use App\BreakdownDetail;
use App\Events\EventCreationEvent;
use Validator;
use App\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use DB;

class BreakdownController extends Controller
{
    public function barcode(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
              'barcode'       => 'required|numeric|exists:product_types,barcode,active,1,deleted_at,NULL',
              'device_type'   => 'required|exists:device_types,code,mobile_type,1,active,1,deleted_at,NULL'
            ]);

        if(!$validator->fails()){
            $credentials = [
                'barcode'     => $request->barcode,
                'active'    => 1
            ];
            $user = auth()->user();
            $id = $user->id;
            $record = ProductType::with('product','schedule')->whereBarcode($request->barcode)->whereHas('schedule', function ($query) use ($id) { 
                $query->where('user_id', $id)
                ;
            })->whereHas('schedule', function($query) {
                $query->where('schedule_status','=','0');
            })->whereHas('schedule', function($query) {
                $query->where('module_type','=','2');
            })->whereActive(1)->first();
            if ($record) {
                $response_data = ["success" => 1,"message" => __("validation.login_success",['attr'=>'Breakdown Schedule']), "data" => $record];   

            } else {
               $response_data = ["success" => 0,  "message" => __("site.invalid_login")];
            }

        }else{
            $response_data = ["success" => 0,  "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        return response()->json($response_data);
    }

    public function getBreakdown(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
              'data'       => 'required|numeric|exists:schedules,id,active,1,deleted_at,NULL',
              'device_type'   => 'required|exists:device_types,code,mobile_type,1,active,1,deleted_at,NULL'
            ]);

        if(!$validator->fails()){
            $user = auth()->user();
            $record = Schedule::with('equipment.product')->whereId($request->data)->whereUserId($user->id)->whereActive(1)->first();
            if ($record) {
                $response_data = ["success" => 1,"message" => __("validation.login_success",['attr'=>'Breakdown Schedule']), "data" => $record];   

            } else {
               $response_data = ["success" => 0,  "message" => __("site.invalid_login")];
            }

        }else{
            $response_data = ["success" => 0,  "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        return response()->json($response_data);
    }
    
    public function getList(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
              'data'       => 'required|numeric|exists:schedules,id,active,1,deleted_at,NULL',
              'device_type'   => 'required|exists:device_types,code,mobile_type,1,active,1,deleted_at,NULL'
            ]);

        if(!$validator->fails()){
            $user = auth()->user();
            $record['schedule']     = Schedule::with('equipment.product')->whereId($request->data)->whereUserId($user->id)->whereActive(1)->first();
            $breakdown              = BreakdownModule::whereUserId($user->id)->whereScheduleId($request->data)->whereActive(1)->first();
            $record['breakdown']    = $breakdown;
            if($breakdown)
            {
                $record['problem']      = BreakdownDetail::select('id','problem','problem_image')->whereBreakdownId($breakdown->id)->whereType(1)->whereActive(1)->get();
                $record['action']       = BreakdownDetail::select('id','action','action_image')->whereBreakdownId($breakdown->id)->whereType(2)->whereActive(1)->get();
                $record['prevention']   = BreakdownDetail::select('id','prevention','prevention_image')->whereBreakdownId($breakdown->id)->whereType(3)->whereActive(1)->get();
                $record['spare']        = Inventory::select('id','date','title','name','file')->whereBreakdownId($breakdown->id)->whereActive(1)->first();
            }
            if ($record) {
                $response_data = ["success" => 1,"message" => __("validation.login_success",['attr'=>'Breakdown Schedule list']), "data" => $record];   

            } else {
               $response_data = ["success" => 0,  "message" => __("site.invalid_login")];
            }

        }else{
            $response_data = ["success" => 0,  "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        return response()->json($response_data);
    }

    public function engineerGetList(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
              'data'       => 'required|numeric|exists:schedules,id,active,1,deleted_at,NULL',
              'device_type'   => 'required|exists:device_types,code,mobile_type,1,active,1,deleted_at,NULL'
            ]);
        if(!$validator->fails()){
            $user = auth()->user();
            $record['schedule']     = Schedule::with('equipment.product')->whereId($request->data)->whereActive(1)->first();
            $breakdown              = BreakdownModule::whereScheduleId($request->data)->whereActive(1)->first();
            $record['breakdown']    = $breakdown;
            if($breakdown)
            {
                $record['problem']      = BreakdownDetail::select('id','problem','problem_image')->whereBreakdownId($breakdown->id)->whereType(1)->whereActive(1)->get();
                $record['action']       = BreakdownDetail::select('id','action','action_image')->whereBreakdownId($breakdown->id)->whereType(2)->whereActive(1)->get();
                $record['prevention']   = BreakdownDetail::select('id','prevention','prevention_image')->whereBreakdownId($breakdown->id)->whereType(3)->whereActive(1)->get();
                $record['spare']        = Inventory::select('id','date','title','name','file')->whereBreakdownId($breakdown->id)->whereActive(1)->first();
            }
            if ($record) {
                $response_data = ["success" => 1,"message" => __("validation.login_success",['attr'=>'Breakdown engineer Schedule list']), "data" => $record];   

            } else {
               $response_data = ["success" => 0,  "message" => __("site.invalid_login")];
            }

        }else{
            $response_data = ["success" => 0,  "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        return response()->json($response_data);
    }

    public function scheduleList()
    {
        $user = auth()->user();
        $bk =   Schedule::with('equipment.product','notify')
                ->whereUserId($user->id)
                ->whereScheduleStatus(0)
                ->whereModuleType(2)
                ->orderBy('created_at', 'desc')
                ->get();      
        $response_data = ["success" => 1, 'data' => $bk];
        return response()->json($response_data);
    }

    public function pendingList()
    {
        $user = auth()->user();
        $bk =   Schedule::with('equipment.product')
                ->whereUserId($user->id)
                ->whereScheduleStatus(1)
                ->whereModuleType(2)
                ->orderBy('created_at', 'desc')
                ->get();
        $response_data = ["success" => 1, 'data' => $bk];
        return response()->json($response_data);
    }

    public function completeList()
    {
        $user = auth()->user();
        $bk =   Schedule::with('equipment.product')
                ->whereUserId($user->id)
                ->whereScheduleStatus(2)
                ->whereModuleType(2)
                ->orderBy('created_at', 'desc')
                ->get();
        $response_data = ["success" => 1, 'data' => $bk];
        return response()->json($response_data);
    }

    public function engineerScheduleList()
    {
        $user = auth()->user();
        $bk =   Schedule::with('equipment.product','notify')
                ->whereEngineerId($user->id)
                ->whereEngineerStatus(0)
                ->whereModuleType(2)
                ->orderBy('created_at', 'desc')
                ->get();
        $response_data = ["success" => 1, 'data' => $bk];
        return response()->json($response_data);
    }

    public function engineerPendingList()
    {
        $user = auth()->user();
        $bk =   Schedule::with('equipment.product')
                ->whereScheduleStatus(1)
                ->whereEngineerId($user->id)
                ->whereEngineerStatus(1)
                ->whereModuleType(2)
                ->orderBy('created_at', 'desc')
                ->get();

        $response_data = ["success" => 1, 'data' => $bk];
        return response()->json($response_data);
    }

    public function engineerCompleteList()
    {
        $user = auth()->user();
        $bk =   Schedule::with('equipment.product')
                ->whereScheduleStatus(2)
                ->whereEngineerId($user->id)
                ->whereEngineerStatus(2)
                ->whereModuleType(2)
                ->orderBy('created_at', 'desc')
                ->get();
        $response_data = ["success" => 1, 'data' => $bk];
        return response()->json($response_data);
    }

    public function managerScheduleList()
    {
        $user = auth()->user();
        $bk =   Schedule::with('equipment.product','notify')
                ->whereManagerId($user->id)
                ->whereEngineerStatus(0)
                ->whereModuleType(2)
                ->orderBy('created_at', 'desc')
                ->get();
        $response_data = ["success" => 1, 'data' => $bk];
        return response()->json($response_data);
    }

    public function managerPendingList()
    {
        $user = auth()->user();
        $bk =   Schedule::with('equipment.product')
                ->whereScheduleStatus(1)
                ->whereManagerId($user->id)
                ->whereEngineerStatus(1)
                ->whereModuleType(2)
                ->orderBy('created_at', 'desc')
                ->get();

        $response_data = ["success" => 1, 'data' => $bk];
        return response()->json($response_data);
    }

    public function managerCompleteList()
    {
        $user = auth()->user();
        $bk =   Schedule::with('equipment.product')
                ->whereScheduleStatus(2)
                ->whereManagerId($user->id)
                ->whereEngineerStatus(2)
                ->whereModuleType(2)
                ->orderBy('created_at', 'desc')
                ->get();
        $response_data = ["success" => 1, 'data' => $bk];
        return response()->json($response_data);
    }
    
    public function spectatorScheduleList()
    {
        $bk =   Schedule::with('equipment.product','notify')
                ->whereEngineerStatus(0)
                ->whereModuleType(2)
                ->orderBy('created_at', 'desc')
                ->get();
        $response_data = ["success" => 1, 'data' => $bk];
        return response()->json($response_data);
    }

    public function spectatorPendingList()
    {
        $bk =   Schedule::with('equipment.product')
                ->whereScheduleStatus(1)
                ->whereEngineerStatus(1)
                ->whereModuleType(2)
                ->orderBy('created_at', 'desc')
                ->get();

        $response_data = ["success" => 1, 'data' => $bk];
        return response()->json($response_data);
    }

    public function spectatorCompleteList()
    {
        $bk =   Schedule::with('equipment.product')
                ->whereScheduleStatus(2)
                ->whereEngineerStatus(2)
                ->whereModuleType(2)
                ->orderBy('created_at', 'desc')
                ->get();
        $response_data = ["success" => 1, 'data' => $bk];
        return response()->json($response_data);
    }

    public function commentRegs(Request $request)
    {
        //Log::info($request->all());
        $validator = Validator::make($request->all(),
            [
              'comment'       => 'nullable',
              'schedule_id'   => 'required|numeric|exists:schedules,id,active,1,deleted_at,NULL',
              'status'        => 'required',
              'device_type'   => 'required|exists:device_types,code,mobile_type,1,active,1,deleted_at,NULL'
            ]);

        if(!$validator->fails()){
            $record = Schedule::find($request->schedule_id);
            $record->engineer_comment = $request->comment;
            $record->engineer_status = $request->status;
            if($request->status ==1)
            {
                $record->schedule_status = 1;
            }else{
                $record->schedule_status = 2;
            }
            if ($record->save()) { 
                
                $data = BreakdownModule::whereScheduleId($request->schedule_id)->first();
                $data->engineer_status =  $request->status;
                $data->save();
                if($request->status ==1 )
                {
                    $user = auth()->user();
                    $record_notify1  = Notification::whereScheduleId($request->schedule_id)->whereType(2)->delete();
                    $record_notify2  = Notification::whereScheduleId($request->schedule_id)->whereType(4)->delete();
                    $notify = new Notification();
                    $notify->schedule_id        = $request->schedule_id;
                    $notify->user_id            = $data->user_id;
                    $notify->module_type        = 2;
                    $notify->type               = 3;
                    $notify->created_by         = $user->id;
                    $notify->updated_by         = $user->id;
                    $notify->save();
                    $check = User::whereId($data->user_id)->first();
                    if(!empty($check->device_token))
                    {
                      event(new EventCreationEvent($notify, Auth::id()));
                    }
                    $response_data = ["success" => 1,"message" => __("validation.success",['attr'=>'Breakdown rejected']), "data" => $record];   
                }else{

                    $record_notify  = Notification::whereScheduleId($request->schedule_id)->delete();
                    $response_data = ["success" => 1,"message" => __("validation.success",['attr'=>'Breakdown approved']), "data" => $record]; 

                }    

            } else {
               $response_data = ["success" => 0,  "message" => __("site.invalid_login")];
            }

        }else{
            $response_data = ["success" => 0,  "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        return response()->json($response_data);
    }

    public function registers(Request $request)
    {
        //Log::info($request->all());
        $validator = Validator::make($request->all(),
            [
              'product_type'        => 'required|numeric|exists:product_types,id,active,1,deleted_at,NULL',
              'schedule_id'         => 'required|numeric|exists:schedules,id,active,1,deleted_at,NULL',
              'engineer_id'         => 'required|numeric|exists:users,id,active,1,deleted_at,NULL',
              'manager_id'          => 'required|numeric|exists:users,id,active,1,deleted_at,NULL',
              'failure_date'        => 'required',
              'failure_time'        => 'required',
              'report_date'         => 'required',
              'report_time'         => 'required',
              'start_date'          => 'required',
              'start_time'          => 'required',
              'end_date'            => 'required',
              'end_time'            => 'required',
              'request_period'      => 'required',
              'waiting_period'      => 'required',
              'maintenance_period'  => 'required',
              'total_downtime'      => 'required',
              'root_cause'          => 'required',
              'downtime_minute'     => 'required',
              'problem.*'           => 'nullable',
              'problem_image.*'     => 'nullable',
              'action.*'            => 'nullable',
              'action_image.*'      => 'nullable',
              'prevention.*'        => 'nullable',
              'prevention_image.*'  => 'nullable',
              'spare_title'         => 'nullable',
              'spare_name'          => 'nullable',
              'spare_image.*'       => 'nullable'
            ]);
        if(!$validator->fails()){
            $user = auth()->user();
            $date = date('Y-m-d H:i:s');
            $filePath = "images/breakdown/";
            $filePath2 = "images/spare/";
            
            $data = new BreakdownModule;
            $data->product_type           = $request->product_type;
            $data->user_id                = $user->id;
            $data->manager_id             = $request->manager_id;
            $data->engineer_id            = $request->engineer_id;
            $data->schedule_id            = $request->schedule_id;
            $data->date                   = $date;
            $data->failure_date           = $request->failure_date;
            $data->failure_time           = $request->failure_time;
            $data->report_date            = $request->report_date;
            $data->report_time            = $request->report_time;
            $data->start_date             = $request->start_date;
            $data->start_time             = $request->start_time;
            $data->end_date               = $request->end_date;
            $data->end_time               = $request->end_time;
            $data->request_period         = $request->request_period;
            $data->waiting_period         = $request->waiting_period;
            $data->maintenance_period     = $request->maintenance_period;
            $data->total_downtime         = $request->total_downtime;
            $data->root_cause             = $request->root_cause;
            $data->downtime_minute        = $request->downtime_minute;
            $data->active                 = 1;
            $data->created_by             = $user->id;
            $data->updated_by             = $user->id;
            $data->save();
            if(!empty($request->problem))
                {
                    foreach($request->problem as $key => $problem)
                    {
                        $data1 = new BreakdownDetail();
                        $data1->breakdown_id           = $data->id;
                        $data1->problem                = $problem;
                        $data1->active                 = 1;
                        $data1->type                   = 1;
                        if(!empty($request->problem_image[$key] ))
                        {
                            $file = [];
                            foreach( $request->problem_image[$key] as $k=> $image){
                                // $file1 = $image->store($filePath);
                                // $file[] = url("storage/".$file1);
                                   
                                    $file1 = $image;
                                    $extension = $file1->getClientOriginalExtension();
                                    $filename = time() . $k .'.'. $extension;
                                    $file1->move('public/storage', $filename);
                                    $file[] = url('/public/storage/'.$filename);
                            }
                            $data1->problem_image   = json_encode($file);
                        }
                        $data1->created_by     = $user->id;
                        $data1->updated_by     = $user->id;
                        $data1->save();
                    }
                }

                if(!empty($request->action))
                {
                    foreach($request->action as $key1 => $action)
                    {
                        $data2 = new BreakdownDetail();
                        $data2->breakdown_id         = $data->id;
                        $data2->action               = $action;
                        $data2->active               = 1;
                        $data2->type                 = 2;
                        if(!empty($request->action_image[$key1]))
                        {
                            $file2 = [];
                            foreach( $request->action_image[$key1] as $k2=> $image){
                                // $action_image = $image->store($filePath);
                                // $file2[] = url("storage/".$action_image);
                                   
                                    $action_image = $image;
                                    $extension = $action_image->getClientOriginalExtension();
                                    $filename = time() . $k2.$k2 .'.'. $extension;
                                    $action_image->move('public/storage', $filename);
                                    $file2[] = url('/public/storage/'.$filename);
                            }
                            $data2->action_image   = json_encode($file2);
                        }
                        $data2->created_by     = $user->id;
                        $data2->updated_by     = $user->id;
                        $data2->save();
                    }
                }

                if(!empty($request->prevention))
                {
                    foreach($request->prevention as $key2 => $prevention)
                    {
                        $data3 = new BreakdownDetail();
                        $data3->breakdown_id           = $data->id;
                        $data3->prevention             = $prevention;
                        $data3->active                 = 1;
                        $data3->type                   = 3;
                        if(!empty($request->prevention_image[$key2]))
                        {
                            $file3 = [];
                            foreach( $request->prevention_image[$key2]  as $k3=> $image){
                                // $prevention_image = $image->store($filePath);
                                // $file3[] = url("storage/".$prevention_image);
                                   
                                    $prevention_image = $image;
                                    $extension = $prevention_image->getClientOriginalExtension();
                                    $filename = time() .$k3.$k3 .$k3. '.' . $extension;
                                    $prevention_image->move('public/storage', $filename);
                                    $file3[] = url('/public/storage/'.$filename);
                            }
                            $data3->prevention_image   = json_encode($file3);
                        }
                        $data3->created_by     = $user->id;
                        $data3->updated_by     = $user->id;
                        $data3->save();
                    }
                }

                if($request->spare_title != "" && $request->spare_name != "")
                {
                    $spare = new Inventory();
                    $spare->product_type           = $request->product_type;
                    $spare->schedule_id            = $request->schedule_id;
                    $spare->breakdown_id           = $data->id;
                    $spare->user_id                = $user->id;
                    $spare->date                   = $date;
                    $spare->title                  = $request->spare_title;
                    $spare->name                   = $request->spare_name;

                    if(!empty($request->spare_image))
                    {
                        $spare_file = [];
                        foreach( $request->spare_image as $k4=> $image){
                            // $spare_image = $image->store($filePath2);
                            // $spare_file[] = url("storage/".$spare_image);
                            $spare_image = $image;
                                    $extension = $spare_image->getClientOriginalExtension();
                                    $filename = time() .$k4.$k4.$k4.$k4. '.'. $extension;
                                    $spare_image->move('public/storage', $filename);
                                    $spare_file[] = url('/public/storage/'.$filename);
                        }
                        $spare->file   = json_encode($spare_file);
                    }
                    
                    $spare_check = InventoryStock::whereName($request->spare_name)->first();
                    if($spare_check){
                        if($spare_check->stock > 0 )
                        {
                            $spare->active      = 1;
                        }else{
                            $spare->active      = 0;
                        }
                    }else{
                        $spare->active      = 0;
                    }
                    
                    $spare->created_by             = $user->id;
                    $spare->updated_by             = $user->id;
                    $spare->save();
                }
                if($data->id !="")
                {
                    $schedule = Schedule::find($request->schedule_id);
                    $schedule->schedule_status = 1;
                    $schedule->save();
                    
                    $notification =[];
                    if(!empty($request->engineer_id))
                    {
                        $notification[] = $request->engineer_id;
                    }
                    if(!empty($request->manager_id))
                    {
                        $notification[] = $request->manager_id;
                    }
                    $spectator = User::whereUserType(4)->whereActive(1)->get();
                        if($spectator)
                        {
                            foreach($spectator as $spec)
                            {
                                $notification[] = $spec->id;
                            }
                        }
                    $record_notify  = Notification::whereScheduleId($request->schedule_id)->whereType(1)->delete();
                    foreach($notification as $notify_record)
                    {
                        $notify = new Notification();
                        $notify->schedule_id        = $request->schedule_id;
                        $notify->user_id            = $notify_record;
                        $notify->module_type        = 2;
                        $notify->type               = 2;
                        $notify->created_by         = $user->id;
                        $notify->updated_by         = $user->id;
                        $notify->save();
                        $check = User::whereId($notify_record)->first();
                        if(!empty($check->device_token))
                        {
                          event(new EventCreationEvent($notify, Auth::id()));
                        }
                    }
                }
            $response_data = ["success" => 1, "message" => __("validation.create_success",['attr'=>'Breakdown'])];
        }else{
            //
            //Log::info($validator->errors()->toArray());
            $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        return response()->json($response_data);
    }


    public function update(Request $request)
    {
        
        $validator = Validator::make($request->all(),
            [
              'data'                => 'required|numeric|exists:breakdown_modules,id,active,1,deleted_at,NULL',
              'product_type'        => 'required|numeric|exists:product_types,id,active,1,deleted_at,NULL',
              'schedule_id'         => 'required|numeric|exists:schedules,id,active,1,deleted_at,NULL',
              'failure_date'        => 'required',
              'failure_time'        => 'required',
              'report_date'         => 'required',
              'report_time'         => 'required',
              'start_date'          => 'required',
              'start_time'          => 'required',
              'end_date'            => 'required',
              'end_time'            => 'required',
              'request_period'      => 'required',
              'waiting_period'      => 'required',
              'maintenance_period'  => 'required',
              'total_downtime'      => 'required',
              'downtime_minute'     => 'required',
              'root_cause'          => 'required',
              'problem.*'           => 'nullable',
              'problem_image.*'     => 'nullable',
              'action.*'            => 'nullable',
              'action_image.*'      => 'nullable',
              'prevention.*'        => 'nullable',
              'prevention_image.*'  => 'nullable',
              'spare_title'         => 'nullable',
              'spare_name'          => 'nullable',
              'spare_image.*'       => 'nullable'
            ]);
        if(!$validator->fails()){

            $user = auth()->user();
            $date = date('Y-m-d H:i:s');
            $filePath = "images/breakdown/";
            $filePath2 = "images/spare/";

            $bk = BreakdownModule::find($request->data);
            $bk->product_type           = $request->product_type;
            $bk->user_id                = $user->id;
            $bk->schedule_id            = $request->schedule_id;
            $bk->date                   = $date;
            $bk->failure_date           = $request->failure_date;
            $bk->failure_time           = $request->failure_time;
            $bk->report_date            = $request->report_date;
            $bk->report_time            = $request->report_time;
            $bk->start_date             = $request->start_date;
            $bk->start_time             = $request->start_time;
            $bk->end_date               = $request->end_date;
            $bk->end_time               = $request->end_time;
            $bk->request_period         = $request->request_period;
            $bk->waiting_period         = $request->waiting_period;
            $bk->maintenance_period     = $request->maintenance_period;
            $bk->total_downtime         = $request->total_downtime;
            $bk->downtime_minute        = $request->downtime_minute;
            $bk->root_cause             = $request->root_cause;
            $bk->active                 = 1;
            $bk->updated_by             = $user->id;
            //$bk->save();

             if($bk->save())
             {
                $detail = BreakdownDetail::whereBreakdownId($request->data); 
                $detail->delete();
                    if(!empty($request->problem))
                    {
                        foreach($request->problem as $key => $problem)
                        {
                            $data1 = new BreakdownDetail();
                            $data1->breakdown_id           = $bk->id;
                            $data1->problem                = $problem;
                            $data1->active                 = 1;
                            $data1->type                   = 1;
                            if(!empty($request->problem_image[$key]))
                            {
                                $file = [];
                                foreach( $request->problem_image[$key] as $image){
                                    $file1 = $image->store($filePath);
                                    $file[] = url("storage/".$file1);
                                }
                                $data1->problem_image   = json_encode($file);
                            }else{
                                $data1->problem_image   = NULL;
                            }
                            $data1->created_by     = $user->id;
                            $data1->updated_by     = $user->id;
                            $data1->save();
                        }
                    }

                    if(!empty($request->action))
                    {
                        foreach($request->action as $key1 => $action)
                        {
                            $data2 = new BreakdownDetail();
                            $data2->breakdown_id         = $bk->id;
                            $data2->action               = $action;
                            $data2->active               = 1;
                            $data2->type                 = 2;
                            if(!empty($request->action_image[$key1]))
                            {
                                $file2 = [];
                                foreach( $request->action_image[$key1] as $image){
                                    $action_image = $image->store($filePath);
                                    $file2[] = url("storage/".$action_image);
                                }
                                $data2->action_image   = json_encode($file2);
                            }
                            else{
                                $data2->action_image   = NULL;
                            }
                            $data2->created_by     = $user->id;
                            $data2->updated_by     = $user->id;
                            $data2->save();
                        }
                    }

                    if(!empty($request->prevention))
                    {
                        foreach($request->prevention as $key2 => $prevention)
                        {
                            $data3 = new BreakdownDetail();
                            $data3->breakdown_id           = $bk->id;
                            $data3->prevention             = $prevention;
                            $data3->active                 = 1;
                            $data3->type                   = 3;
                            if(!empty($request->prevention_image[$key2]))
                            {
                                $file3 = [];
                                foreach( $request->prevention_image[$key2] as $image){
                                    $prevention_image = $image->store($filePath);
                                    $file3[] = url("storage/".$prevention_image);
                                }
                                $data3->prevention_image   = json_encode($file3);
                            }else{
                                $data3->prevention_image   = NULL;
                            }
                            $data3->created_by     = $user->id;
                            $data3->updated_by     = $user->id;
                            $data3->save();
                        }
                    }

                    if($request->spare_title != "" && $request->spare_name != "")
                    {
                        $spare = Inventory::whereBreakdownId($bk->id)->first();
                        $spare->date                   = $date;
                        $spare->title                  = $request->spare_title;
                        $spare->name                   = $request->spare_name;
                        if(!empty($request->spare_image))
                        {
                            $spare_file = [];
                            foreach( $request->spare_image as $image){
                                $spare_image = $image->store($filePath2);
                                $spare_file[] = url("storage/".$spare_image);
                            }
                            $spare->file   = json_encode($spare_file);
                        }else{
                            $spare->file   = NULL;
                        }
                        $spare->updated_by             = $user->id;
                        $spare->save();
                    }
                    if($bk->id !="")
                    {
                        $schedule = Schedule::find($request->schedule_id);
                        $schedule->schedule_status = 1;
                        $schedule->save();

                        $notification =[];
                        if(!empty($bk->engineer_id))
                        {
                            $notification[] = $bk->engineer_id;
                        }
                        if(!empty($bk->manager_id))
                        {
                            $notification[] = $bk->manager_id;
                        }
                        
                        $spectator = User::whereUserType(4)->whereActive(1)->get();
                        if($spectator)
                        {
                            foreach($spectator as $spec)
                            {
                                $notification[] = $spec->id;
                            }
                        }
                        
                        $record_notify1  = Notification::whereScheduleId($request->schedule_id)->whereType(3)->delete();
                        $record_notify2  = Notification::whereScheduleId($request->schedule_id)->whereType(4)->delete();
                        foreach($notification as $notify_record)
                        {
                            $notify = new Notification();
                            $notify->schedule_id = $request->schedule_id;
                            $notify->user_id = $notify_record;
                            $notify->module_type = 2;
                            $notify->type = 4;
                            $notify->created_by       = $user->id;
                            $notify->updated_by       = $user->id;
                            $notify->save();
                            $check = User::whereId($notify_record)->first();
                            if(!empty($check->device_token))
                            {
                              event(new EventCreationEvent($notify, Auth::id()));
                            }
                        }
                    }
            }
            //Log::info($notification);
            $response_data = ["success" => 1, "message" => __("validation.update_success",['attr'=>'Breakdown'])];
        }else{
            
            $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        return response()->json($response_data);
    }

    // public function breakdownList()
    // {
    //     $user = auth()->user();
    //     $bk =   BreakdownModule::whereUserId($user->id)->get();
    //     $response_data = ["success" => 1, 'data' => $bk];
    //     return response()->json($response_data);
    // }

}
