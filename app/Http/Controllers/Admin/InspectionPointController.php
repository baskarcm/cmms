<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Product;
use App\InspectionPoint;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class InspectionPointController extends Controller
{
    
    public function index()
    {
        $response_data["title"] = __("title.private.inspec_point_list");
        $response_data["products"] = Product::whereActive(1)->get();
        return view("admin.product.inspection_point")->with($response_data);
    }

    public function getList(Request $request)
    {
        $query = InspectionPoint::select();
        if($request->has("product") && $request->product != ""){
            $query->whereProductId($request->product);
        }
        if($request->has("status") && $request->status != ""){
            $query->whereActive($request->status);
        }
        return Datatables::of($query->get())->make(true);
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
                'name'          => 'required|min:'.limit("inspection.min").'|max:'.limit("inspection.max"),
                'product'          => 'required|exists:products,id,deleted_at,NULL',
            ]);

        if(!$validator->fails()){
            $data = new InspectionPoint();
            $data->name           = $request->name;
            $data->product_id        = $request->product;
            $data->name           = $request->name;
            $data->active         = 1;
            $data->created_by     = Auth::id();
            $data->updated_by     = Auth::id();
            if($data->save()){
                $response_data = ["success" => 1,"message" => __("validation.create_success",['attr'=>'Inspection Point'])];
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
                    "pk" => "required|exists:inspection_points,id",
                ]);
        if(!$validator->fails()){
            $data = InspectionPoint::find($request->pk);
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
              'data' => 'required|exists:inspection_points,id,deleted_at,NULL',
            ]);
            
        if (!$validator->fails()) 
        { 
            $typeId = $request->data;
            $data = InspectionPoint::where('id', $typeId)->first();
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
                'data' => 'required|exists:inspection_points,id,deleted_at,NULL',
                'product' => 'required|exists:products,id,deleted_at,NULL',
                'name' => 'required|min:'.limit("inspection.min").'|max:'.limit("inspection.max"),
            ]);

        if(!$validator->fails()){
            
           $data = InspectionPoint::find($typeId);
           $data->name           = $request->name;
           $data->product_id     = $request->product;
           $data->updated_by     = Auth::id();
            if($data->save()){
                $response_data = ["success" => 1,"message" => __("validation.update_success",['attr'=>'Inspection Point'])];
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
              'data' => 'required|exists:inspection_points,id,deleted_at,NULL',
            ]);
            
        if (!$validator->fails()) 
        { 
            $userType = InspectionPoint::find($request->data); 
            if($userType->delete()){
                    $response_data =  ['success' => 1, 'message' => __('validation.delete_success',['attr'=>'Inspection Point'])]; 
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
