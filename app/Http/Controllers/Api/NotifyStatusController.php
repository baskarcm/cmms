<?php

namespace App\Http\Controllers\Api;
use Auth;
use App\User;
use App\Schedule;
use App\ModuleType;
use App\BreakdownModule;
use App\ProductType;
use App\Inventory;
use App\BreakdownDetail;
use App\NotifyStatus;
use App\Notification;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use DB;

class NotifyStatusController extends Controller
{
    public function readStatus(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
              'data'       => 'required|numeric|exists:notify_statuses,id,active,1,deleted_at,NULL',
              'device_type'=> 'required|exists:device_types,code,mobile_type,1,active,1,deleted_at,NULL',
              'user_status'     => 'required|numeric'
            ]);
        if(!$validator->fails()){
                $read = NotifyStatus::find($request->data);
                if($request->user_status == 1)
                {
                    $read->user_status = 1;
                }elseif($request->user_status == 2)
                {
                    $read->engineer_status = 1;
                }elseif($request->user_status == 3)
                {
                    $read->manager_status = 1;
                }
            if ($read->save()) {
                $response_data = ["success" => 1,"message" => __("validation.update_success",['attr'=>'read status'])];

            } else {
               $response_data = ["success" => 0,  "message" => __("site.invalid_login")];
            }

        }else{
            $response_data = ["success" => 0,  "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        return response()->json($response_data);
    }

    public function userCount(Request $request)
    {
        $user = auth()->user();
        $pm_schedule    = Schedule::whereUserId($user->id)->whereModuleType(1)->whereScheduleStatus(0)->get();
        $pm_pending     = Schedule::whereUserId($user->id)->whereModuleType(1)->whereScheduleStatus(1)->get();
        $pm_complete    = Schedule::whereUserId($user->id)->whereModuleType(1)->whereScheduleStatus(2)->get();
        $bk_schedule    = Schedule::whereUserId($user->id)->whereModuleType(2)->whereScheduleStatus(0)->get();
        $bk_pending     = Schedule::whereUserId($user->id)->whereModuleType(2)->whereScheduleStatus(1)->get();
        $bk_complete    = Schedule::whereUserId($user->id)->whereModuleType(2)->whereScheduleStatus(2)->get();
        $notify         = Notification::whereUserId($user->id)->whereStatus(0)->get();
        $record['pm_schedule']  = count($pm_schedule);
        $record['pm_pending']   = count($pm_pending);
        $record['pm_complete']   = count($pm_complete);
        $record['bk_schedule']  = count($bk_schedule);
        $record['bk_pending']   = count($bk_pending);
        $record['bk_complete']   = count($bk_complete);
        $record['notify']       = count($notify);
        $response_data = ["success" => 1, 'data' => $record];
        return response()->json($response_data);
    }

    public function engineerCount()
    {
        $user = auth()->user();
        $pm_schedule    = Schedule::whereEngineerId($user->id)->whereModuleType(1)->whereBetween("schedule_status",[0,1])->whereEngineerStatus(0)->get();
        $pm_pending     = Schedule::whereEngineerId($user->id)->whereModuleType(1)->whereScheduleStatus(1)->whereEngineerStatus(1)->get();
        $pm_complete    = Schedule::whereEngineerId($user->id)->whereModuleType(1)->whereScheduleStatus(2)->whereEngineerStatus(2)->get();
        $bk_schedule    = Schedule::whereEngineerId($user->id)->whereModuleType(2)->whereBetween("schedule_status",[0,1])->whereEngineerStatus(0)->get();
        $bk_pending     = Schedule::whereEngineerId($user->id)->whereModuleType(2)->whereScheduleStatus(1)->whereEngineerStatus(1)->get();
        $bk_complete    = Schedule::whereEngineerId($user->id)->whereModuleType(2)->whereScheduleStatus(2)->whereEngineerStatus(2)->get();
        $notify         = Notification::whereUserId($user->id)->whereStatus(0)->get();
        $record['pm_schedule']  = count($pm_schedule);
        $record['pm_pending']   = count($pm_pending);
        $record['pm_complete']  = count($pm_complete);
        $record['bk_schedule']  = count($bk_schedule);
        $record['bk_pending']   = count($bk_pending);
        $record['bk_complete']  = count($bk_complete);
        $record['notify']       = count($notify);
        $response_data = ["success" => 1, 'data' => $record];
        return response()->json($response_data);
    }
    
    public function managerCount()
    {
        $user = auth()->user();
        $pm_schedule    = Schedule::whereManagerId($user->id)->whereModuleType(1)->whereBetween("schedule_status",[0,1])->whereEngineerStatus(0)->get();
        $pm_pending     = Schedule::whereManagerId($user->id)->whereModuleType(1)->whereScheduleStatus(1)->whereEngineerStatus(1)->get();
        $pm_complete    = Schedule::whereManagerId($user->id)->whereModuleType(1)->whereScheduleStatus(2)->whereEngineerStatus(2)->get();
        $bk_schedule    = Schedule::whereManagerId($user->id)->whereModuleType(2)->whereBetween("schedule_status",[0,1])->whereEngineerStatus(0)->get();
        $bk_pending     = Schedule::whereManagerId($user->id)->whereModuleType(2)->whereScheduleStatus(1)->whereEngineerStatus(1)->get();
        $bk_complete    = Schedule::whereManagerId($user->id)->whereModuleType(2)->whereScheduleStatus(2)->whereEngineerStatus(2)->get();
        $notify         = Notification::whereUserId($user->id)->whereStatus(0)->get();
        $record['pm_schedule']  = count($pm_schedule);
        $record['pm_pending']   = count($pm_pending);
        $record['pm_complete']  = count($pm_complete);
        $record['bk_schedule']  = count($bk_schedule);
        $record['bk_pending']   = count($bk_pending);
        $record['bk_complete']  = count($bk_complete);
        $record['notify']       = count($notify);
        $response_data = ["success" => 1, 'data' => $record];
        return response()->json($response_data);
    }
    
    public function spectatorCount()
    {
        $user = auth()->user();
        $pm_schedule    = Schedule::whereModuleType(1)->whereBetween("schedule_status",[0,1])->whereEngineerStatus(0)->get();
        $pm_pending     = Schedule::whereModuleType(1)->whereScheduleStatus(1)->whereEngineerStatus(1)->get();
        $pm_complete    = Schedule::whereModuleType(1)->whereScheduleStatus(2)->whereEngineerStatus(2)->get();
        $bk_schedule    = Schedule::whereModuleType(2)->whereBetween("schedule_status",[0,1])->whereEngineerStatus(0)->get();
        $bk_pending     = Schedule::whereModuleType(2)->whereScheduleStatus(1)->whereEngineerStatus(1)->get();
        $bk_complete    = Schedule::whereModuleType(2)->whereScheduleStatus(2)->whereEngineerStatus(2)->get();
        $notify         = Notification::whereUserId($user->id)->whereStatus(0)->get();
        
        $record['pm_schedule']  = count($pm_schedule);
        $record['pm_pending']   = count($pm_pending);
        $record['pm_complete']  = count($pm_complete);
        $record['bk_schedule']  = count($bk_schedule);
        $record['bk_pending']   = count($bk_pending);
        $record['bk_complete']  = count($bk_complete);
        $record['notify']       = count($notify);
        $response_data = ["success" => 1, 'data' => $record];
        return response()->json($response_data);
    }
    
    public function notifyList(Request $request)
    {
        $user = auth()->user();
        $record = [];
        $data    = Notification::whereUserId($user->id)->orderBy('created_at','desc')->get();
        foreach($data as $key => $value)
        {
            $record[$key]['notify'] = $value;
            $record[$key]['schedule'] = Schedule::with('equipment.product','users:id,name','engineer:id,name','manager:id,name')->whereId($value->schedule_id)->first();
        }
        $response_data = ["success" => 1, 'data' => $record];
        return response()->json($response_data);
    }

    public function notifyStatus(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
              'data'       => 'required|numeric|exists:notifications,id,active,1,deleted_at,NULL',
              'device_type'=> 'required|exists:device_types,code,mobile_type,1,active,1,deleted_at,NULL',
            ]);
        if(!$validator->fails()){
            $user = auth()->user();
            $read = Notification::find($request->data);
            $read->status = 1;
            if ($read->save()) {
                $response_data = ["success" => 1,"message" => __("validation.update_success",['attr'=>'notify status'])];
            } else {
               $response_data = ["success" => 0,  "message" => __("site.invalid_login")];
            }
        }else{
            $response_data = ["success" => 0,  "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        return response()->json($response_data);
    }

    public function deviceToken(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
          'token'       => 'required',
          'device_type'=> 'required|exists:device_types,code,mobile_type,1,active,1,deleted_at,NULL',
        ]);
    if(!$validator->fails()){
        $user = auth()->user();
        $data = User::find($user->id);
        $data->device_token = $request->token;
        if ($data->save()) {
            $response_data = ["success" => 1,"message" => __("validation.update_success",['attr'=>'device token'])];
        } else {
           $response_data = ["success" => 0,  "message" => __("site.invalid_login")];
        }
    }else{
        $response_data = ["success" => 0,  "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
    }
    return response()->json($response_data);
    }


}
