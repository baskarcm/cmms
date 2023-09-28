<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\UserCollectAnswer;
use App\UserProfile;
use App\Question;
use App\QuestionAnswer;
use App\ProfileCollection;
use Illuminate\Support\Str;
use App\Events\LoginEvent;
use Illuminate\Support\Facades\Hash;
use Avatar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Notifications\UserEmailVerification;

class UserProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(),
            [
              'limit'        => 'nullable|numeric',
              'name'        => 'nullable|string',
            ]);
        if(!$validator->fails()){
            $query = UserProfile::select()->whereActive(1)->whereUserId($user->id);
            if($request->filled("name")){
                $query->search($request->name);
            }
            if($request->limit > 0){
                $data = $query->paginate($request->limit);
            }else{
                $data = $query->get();
            }
            if(count($data)>0)
            {
                $response_data = ["success" => 1, "data" => $data];
            }
            else
            {
                 $response_data = ["success" => 0, "message" => __("validation.no_record",['attr'=>'Personal'])];
            }
        }
        else
        {
            $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
            $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        return response()->json($response_data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(),
            [
                'name'    => 'required|min:'.limit("name.min").'|max:'.limit("name.max"),
                'age'    => 'required|min:'.limit("age.min").'|max:'.limit("age.max").'|numeric',
                'gender'      => 'required|numeric|exists:genders,id,active,1,deleted_at,NULL',
                'height'    => 'required|min:'.limit("height.min").'|max:'.limit("height.max").'|numeric|between:0,99.99',
                'weight'    => 'required|min:'.limit("weight.min").'|max:'.limit("weight.max").'|numeric|between:0,99.99',
                'hair_color'    => 'required|in:0,1',
                'contact'    => 'required|in:0,1',
                'image' => 'required|image|mimes:'.limit("profile_pic.format").'|min:'.limit("profile_pic.min").'|max:'.limit("profile_pic.max").',|dimensions:min_width='.limit("profile_pic.min_width").',min_height='.limit("profile_pic.min_height"),
                'userUploadImg.*' => 'required|image|mimes:'.limit("profile_pic.format").'|min:'.limit("profile_pic.min").'|max:'.limit("profile_pic.max").',|dimensions:min_width='.limit("profile_pic.min_width").',min_height='.limit("profile_pic.min_height"),
                "notes.*"  => 'required|min:'.limit("profile_text.min").'|max:'.limit("profile_text.max").'',
                "image_type.*"  => 'required|numeric|exists:image_types,id,active,1,deleted_at,NULL',
             
            ]);

        if(!$validator->fails()){
            $usercount = UserProfile::whereActive(1)->whereUserId($user->id)->count();
            if($user->active == 1 && $usercount<=3)
            {
                $filePath = "images/profile";
                if($request->hasFile('image')){
                    $file = $request->file('image')->store($filePath);
                    $file = "storage/".$file;
                }else{
                    $file =  "storage/".$filePath.uniqid().".png";
                    Avatar::create(strtoupper($request->name))->save($file, $quality = 90);
                }
                if($usercount!=0)
                {
                    $userType = 1;
                }
                else
                {
                    $userType = 0;
                }
                $profile = new UserProfile();
                $profile->name  = $request->name;
                $profile->gender  = $request->gender;
                $profile->user_id  = $user->id;
                $profile->user_type  = $userType;
                $profile->age  = $request->age;
                $profile->height  = $request->height;
                $profile->weight  = $request->weight;
                $profile->hair_color  = $request->hair_color;
                $profile->contact  = $request->contact;
                $profile->image  = url($file);
                $profile->active            = 1;
                $profile->created_by        = 1;
                $profile->updated_by        = 1;
                if($profile->save()){
                    $insertedId = $profile->id;
                    if($insertedId)
                    {
                        $attachments = [];
                        if($request->userUploadImg > 0)
                        {
                            $i = 0;
                            foreach($request->userUploadImg as $file) 
                            {
                                $attachment                 = []; 
                                $attachment["user_id"]   = $user->id; 
                                $attachment["user_profile_id"]   = $insertedId; 
                                $filePath                   = "images/users/";
                                $path                       = "storage/".$request->file("userUploadImg.".$i)->store($filePath);
                                $attachment["image"]        = $path; 
                                if(!empty($request->notes[$i])){
                                    $attachment["notes"]  = $request->notes[$i]; 
                                }else
                                {
                                    $attachment["notes"] = "";
                                }
                                $attachment["active"]       = 1; 
                                $attachment["image_type"]       = $request->image_type[$i]; 
                                $attachment["created_by"]   = Auth::id(); 
                                $attachment["updated_by"]   = Auth::id(); 
                                $attachments[] = $attachment;
                                $i++;
                            }
                        }
                        if(count($attachments) > 0){
                            $attchementResult = ProfileCollection::insert($attachments);
                        }

                        $response_data = ["success" => 1, "message" => __("validation.create_success",['attr'=>'Personal User'])];
                    }else{
                        $response_data = ["success" => 0, "message" => __("site.server_error")];
                    }
                }else{
                    $response_data = ["success" => 0, "message" => __("site.server_error")];
                }
            
            }else{
                $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
            }
        }
        else
        {
            $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        return response()->json($response_data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\UserProfile  $userProfile
     * @return \Illuminate\Http\Response
     */
    public function show(UserProfile $userProfile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UserProfile  $userProfile
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(),
            [
              'data' => 'required|exists:user_profiles,id,deleted_at,NULL',
            ]);

        if(!$validator->fails()){
            $userProfile = UserProfile::with(['userCollection:id,image,notes,user_id,user_profile_id'])->find($request->data);
            if($userProfile)
            {
                $response_data = ["success" => 1, "message" => __("validation.edit_success",['attr'=>'Personal User']), "data" => $userProfile];
            }else{
                $response_data = ["success" => 0, "message" => __("site.server_error")];
            }
            
        }else{
            $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        return response()->json($response_data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserProfile  $userProfile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(),
            [
                'data' => 'required|exists:user_profiles,id,deleted_at,NULL',
              'name'    => 'required|min:'.limit("name.min").'|max:'.limit("name.max"),
              'age'    => 'required|min:'.limit("age.min").'|max:'.limit("age.max").'|numeric',
              'gender'      => 'required|exists:genders,id,active,1,deleted_at,NULL',
              'height'    => 'required|min:'.limit("height.min").'|max:'.limit("height.max").'|numeric|between:0,99.99',
              'weight'    => 'required|min:'.limit("weight.min").'|max:'.limit("weight.max").'|numeric|between:0,999.99',
              'hair_color'    => 'required|in:0,1',
              'contact'    => 'required|in:0,1',
              'image' => 'required|image|mimes:'.limit("profile_pic.format").'|min:'.limit("profile_pic.min").'|max:'.limit("profile_pic.max").',|dimensions:min_width='.limit("profile_pic.min_width").',min_height='.limit("profile_pic.min_height"),
              'userUploadImg.*' => 'required|image|mimes:'.limit("profile_pic.format").'|min:'.limit("profile_pic.min").'|max:'.limit("profile_pic.max").',|dimensions:min_width='.limit("profile_pic.min_width").',min_height='.limit("profile_pic.min_height"),
                "notes.*"  => 'nullable|min:'.limit("profile_text.min").'|max:'.limit("profile_text.max").'',
                "image_type.*"  => 'required|numeric|exists:image_types,id,active,1,deleted_at,NULL',
            ]);

        if(!$validator->fails()){
            $usercount = UserProfile::whereActive(1)->whereUserId($user->id)->whereUserType(1)->count();
            
            if($user->active == 1 && $usercount<=2)
            {
                $filePath = "images/profile";
                if($request->hasFile('image')){
                    $file = $request->file('image')->store($filePath);
                    $file = "storage/".$file;
                }
                $profile = UserProfile::find($request->data);
                $profile->name  = $request->name;
                $profile->gender  = $request->gender;
                $profile->user_id  = $user->id;
                $profile->age  = $request->age;
                $profile->height  = $request->height;
                $profile->weight  = $request->weight;
                $profile->hair_color  = $request->hair_color;
                $profile->contact  = $request->contact;
                $profile->image  = url($file);
                $profile->active            = 1;
                $profile->updated_by        = 1;
                if($profile->save()){
                    $getProfileCollection = ProfileCollection::select('id')->whereUserProfileId($request->data)->whereActive(1)->get();
                    
                    if($request->userUploadImg > 0)
                    {
                        $i = 0;
                        foreach($request->userUploadImg as $file) 
                        {

                            foreach ($getProfileCollection as $value) {
                                $profileCollection = ProfileCollection::find($value->id);
                                $profileCollection->user_id   = $user->id; 
                                $filePath                   = "images/users/";
                                $savedPath = $request->file("userUploadImg.".$i)->store($filePath);
                                $path                       = "storage/".$savedPath;
                                $profileCollection->image        = $path; 
                                if(!empty($request->notes[$i])){
                                    $profileCollection->notes  = $request->notes[$i]; 
                                }else
                                {
                                    $profileCollection->notes = "";
                                }
                                $profileCollection->active       = 1; 
                                $profileCollection->image_type      = $request->image_type[$i]; 
                                $profileCollection->save();
                            }
                            $i++;
                        }
                    }
                    $response_data = ["success" => 1, "message" => __("validation.update_success",['attr'=>'Personal User'])];
                }else{
                    $response_data = ["success" => 0, "message" => __("site.server_error")];
                }
            }else{
                $response_data = ["success" => 0, "message" => __("site.server_error")];
            }
            
        }else{
            $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        return response()->json($response_data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserProfile  $userProfile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(),
            [
              'data' => 'required|exists:user_profiles,id,deleted_at,NULL',
            ]);

        if(!$validator->fails()){
            $userProfile = UserProfile::find($request->data);
            if($userProfile->delete())
            {
                $attached = ProfileCollection::whereUserProfileId($request->data);
                $attached->delete();
                $question = UserCollectAnswer::whereUserProfileId($request->data);
                $question->delete();
                $response_data = ["success" => 1, "message" => __("validation.delete_success",['attr'=>'Personal User'])];
            }else{
                $response_data = ["success" => 0, "message" => __("site.server_error")];
            }
            
        }else{
            $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        return response()->json($response_data);
    }


    //Get Question And Anwser
    public function getQuestionCollection()
    {

        $user = auth()->user();
        if($user)
        {
            $collection = Question::with(['answer:id,name,question_id'])->whereActive(1)->get();
            if($collection)
            {

                $response_data = ["success" => 1, "message" =>"List of Collection","data" => $collection];
            }else
            {
                $response_data = ["success" => 0, "message" => __("site.server_error")];
            }
        }
        else
        {
            $response_data = ["success" => 0, "message" => __("site.server_error")];
        }
        return response()->json($response_data);
    }
}
