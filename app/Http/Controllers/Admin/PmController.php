<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Validator;
use App\User;
use App\BreakdownModule;
use App\BreakdownDetail;
use App\ProductJudge;
use App\Inventory;
use App\PmModule;
use App\PmDetail;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Exports\PMExport;
use App\Exports\IndividualPMExport;
use Maatwebsite\Excel\Facades\Excel;
class PmController extends Controller
{
    
    public function index()
    {
        $response_data["title"] = __("title.private.pm");
        return view("admin.pm.list")->with($response_data);
    }

    public function getList(Request $request)
    {
        $query = PmModule::with(['equipment.product','schedule','users','engineer'])->select();
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
                $response_data["title"] = __("title.private.pm_detail");
                $response_data["key"] = $key;
                $pm = PmModule::find($userId);
                if($pm){
                        $pm = PmModule::with(['equipment.product','schedule','users'])->whereId($pm->id)->first();
                        $response_data["pm"] = $pm;
                        if($pm){
                                $response_data['detail']      = PmDetail::with(['point','items','judge'])->wherePmId($pm->id)->whereActive(1)->get();
                                $response_data['judge']       = ProductJudge::whereProductId($pm->equipment->product->id)->get();
                            }
                            return view("admin.pm.view")->with($response_data);
                    }
                    else
                    {
                        return redirect(route("private.pm"));
                    }
            } catch (DecryptException $e) {
                return redirect(route("private.pm"));
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
        $file = "Daily-PM-".$current.".xlsx";
        Excel::store(new PMExport($request), $file,'local');
        $downloadUrl =url('/')."/storage/app/".$file;
        $response_data = ["success" => 1,"message" => __("validation.create_success",['attr'=>'Daily PM Report Export']),"data"=> $downloadUrl];
        return response()->json($response_data);
    } 

    public function individualDataExports(Request $request)
    {
        $current = strtotime(now());
        $file = "Daily-PM-Individual-".$current.".xlsx";
        Excel::store(new IndividualPMExport($request), $file);
        $downloadUrl =url('/')."/storage/".$file;
        $response_data = ["success" => 1,"message" => __("validation.create_success",['attr'=>'Daily PM Individual Export']),"data"=> $downloadUrl];
        return response()->json($response_data);
    }

}
