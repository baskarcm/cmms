<?php

namespace App\Http\Controllers\Api;
use Auth;
use App\User;
use App\Schedule;
use App\ModuleType;
use App\ProductType;
use App\ProductJudge;
use App\ProductForm;
use App\InspectionIteam;
use App\InspectionPoint;
use App\PmModule;
use App\PmDetail;
use App\NotifyStatus;
use App\Notification;
use Validator;
use App\Events\EventCreationEvent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class PmController extends Controller
{
    public function barcode(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
              'barcode'       => 'required|numeric|exists:product_types,barcode,active,1,deleted_at,NULL',
              'device_type'   => 'required|exists:device_types,code,mobile_type,1,active,1,deleted_at,NULL'
            ]);

        if(!$validator->fails()){

            $user = auth()->user();
            $type = ProductType::whereBarcode($request->barcode)->whereActive(1)->first();
            if($type)
            {
                $Schedule = Schedule::with('equipment.product')->whereProductType($type->id)->whereUserId($user->id)->whereScheduleStatus(0)->whereModuleType(1)->whereActive(1)->first();
                $record['schedule'] = $Schedule;
                $form1 = ProductForm::whereProductType($type->id)->whereLevel(1)->whereActive(1)->get();
                $form2 = ProductForm::whereProductType($type->id)->whereLevel(2)->whereActive(1)->get();

                $pointlist = [];
                $iteamlist = [];
                foreach($form1 as $forms)
                {
                    $point =   InspectionPoint::select('id','name')->whereId($forms->inspec_point)->first();
                    $pointlist[] = $point;
                }

                $record['points'] = $pointlist;

                foreach($form2 as $items)
                {
                    $iteam =   InspectionIteam::select('id','name','inspec_point')->whereId($items->inspec_iteam)->first();
                    $iteamlist[] = $iteam;
                }

                $record['items'] = $iteamlist;
                $record['judge'] = ProductJudge::select('id','name')->whereProductId($type->id)->whereActive(1)->get();
            }
            if ($record) {
                $response_data = ["success" => 1,"message" => __("validation.login_success",['attr'=>'PM Schedule']), "data" => $record];

            } else {
               $response_data = ["success" => 0,  "message" => __("site.invalid_login")];
            }

        }else{
            $response_data = ["success" => 0,  "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        return response()->json($response_data);
    }

    public function getPm(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
              'data'       => 'required|numeric|exists:schedules,id,active,1,deleted_at,NULL',
              'device_type'   => 'required|exists:device_types,code,mobile_type,1,active,1,deleted_at,NULL'
            ]);

        if(!$validator->fails()){
            $user = auth()->user();
            $type = Schedule::with('equipment.product')->whereId($request->data)->whereUserId($user->id)->whereModuleType(1)->whereActive(1)->first();
            //$type = ProductType::whereBarcode($request->barcode)->whereActive(1)->first();
            $record['schedule'] = $type;

            $form1 = ProductForm::whereProductType($type->product_type)->whereLevel(1)->whereActive(1)->get();
            $form2 = ProductForm::whereProductType($type->product_type)->whereLevel(2)->whereActive(1)->get();

            $pointlist = [];
            $iteamlist = [];
            foreach($form1 as $forms)
            {
                //$iteam =   InspectionIteam::whereId($forms->inspec_iteam)->first();
                $point =   InspectionPoint::select('id','name')->whereId($forms->inspec_point)->first();
                $pointlist[] = $point;

            }

            $record['points'] = $pointlist;

            foreach($form2 as $items)
            {
                $iteam =   InspectionIteam::select('id','name','inspec_point')->whereId($items->inspec_iteam)->first();
                $iteamlist[] = $iteam;

            }

            $record['items'] = $iteamlist;

            $record['judge'] = ProductJudge::select('id','name')->whereProductId($type->equipment->product_id)->whereActive(1)->get();

            if ($record) {
                $response_data = ["success" => 1,"message" => __("validation.login_success",['attr'=>'PM Schedule']), "data" => $record];

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
        $pmList =   Schedule::with('equipment.product','notify')
                    ->whereUserId($user->id)
                    ->whereModuleType(1)
                    ->whereScheduleStatus(0)
                    ->orderBy('created_at', 'desc')
                    ->get();
        $response_data = ["success" => 1, 'data' => $pmList];
        return response()->json($response_data);
    }

    public function pendingList()
    {
        $user = auth()->user();
        $bk =   Schedule::with('equipment.product')
                ->whereUserId($user->id)
                ->whereScheduleStatus(1)
                ->whereModuleType(1)
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
                ->whereModuleType(1)
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
                ->whereModuleType(1)
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
                ->whereModuleType(1)
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
                ->whereModuleType(1)
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
                ->whereModuleType(1)
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
                ->whereModuleType(1)
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
                ->whereModuleType(1)
                ->orderBy('created_at', 'desc')
                ->get();
        $response_data = ["success" => 1, 'data' => $bk];
        return response()->json($response_data);
    }
    
     public function spectatorScheduleList()
    {
        $bk =   Schedule::with('equipment.product','notify')
                ->whereEngineerStatus(0)
                ->whereModuleType(1)
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
                ->whereModuleType(1)
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
                ->whereModuleType(1)
                ->orderBy('created_at', 'desc')
                ->get();
        $response_data = ["success" => 1, 'data' => $bk];
        return response()->json($response_data);
    }

    public function commentRegs(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
              'comment'       => 'nullable',
              'schedule_id'   => 'required|numeric|exists:schedules,id,active,1,deleted_at,NULL',
              'status'        => 'required',
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

                $data = PmModule::whereScheduleId($request->schedule_id)->first();
                if($data)
                {  $data->engineer_status = $request->status;
                $data->save();
                }
                if($request->status ==1 )
                {
                    $user = auth()->user();
                    $record_notify1  = Notification::whereScheduleId($request->schedule_id)->whereType(2)->delete();
                    $record_notify2  = Notification::whereScheduleId($request->schedule_id)->whereType(4)->delete();
                    $notify = new Notification();
                    $notify->schedule_id        = $request->schedule_id;
                    $notify->user_id            = $data->user_id;
                    $notify->module_type        = 1;
                    $notify->type               = 3;
                    $notify->created_by         = $user->id;
                    $notify->updated_by         = $user->id;
                    $notify->save();
                    
                    $check = User::whereId($data->user_id)->first();
                    if(!empty($check->device_token))
                    {
                      event(new EventCreationEvent($notify, Auth::id()));
                    }
                    
                    $response_data = ["success" => 1,"message" => __("validation.success",['attr'=>'PM rejected']), "data" => $record];
                }else{
                    $record_notify  = Notification::whereScheduleId($request->schedule_id)->delete();
                    $response_data = ["success" => 1,"message" => __("validation.success",['attr'=>'PM approved']), "data" => $record];

                }
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
            //dd($user->id);
            $record['schedule']     = Schedule::with('equipment.product')->whereId($request->data)->whereUserId($user->id)->whereActive(1)->first();
            $pm                     = PmModule::whereUserId($user->id)->whereScheduleId($request->data)->whereActive(1)->first();
            $record['pm']           = $pm;
            if($pm)
            {
                $record['detail']      = PmDetail::with(['point','items','judge'])->wherePmId($pm->id)->whereActive(1)->get();
                $record['judges']      = ProductJudge::whereProductId($pm->equipment->product->id)->whereActive(1)->get();
            }
            if ($record) {
                $response_data = ["success" => 1,"message" => __("validation.login_success",['attr'=>'PM Schedule list']), "data" => $record];

            } else {
               $response_data = ["success" => 0,  "message" => __("site.invalid_login")];
            }

        }else{
            $response_data = ["success" => 0,  "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        return response()->json($response_data);
    }

    public function engineerList(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
              'data'       => 'required|numeric|exists:schedules,id,active,1,deleted_at,NULL',
              //'device_type'   => 'required|exists:device_types,code,mobile_type,1,active,1,deleted_at,NULL'
            ]);

        if(!$validator->fails()){
            $user = auth()->user();
            $record['schedule']     = Schedule::with('equipment.product')->whereId($request->data)->whereEngineerId($user->id)->whereActive(1)->first();
            $pm              = PmModule::whereEngineerId($user->id)->whereScheduleId($request->data)->whereActive(1)->first();
            $record['pm']    = $pm;
            if($pm)
            {
                $record['detail']      = PmDetail::with(['point','items','judge'])->wherePmId($pm->id)->whereActive(1)->get();
                $record['judges']      = ProductJudge::whereProductId($pm->equipment->product->id)->whereActive(1)->get();
            }
            if ($record) {
                $response_data = ["success" => 1,"message" => __("validation.login_success",['attr'=>'PM Schedule list']), "data" => $record];

            } else {
               $response_data = ["success" => 0,  "message" => __("site.invalid_login")];
            }

        }else{
            $response_data = ["success" => 0,  "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        return response()->json($response_data);
    }

    public function managerList(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
              'data'       => 'required|numeric|exists:schedules,id,active,1,deleted_at,NULL',
              'device_type'   => 'required|exists:device_types,code,mobile_type,1,active,1,deleted_at,NULL'
            ]);

        if(!$validator->fails()){
            $user = auth()->user();
            $record['schedule']     = Schedule::with('equipment.product')->whereId($request->data)->whereManagerId($user->id)->whereActive(1)->first();
            $pm              = PmModule::whereManagerId($user->id)->whereScheduleId($request->data)->whereActive(1)->first();
            $record['pm']    = $pm;
            if($pm)
            {
                $record['detail']      = PmDetail::with(['point','items','judge'])->wherePmId($pm->id)->whereActive(1)->get();
                $record['judges']      = ProductJudge::whereProductId($pm->equipment->product->id)->whereActive(1)->get();
            }
            if ($record) {
                $response_data = ["success" => 1,"message" => __("validation.login_success",['attr'=>'PM Schedule list']), "data" => $record];

            } else {
               $response_data = ["success" => 0,  "message" => __("site.invalid_login")];
            }

        }else{
            $response_data = ["success" => 0,  "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        return response()->json($response_data);
    }
    
    public function spectatorList(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
              'data'       => 'required|numeric|exists:schedules,id,active,1,deleted_at,NULL',
              'device_type'   => 'required|exists:device_types,code,mobile_type,1,active,1,deleted_at,NULL'
            ]);

        if(!$validator->fails()){
            
            $record['schedule']     = Schedule::with('equipment.product')->whereId($request->data)->whereActive(1)->first();
            $pm              = PmModule::whereScheduleId($request->data)->whereActive(1)->first();
            $record['pm']    = $pm;
            if($pm)
            {
                $record['detail']      = PmDetail::with(['point','items','judge'])->wherePmId($pm->id)->whereActive(1)->get();
                $record['judges']      = ProductJudge::whereProductId($pm->equipment->product->id)->whereActive(1)->get();
            }
            if ($record) {
                $response_data = ["success" => 1,"message" => __("validation.login_success",['attr'=>'PM Schedule list']), "data" => $record];

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
        Log::info($request->all());
        $validator = Validator::make($request->all(),
            [
              'product_type'        => 'required|numeric|exists:product_types,id,active,1,deleted_at,NULL',
              'schedule_id'         => 'required|numeric|exists:schedules,id,active,1,deleted_at,NULL',
              'inspec_point.*'      => 'required|numeric|exists:inspection_points,id,active,1,deleted_at,NULL',
              'inspec_item.*'       => 'required|numeric|exists:inspection_iteams,id,active,1,deleted_at,NULL',
              'product_judge.*'     => 'required|numeric|exists:product_judges,id,active,1,deleted_at,NULL',
              'engineer_id'         => 'required|numeric|exists:users,id,active,1,deleted_at,NULL',
              'manager_id'          => 'required|numeric|exists:users,id,active,1,deleted_at,NULL',
              'root_cause'          => 'required',
              'status.*'            => 'nullable',
              'defect_item.*'       => 'nullable',
              'defect_image.*'      => 'nullable',
              'action.*'            => 'nullable',
              'action_image.*'      => 'nullable',
            ]);
        if(!$validator->fails()){
            $filePath = "images/pm/";
            $date = date('Y-m-d H:i:s');
            $point = [];
            $item = [];
            $judge = [];
            $defect = [];
            $status = [];
            $defect_image = [];
            $action = [];
            $action_image = [];

            foreach($request->inspec_point as  $points )
            {
                $point[] = $points;
            }
            foreach($request->inspec_item as  $items )
            {
                $item[] = $items;
            }
            foreach($request->product_judge as  $judges )
            {
                $judge[] = $judges;
            }
            if(!empty($request->status))
            {
                foreach($request->status as $key_status => $data )
                {
                    $status[$key_status] = $data;
                }
            }

            if(!empty($request->defect_item))
            {
                foreach($request->defect_item as $key_defect_item => $defect_items )
                {
                    $defect_item[$key_defect_item] = $defect_items;
                }
            }

            if(!empty($request->defect_image))
            {
                foreach($request->defect_image as $key_defect_img => $defect_images )
                {
                    $defect_image[$key_defect_img] = $defect_images;
                }
            }

            if(!empty($request->action))
            {
                foreach($request->action as $key_action => $actions )
                {
                    $action[$key_action] = $actions;
                }
            }

            if(!empty($request->action_image))
            {
                foreach($request->action_image as  $key_action_img => $action_images )
                {
                    $action_image[$key_action_img] = $action_images;
                }
            }
                $module = new PmModule;
                $user = auth()->user();
                $module->product_type           = $request->product_type;
                $module->user_id                = $user->id;
                $module->engineer_id             = $request->engineer_id;
                $module->manager_id             = $request->manager_id;
                $module->schedule_id            = $request->schedule_id;
                $module->date                   = $date;
                $module->root_cause             = $request->root_cause;
                $module->active                 = 1;
                $module->created_by             = $user->id;
                $module->updated_by             = $user->id;
                if($module->save())
                {
                    foreach($request->inspec_point as  $key => $points )
                    {
                        $data = new PmDetail;
                        $data->pm_id                  = $module->id;
                        $data->inspec_point           = $point[$key];
                        $data->inspec_item            = $item[$key];
                        $data->product_judge          = $judge[$key];

                        if(!empty($status[$key]))
                        {
                            $data->status                 = $status[$key];
                        }

                        if(!empty($defect_item[$key]))
                        {
                            $data->defect_item            = $defect_item[$key];
                        }

                        if(!empty($action[$key]))
                        {
                            $data->action                 = $action[$key];
                        }

                        if(!empty($defect_image[$key]))
                        {
                            $file = [];
                            $i=0;
                            foreach( $defect_image[$key] as $key=> $image){
                                // $file1 = $image->store($filePath);
                                // $file[] = url("storage/".$file1);
                                
                               
                                    $file1 = $image;
                                    $extension = $file1->getClientOriginalExtension();
                                    $filename = time() . $key .'.'. $extension;
                                    $file1->move('public/storage', $filename);
                                    $file[] = url('/public/storage/'.$filename);
                               
                                    $i=$i+1;
                            }
                            $data->defect_image   = json_encode($file);
                        }

                        if(!empty($action_image[$key]))
                        {
                            $action_file = [];
                             
                            foreach( $action_image[$key] as $key=> $image1){
                                // $action_file1 = $image1->store($filePath);
                                // $action_file[] = url("storage/".$action_file1);
                                 $action_file1 = $image1;
                                    $extension = $action_file1->getClientOriginalExtension();
                                    $filename = time() . $key.$key .'.'. $extension;
                                    $action_file1->move('public/storage', $filename);
                                    $action_file[] = url('/public/storage/'.$filename);
                            }
                            $data->action_image   = json_encode($action_file);
                        }

                        $data->active         = 1;
                        $data->created_by     = $user->id;
                        $data->updated_by     = $user->id;
                        $data->save();
                    }

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
                    //Log::info($notification);
                    
                    $record_notify  = Notification::whereScheduleId($request->schedule_id)->whereType(1)->delete();
                    foreach($notification as $notify_record)
                    {
                        $notify = new Notification();
                        $notify->schedule_id        = $request->schedule_id;
                        $notify->user_id            = $notify_record;
                        $notify->module_type        = 1;
                        $notify->type               = 2;
                        $notify->created_by         = $user->id;
                        $notify->updated_by         = $user->id;
                        $notify->save();
                        $check = User::whereId($notify_record)->first();
                        if(!empty($check->device_token))
                        {
                        //   event(new EventCreationEvent($notify, Auth::id()));
                        }
                    }
                }
                //Log::info($notification);
            $response_data = ["success" => 1, "message" => __("validation.create_success",['attr'=>'pm'])];
        }else{

            $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        return response()->json($response_data);
    }

    public function update(Request $request)
    {
        //Log::info($request->all());
        $validator = Validator::make($request->all(),
            [
              'data'                => 'required|numeric|exists:pm_modules,id,active,1,deleted_at,NULL',
              'product_type'        => 'required|numeric|exists:product_types,id,active,1,deleted_at,NULL',
              'schedule_id'         => 'required|numeric|exists:schedules,id,active,1,deleted_at,NULL',
              'inspec_point.*'      => 'required|numeric|exists:inspection_points,id,active,1,deleted_at,NULL',
              'inspec_item.*'       => 'required|numeric|exists:inspection_iteams,id,active,1,deleted_at,NULL',
              'product_judge.*'     => 'required|numeric|exists:product_judges,id,active,1,deleted_at,NULL',
              'engineer_id'         => 'required|numeric|exists:users,id,active,1,deleted_at,NULL',
              'manager_id'          => 'required|numeric|exists:users,id,active,1,deleted_at,NULL',
              'root_cause'          => 'required',
              'status.*'            => 'nullable',
              'defect_item.*'       => 'nullable',
              'defect_image.*'      => 'nullable',
              'action.*'            => 'nullable',
              'action_image.*'      => 'nullable',
            ]);
        if(!$validator->fails()){
            $filePath = "images/pm/";
            $date = date('Y-m-d H:i:s');
            $point = [];
            $item = [];
            $judge = [];
            $defect = [];
            $status = [];
            $defect_image = [];
            $action = [];
            $action_image = [];

            foreach($request->inspec_point as  $points )
            {
                $point[] = $points;
            }
            foreach($request->inspec_item as  $items )
            {
                $item[] = $items;
            }
            foreach($request->product_judge as  $judges )
            {
                $judge[] = $judges;
            }
            if(!empty($request->status))
            {
                foreach($request->status as $key_status => $data )
                {
                    $status[$key_status] = $data;
                }
            }

            if(!empty($request->defect_item))
            {
                foreach($request->defect_item as $key_defect_item => $defect_items )
                {
                    $defect_item[$key_defect_item] = $defect_items;
                }
            }

            if(!empty($request->defect_image))
            {
                foreach($request->defect_image as $key_defect_img => $defect_images )
                {
                    $defect_image[$key_defect_img] = $defect_images;
                }
            }

            if(!empty($request->action))
            {
                foreach($request->action as $key_action => $actions )
                {
                    $action[$key_action] = $actions;
                }
            }

            if(!empty($request->action_image))
            {
                foreach($request->action_image as  $key_action_img => $action_images )
                {
                    $action_image[$key_action_img] = $action_images;
                }
            }
                $module = PmModule::find($request->data);
                $user = auth()->user();
                $module->product_type           = $request->product_type;
                $module->user_id                = $user->id;
                $module->manager_id             = $request->manager_id;
                $module->engineer_id             = $request->engineer_id;
                $module->schedule_id            = $request->schedule_id;
                $module->date                   = $date;
                $module->root_cause             = $request->root_cause;
                $module->updated_by             = $user->id;
                if($module->save())
                {
                    $detail = PmDetail::wherePmId($request->data);
                    if($detail->delete())
                    {
                        foreach($request->inspec_point as  $key => $points )
                        {
                            $data = new PmDetail;
                            $data->pm_id                  = $module->id;
                            $data->inspec_point           = $point[$key];
                            $data->inspec_item            = $item[$key];
                            $data->product_judge          = $judge[$key];

                            if(!empty($status[$key]))
                            {
                                $data->status                 = $status[$key];
                            }

                            if(!empty($defect_item[$key]))
                            {
                                $data->defect_item            = $defect_item[$key];
                            }

                            if(!empty($action[$key]))
                            {
                                $data->action                 = $action[$key];
                            }

                            if(!empty($defect_image[$key]))
                            {
                                $file = [];
                                foreach( $defect_image[$key] as $image){
                                    $file1 = $image->store($filePath);
                                    $file[] = url("storage/".$file1);
                                }
                                $data->defect_image   = json_encode($file);
                            }

                            if(!empty($action_image[$key]))
                            {
                                $action_file = [];
                                foreach( $action_image[$key] as $image1){
                                    $action_file1 = $image1->store($filePath);
                                    $action_file[] = url("storage/".$action_file1);
                                }
                                $data->action_image   = json_encode($action_file);
                            }

                            $data->active         = 1;
                            $data->created_by     = $user->id;
                            $data->updated_by     = $user->id;
                            $data->save();
                        }

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

                        $record_notify1  = Notification::whereScheduleId($request->schedule_id)->whereType(3)->delete();
                        $record_notify2  = Notification::whereScheduleId($request->schedule_id)->whereType(4)->delete();
                        foreach($notification as $notify_record)
                        {
                            $notify = new Notification();
                            $notify->schedule_id = $request->schedule_id;
                            $notify->user_id = $notify_record;
                            $notify->module_type = 1;
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
            $response_data = ["success" => 1, "message" => __("validation.update_success",['attr'=>'pm'])];
        }else{
            //Log::info($validator->errors()->toArray());
            $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        return response()->json($response_data);
    }


 public function test()
 {
     $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => ""];
        
        return response()->json($response_data);
 }
}
