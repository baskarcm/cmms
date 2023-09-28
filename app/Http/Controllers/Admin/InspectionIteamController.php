<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Product;
use App\InspectionIteam;
use App\InspectionPoint;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class InspectionIteamController extends Controller
{
    
    public function index()
    {
        $response_data["title"] = __("title.private.inspec_iteam_list");
        $response_data["products"] = Product::whereActive(1)->get();
        $response_data["inspec_point"] = InspectionPoint::whereActive(1)->get();
        return view("admin.product.inspection_iteams")->with($response_data);
    }

    public function getList(Request $request)
    {
        $query = InspectionIteam::select();
        if($request->has("product") && $request->product != ""){
            $query->whereProductId($request->product);
        }
        if($request->has("point") && $request->point != ""){
            $query->whereInspecPoint($request->point);
        }
        if($request->has("status") && $request->status != ""){
            $query->whereActive($request->status);
        }
        return Datatables::of($query->get())->make(true);
    }

    public function getPoint(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
              'data' => 'required|exists:products,id,deleted_at,NULL',
            ]);
            
        if (!$validator->fails()) 
        { 
            $typeId = $request->data;
            $data = InspectionPoint::where('product_id', $typeId)->get();
            if($data)
            {
                $response_data = ["success" => 1, "data" => $data];
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $new_request = $request->all();
        //Log::debug($new_request);
        $validator = Validator::make($new_request,
            [
                'name'             => 'required|min:'.limit("inspection.min").'|max:'.limit("inspection.max"),
                'product'          => 'required|exists:products,id,deleted_at,NULL',
                'inspec_point'     => 'required|exists:inspection_points,id,deleted_at,NULL',
            ]);

        if(!$validator->fails()){
            $data = new InspectionIteam();
            $data->name           = $request->name;
            $data->product_id     = $request->product;
            $data->inspec_point   = $request->inspec_point;
            $data->active         = 1;
            $data->created_by     = Auth::id();
            $data->updated_by     = Auth::id();
            if($data->save()){
                $response_data = ["success" => 1,"message" => __("validation.create_success",['attr'=>'Inspection Iteams'])];
            }else{
                $response_data = ["success" => 0, "message" => __("site.server_error")];
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
                    "pk" => "required|exists:inspection_iteams,id",
                ]);
        if(!$validator->fails()){
            $data = InspectionIteam::find($request->pk);
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
              'data' => 'required|exists:inspection_iteams,id,deleted_at,NULL',
            ]);
            
        if (!$validator->fails()) 
        { 
            $typeId = $request->data;
            $data = InspectionIteam::where('id', $typeId)->first();
            if($data)
            {
                $response_data = ["success" => 1, "data" => $data];
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
     * @param  \App\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
    
        $typeId = $request->data;
        $new_request = $request->all();
        $validator = Validator::make($new_request,
            [
                'data' => 'required|exists:inspection_iteams,id,deleted_at,NULL',
                'product' => 'required|exists:products,id,deleted_at,NULL',
                'name' => 'required|min:'.limit("inspection.min").'|max:'.limit("inspection.max"),
            ]);

        if(!$validator->fails()){
            
           $data = InspectionIteam::find($typeId);
           $data->name           = $request->name;
           $data->updated_by     = Auth::id();
            if($data->save()){
                $response_data = ["success" => 1,"message" => __("validation.update_success",['attr'=>'Inspection iteams'])];
            }else{
                $response_data = ["success" => 0, "message" => __("site.server_error")];
            } 
        
        }else{
            $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()];
        }
       
        return response()->json($response_data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
              'data' => 'required|exists:inspection_iteams,id,deleted_at,NULL',
            ]);
            
        if (!$validator->fails()) 
        { 
            $userType = InspectionIteam::find($request->data); 
            if($userType->delete()){
                    $response_data =  ['success' => 1, 'message' => __('validation.delete_success',['attr'=>'Inspection iteams'])]; 
            }else{
                $response_data =  ['success' => 0, 'message' => __('validation.refresh_page')];
            }

        }else
        {
            $response_data =  ['success' => 0, 'message' => __('validation.refresh_page')];
        }

        return response()->json($response_data);
    }
}
