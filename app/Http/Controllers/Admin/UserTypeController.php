<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\UserType;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class UserTypeController extends Controller
{
     public function index()
    {
        $response_data["title"] = __("title.private.user_type_list");
        return view("admin.userType.userType")->with($response_data);
    }

    public function getList(Request $request)
    {
        $query = new UserType();
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
                'name'          => 'required|min:'.limit("user_type.min").'|max:'.limit("user_type.max").'|string|not_exists:user_types,name,deleted_at,NULL',
            ]);

        if(!$validator->fails()){
       
            $userType = new UserType();
            $userType->name           = $request->name;
            //$userType->code           = strtolower(str_replace(' ', '', trim($request->name)));
            $userType->active         = 1;
            $userType->created_by     = Auth::id();
            $userType->updated_by     = Auth::id();
            if($userType->save()){
                $response_data = ["success" => 1,"message" => __("validation.create_success",['attr'=>'User Type'])];
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
                    "pk" => "required|exists:user_types,id",
                ]);
        if(!$validator->fails()){
            $userType = UserType::find($request->pk);
            $userType->active = $request->value;
            if($userType->save()){
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
              'data' => 'required|exists:user_types,id,deleted_at,NULL',
            ]);
            
        if (!$validator->fails()) 
        { 
            $typeId = $request->data;
            $userType = UserType::where('id', $typeId)->first();
            if($userType)
            {
                $response_data = ["success" => 1, "data" => $userType];
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
                'data' => 'required|exists:user_types,id,deleted_at,NULL',
                'name' => 'required|min:'.limit("user_type.min").'|max:'.limit("user_type.max").'|string|unique:user_types,name,'.$typeId.',id,deleted_at,NULL',
            ]);

        if(!$validator->fails()){
            
           $userType = UserType::find($typeId);
           $userType->name           = $request->name;
           //$userType->code           = strtolower(str_replace(' ', '', trim($request->name)));
           $userType->updated_by     = Auth::id();

            if($userType->save()){
                $response_data = ["success" => 1,"message" => __("validation.update_success",['attr'=>'User type'])];
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
              'data' => 'required|exists:user_types,id,deleted_at,NULL',
            ]);
            
        if (!$validator->fails()) 
        { 
            $userType = UserType::find($request->data); 
            if($userType->delete()){
                    $response_data =  ['success' => 1, 'message' => __('validation.delete_success',['attr'=>'User type'])]; 
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
