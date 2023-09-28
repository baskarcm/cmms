<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Validator;
use App\User;
use App\BreakdownModule;
use App\BreakdownDetail;
use App\Inventory;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Exports\BreakDownExport;
use App\Exports\IndividualBreakDownExport;
use Maatwebsite\Excel\Facades\Excel;
class BreakdownController extends Controller
{
    
    public function index()
    {
        $response_data["title"] = __("title.private.breakdown");
        return view("admin.breakdown.list")->with($response_data);
    }

    public function getList(Request $request)
    {
        $query = BreakdownModule::with(['equipment.product','schedule','engineer','users'])->select();
        if($request->has("status") && $request->status != ""){
            $query->whereActive($request->status);
        }
        if($request->has("type") && $request->type != ""){
            $query->whereUserType($request->type);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', "{$request->get('start_date')}");
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', "{$request->get('end_date')}");
        }
       return Datatables::of($query->get())->make(true);
    }

    public function view($key, Request $request)
    {
        try {
                $userId = decrypt($key);
                $response_data["title"] = __("title.private.breakdown_detail");
                $response_data["key"] = $key;
                $breakdown = BreakdownModule::find($userId);
                if($breakdown){
                            
                            $breakdown = BreakdownModule::with(['equipment.product','schedule','users'])->whereId($breakdown->id)->first();
                            $response_data["breakdown"] = $breakdown;
                            
                            $response_data['problem']      = BreakdownDetail::select('id','problem','problem_image')->whereBreakdownId($breakdown->id)->whereType(1)->whereActive(1)->get();
                            $response_data['action']       = BreakdownDetail::select('id','action','action_image')->whereBreakdownId($breakdown->id)->whereType(2)->whereActive(1)->get();
                            $response_data['prevention']   = BreakdownDetail::select('id','prevention','prevention_image')->whereBreakdownId($breakdown->id)->whereType(3)->whereActive(1)->get();
                            $response_data['spare']        = Inventory::select('id','date','title','name','file')->whereBreakdownId($breakdown->id)->whereActive(1)->first();
                            
                            return view("admin.breakdown.view")->with($response_data);
                        }
                        else
                        {
                        return redirect(route("private.breakdown"));
                        }
            } catch (DecryptException $e) {
                return redirect(route("private.breakdown"));
        }
    }
    public function updateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
                    "value" => "required|in:0,1",
                    "pk" => "required|exists:users,id",
                ]);
        if(!$validator->fails()){
            $user = User::find($request->pk);
            $user->active = $request->value;
            if($user->save()){
                $response_data = ["success" => 1, "message" => __("validation.update_success", ["attr" => "User Status"])];
            }else{
                $response_data = ["success" => 0, "message" => __("site.server_error")];
            }
        }else{
            $response_data = ["success" =>  0, "message" => __("validation.refresh_page")];
        }
       return response()->json($response_data);
    }

    //User Delete
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
              'data' => 'required|exists:users,id,deleted_at,NULL',
            ]);
            
        if (!$validator->fails()) 
        { 
            $user = User::find($request->data);    
            if($user->delete()){
                $response_data =  ['success' => 1, 'message' => __('validation.delete_success',['attr'=>'User'])]; 
            }else{
                
                $response_data =  ['success' => 1, 'message' => __('site.server_error')]; 
            }

        }else
        {
            $response_data =  ['success' => 0, 'message' => __('validation.refresh_page')];
        }

        return response()->json($response_data);
    }
    
    public function dataExports(Request $request)
    {
        $current = strtotime(now());
        $file = "Daily-Breakdown-".$current.".xlsx";
        Excel::store(new BreakDownExport($request), $file,'local');
        $downloadUrl =url('/')."/storage/app/".$file;
        $response_data = ["success" => 1,"message" => __("validation.create_success",['attr'=>'Daily BreakDown Report Export']),"data"=> $downloadUrl];
        return response()->json($response_data);
    } 


    public function individualDataExports(Request $request)
    {
        $current = strtotime(now());
        $file = "Daily-Breakdown-Individual-".$current.".xlsx";
        Excel::store(new IndividualBreakDownExport($request), $file,'local');
        $downloadUrl =url('/')."/storage/app/".$file;
        $response_data = ["success" => 1,"message" => __("validation.create_success",['attr'=>'Daily BreakDown Individual Export']),"data"=> $downloadUrl];
        return response()->json($response_data);
    }
}
