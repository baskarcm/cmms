<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Auth;
use Validator;
use Yajra\Datatables\Datatables;
use App\User;
use App\Gender;
use App\UserType;
use App\Schedule;
use Avatar;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Encryption\DecryptException;

class UserController extends Controller
{
    
    public function index()
    {
        $response_data["title"] = __("title.private.user_list");
        $response_data["genders"] = Gender::whereActive(1)->get();
        $response_data["userTypes"] = UserType::whereActive(1)->get();
        return view("admin.user.list")->with($response_data);
    }

    public function userCount()
    {
        $response_data = User::count();
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
        $validator = Validator::make($new_request,
            [
              'name'        => 'required|min:'.limit("name.min").'|max:'.limit("name.max").'',
              'code'        => 'required|min:'.limit("name.min").'|max:'.limit("name.max").'|not_exists:users,code,deleted_at,NULL',
              'email'       => 'required|email|min:'.limit("email.min").'|max:'.limit("email.max").'|not_exists:users,email',
              'phone'       => 'required|numeric|digits:'.limit("phone.max").'|not_exists:users,phone',
              'password'    => 'required|min:'.limit("password.min").'|max:'.limit("password.max").'|password',
              'gender'   => 'required|exists:genders,id,active,1,deleted_at,NULL',
              'user_type'   => 'required|exists:user_types,id,active,1,deleted_at,NULL'
            ]);

        if(!$validator->fails()){
       
            $user = new User();
            $user->name           = $request->name;
            $user->code           = $request->code;
            $user->email          = $request->email;
            $user->password       = Hash::make($request->password);
            $user->phone          = $request->phone;
            $user->gender         = $request->gender;
            $user->user_type      = $request->user_type;
            $user->active         = 1;
            $user->created_by     = 1;
            $user->updated_by     = 1;
            // $filePath = "images/profile/";
            // $file =  "storage/".$filePath.uniqid().".png";
            // Avatar::create(strtoupper($request->name))->save($file, $quality = 90);
            $user->profile_pic    = "";
            $user->save();

            if($user->save()){
                $response_data = ["success" => 1,"message" => __("validation.create_success",['attr'=>'User'])];
            }else{
                $response_data = ["success" => 0, "message" => __("site.server_error")];
            } 
            
        }else{
            $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()];
        }
        //Log::debug($response_data);
    
        return response()->json($response_data);
    }


    //Blocked User
    public function updateBlock(Request $request)
    {
        $validator = Validator::make($request->all(), [
                    "value" => "required|in:0,1",
                    "pk" => "required|exists:users,id",
                ]);
        //dd($request->all());
        if(!$validator->fails()){
            $user = User::find($request->pk);
            if($request->value == 1)
            {
                $user->blocked_at = now();
            }
            elseif($request->value==0)
            {
                $user->blocked_at =NULL;
            }
            
            if($user->save()){
                $response_data = ["success" => 1, "message" => __("validation.update_success", ["attr" => "User Blocked"])];
            }else{
                $response_data = ["success" => 0, "message" => __("site.server_error")];
            }
        }else{
            $response_data = ["success" =>  0, "message" => __("validation.refresh_page")];
        }
       return response()->json($response_data);
    }



    public function getList(Request $request)
    {
        $query = User::with(['gen','type']);
        if($request->has("status") && $request->status !=""){
            $query->whereActive($request->status);
        }
        if($request->has("userType") && $request->userType !=""){
            $query->whereUserType($request->userType);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', "{$request->get('start_date')}");
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', "{$request->get('end_date')}");
        }
       return Datatables::of($query->get())->make(true);
    }



    public function viewProfile($key, Request $request)
    {

        try {
                $userId = decrypt($key);
                $response_data["title"] = __("title.private.user_profile");
                $response_data["key"] = $key;
                $user = User::find($userId);
                if($user){
                           
                            $response_data["profile"]       = User::with('gen:id,name','type:id,name')->whereId($user->id)->first();
                            return view("admin.user.profile")->with($response_data);
                        }
                        else
                        {
                        return redirect(route("private.users"));
                        }
            } catch (DecryptException $e) {
                return redirect(route("private.users"));
        }
        
    }

     public function edit(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
              'data' => 'required|exists:users,id,deleted_at,NULL',
            ]);
            
        if (!$validator->fails()) 
        { 
            $userId = $request->data;
            $user = User::where('id', $userId)->first();
            if($user)
            {
                $response_data = ["success" => 1,  "data" => $user];
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
        
        $userId = $request->data;
        $new_request = $request->all();
        //Log::debug($new_request);
        $validator = Validator::make($new_request,
            [
              'data'        => 'required|exists:users,id,deleted_at,NULL',
              'name'        => 'required|min:'.limit("name.min").'|max:'.limit("name.max").'',
              'code'        => 'required|min:'.limit("name.min").'|max:'.limit("name.max").'|unique:users,code,'.$userId.',id,deleted_at,NULL',
              'email'       => 'required|email|min:'.limit("email.min").'|max:'.limit("email.max").'|unique:users,email,'.$userId.',id,deleted_at,NULL',
              'phone'       => 'required|numeric|digits:'.limit("phone.max").'|unique:users,phone,'.$userId.',id,deleted_at,NULL',
              'password'    => 'nullable|min:'.limit("password.min").'|max:'.limit("password.max").'|password',
              'gender'      => 'required|exists:genders,id,active,1,deleted_at,NULL',
              'user_type'   => 'required|exists:user_types,id,active,1,deleted_at,NULL'
            ]);

        if(!$validator->fails()){
            
            $User = User::whereId($request->data)->first();
            $User->email          = $request->email;
            $User->code           = $request->code;
            if(!empty($request->password)){
                $User->password   = Hash::make($request->password);
            }
            $User->phone          = $request->phone;
            $User->gender         = $request->gender;
            $User->user_type      = $request->user_type;
            $User->updated_by     = 1;

            if($User->save()){
                $response_data = ["success" => 1,"message" => __("validation.update_success",['attr'=>'User'])];
            }else{
                $response_data = ["success" => 0, "message" => __("site.server_error")];
            } 
        
        }else{
            $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()];
        }
       
        //Log::debug($response_data);
    
        return response()->json($response_data);
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
            $user = User::whereId($request->data)->first();  

            $check = $this->module($user->user_type,$request->data);
            
            if($check == 1)
            {
                $response_data =  ['success' => 0, 'message' => "The employee is allocated schedule"];
            }else
            {
                    if($user->delete()){
                            $response_data =  ['success' => 1, 'message' => __('validation.delete_success',['attr'=>'Employee'])]; 
                    }else{
                        $response_data =  ['success' => 0, 'message' => __('validation.refresh_page')];
                    }
            }

        }else
        {
            $response_data =  ['success' => 0, 'message' => __('validation.refresh_page')];
        }

        return response()->json($response_data);
    }

    public function logout()
    {
    	Auth::logout();
        return redirect(route("login"));
    }

    public function module($user_type,$user_id)
    {
        if($user_type == 1)
            {
                $type = Schedule::whereUserId($user_id)->first();
                if($type)
                {
                    return 1;
                }else
                {
                    return 0;
                }
            }
            elseif($user_type == 2)
            {
                $type = Schedule::whereEngineerId($user_id)->first();
                if($type)
                {
                    return 1;
                }else
                {
                    return 0;
                }
            }
            elseif($user_type == 3)
            {
                $type = Schedule::whereManagerId($user_id)->first();
                if($type)
                {
                    return 1;
                }else
                {
                    return 0;
                }
            }
    }

}
