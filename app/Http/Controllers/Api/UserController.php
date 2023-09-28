<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\User;
use App\UserProfile;
use App\SocialToken;
use App\DeviceToken;
use App\PasswordReset;
use Illuminate\Support\Str;
use App\Events\LoginEvent;
use App\Events\SendMail;
use Illuminate\Support\Facades\Hash;
use Avatar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Notifications\UserEmailVerification;
use App\Notifications\UserUpdatePassword;

class UserController extends Controller
{
    /**
     * Handles Registration Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $new_request = $request->all();
        Log::debug($new_request);
        $new_request["gender"] = (int)$request->gender;
        $validator = Validator::make($new_request,
            [
              'firstname'       	=> 'required|min:'.limit("firstname.min").'|max:'.limit("firstname.max").'|valid_name',
              'lastname'        => 'nullable',
              'email'       => 'required|email|min:'.limit("email.min").'|max:'.limit("email.max").'|not_exists:users,email',
              'phone_number'=> 'nullable|numeric|digits:'.limit("phone.max").'|not_exists:users,phone',
              'password'    => 'required|min:'.limit("password.min").'|max:'.limit("password.max").'|password',
              'profile_pic' => 'nullable|image|mimes:'.limit("profile_pic.format").'|min:'.limit("profile_pic.min").'|max:'.limit("profile_pic.max").',|dimensions:min_width='.limit("profile_pic.min_width").',min_height='.limit("profile_pic.min_height"),
              'gender'      => 'required|integer|exists:genders,id,active,1,deleted_at,NULL',
              'device_type'   => 'required|exists:device_types,code,mobile_type,1,active,1,deleted_at,NULL'
            ],
            ["phone_number.not_exists" => "Phone number is already used by another account",
                "email.not_exists" => "Email address is already used by another account"
            ]);

 		if(!$validator->fails()){
            $filePath = "images/profile/";
            if($request->hasFile('profile_pic')){
                $file = $request->file('profile_pic')->store($filePath);
                $file = "storage/".$file;
            }else{
                $file =  "storage/".$filePath.uniqid().".png";
                Avatar::create(strtoupper($request->firstname))->save($file, $quality = 90);
            }

            $otp = strtoupper(str_random(6));
            $token = $this->generateToken($otp);

 			$user = new User();
 			$user->firstname 		   = $request->firstname;
            $user->lastname  = $request->lastname;
 			$user->email 	           = $request->email;
 			$user->password 	       = bcrypt($request->password);
 			$user->phone 	           = $request->filled("phone_number") ? $request->phone_number : "";
 			$user->gender 	         = $request->gender;
 			$user->profile_pic       = url($file);
 			$user->active 	         = 0;
 			$user->created_by        = 1;
 			$user->updated_by        = 1;
            $user->token       = $token;
 			if($user->save()){
                   /* $insertId = $user->id;
                    $profile = new UserProfile();
                    $profile->name  = $request->firstname;
                    $profile->gender  = $request->gender;
                    $profile->user_id  = $insertId;
                    $profile->image  = url($file);
                    $profile->active            = 0;
                    $profile->created_by        = 1;
                    $profile->updated_by        = 1;
                    $profile->save();*/
    	 			$token = $user->createToken('Fashion')->accessToken;
                    $user->notify(new UserEmailVerification($otp));
                    $email=[
                        'user_id'   => $user->id,
                        //'title'     =>'Registration Successfully',
                        'message'   =>'Your account has been Successfully registed.thanks for getting started with fashion app!.'
                    ];
            		event(new SendMail($email));
                //event(new LoginEvent($user, $request->ip(), $request->device_type));
 				$response_data = ["success" => 1, "message" => __("validation.activation_email")];
 			}else{
 				$response_data = ["success" => 0, "message" => __("site.server_error")];
 			}
 			
 		}else{
 			$response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
 		}
        //Log::debug($response_data);
 
        return response()->json($response_data);
    }


    /**
     * Handles Registration Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function socialRegister(Request $request)
    {
        $socialProvider = $request->provider;
        $validator = Validator::make($request->all(),
            [
              'firstname'           => 'required|min:'.limit("firstname.min").'|max:'.limit("firstname.max").'|valid_name',
              'lastname'        => 'required|min:'.limit("lastname.min").'|max:'.limit("lastname.max").'|valid_name',
              'email'       => 'required|email|min:'.limit("email.min").'|max:'.limit("email.max").'|not_exists:users,email',
              'phone_number' => 'nullable|numeric|digits:'.limit("phone.max").'|not_exists:users,phone',
              'profile_pic' => 'nullable|image|mimes:'.limit("profile_pic.format").'|min:'.limit("profile_pic.min").'|max:'.limit("profile_pic.max").',|dimensions:min_width='.limit("profile_pic.min_width").',min_height='.limit("profile_pic.min_height"),
              'gender'      => 'nullable|exists:genders,id,active,1,deleted_at,NULL',
              'code'        => 'required|min:'.limit("social_token.min").'|max:'.limit("social_token.max").'|not_exists:social_tokens,code,active,1,provider,'.$socialProvider.',deleted_at,NULL',
              'provider'    => ['required', Rule::in(config("site.social_providers"))],
              'device_type'   => 'required|exists:device_types,code,mobile_type,1,active,1,deleted_at,NULL'
            ], ["phone_number.not_exists" => "Phone number is already used by another account",
                "email.not_exists" => "Email address is already used by another account"]
        );
        if(!$validator->fails()){
        	$filePath = "images/profile/";
            if($request->hasFile('profile_pic')){
                //$file = $request->file('profile_pic')->store($filePath);
                $file = $request->file('profile_pic')->store($filePath);
                $file = "storage/".$file;
            }else{
                $file =  "storage/".$filePath.uniqid().".png";
                Avatar::create(strtoupper($request->firstname))->save($file, $quality = 90);
            }

            $user = new User();
            $user->firstname      = $request->firstname;
            $user->lastname       = $request->lastname;
            $user->email          = $request->email;
            $user->password       = "";
            $user->phone          = $request->filled("phone_number") ? $request->phone_number : "";
            $user->gender         = $request->gender;
            $user->profile_pic    = url($file);
            $user->active         = 1;
            $user->created_by     = 1;
            $user->updated_by     = 1;


            if($user->save()){
             /* $profile = new UserProfile();
              $profile->name  = $request->firstname;
              $profile->gender  = $request->gender;
              $profile->user_id  = $user->id;
              $profile->image  = url($file);
              $profile->active            = 1;
              $profile->created_by        = 1;
              $profile->updated_by        = 1;
              $profile->save();*/
                $socialToken = new SocialToken();
                $socialToken->user_id = $user->id;
                $socialToken->code = $request->code;
                $socialToken->provider = $request->provider;
                $socialToken->active = 1;
                $socialToken->created_by = 1;
                $socialToken->updated_by = 1;
                if($socialToken->save()){
                    $token = $user->createToken('Fashion')->accessToken;
                    event(new LoginEvent($user, $request->ip(), $request->device_type));
                    $usercount = UserProfile::whereActive(1)->whereUserId($user->id)->count();
                    $response_data = ["success" => 1, "register" => 1, "token" => $token, "data" => $user, "personalUserCount" => $usercount];
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
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendConfirmationEmailOtp(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
              'email'       => 'required|email|exists:users,email,deleted_at,NULL',
            ]);

        if(!$validator->fails()){
            $user = User::whereEmail($request->email)->first();
            if($user->active != 1){
                $otp = strtoupper(str_random(6));
                $token = $this->generateToken($otp);
                $user->token = $token;
                $user->save();
                $user->notify(new UserEmailVerification($otp));
                if($user){
                    $response_data = ["success" => 1, "message" => __("site.otp_emailed"), "otp" => $otp];
                }else{
                    $response_data = ["success" => 0, "message" => __("site.server_error")];
                }
            }else{
                $response_data = ["success" => 1, "message" => __("validation.user_active")];
            }
            
        }else{
            $response_data = ["success" => 0, "message" => __("passwords.user")];
        }
        return response()->json($response_data);
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function checkVerificationToken(Request $request)
    {
        //Token means email opt 
        $validator = Validator::make($request->all(),
            [
              'email'       => 'required|email',
              'token'       => 'required',
            ]);

        if(!$validator->fails()){
            $token = $this->generateToken($request->token);
            $user = User::whereToken($token)->whereEmail($request->email)->first();
            if($user){
            	$user->token   = NULL;
                $user->active   = 1;
            	$user->save();
                $token = $user->createToken('dems')->accessToken;
                $response_data = ["success" => 1, "message" => __("validation.verified_success", ["attr" => "Email"]), "token" => $token, "data" => $user];
                
            }else{
                $response_data = ["success" => 0, "message" => __("validation.invalid_value", ["attr" => "token"])];
            }

        }else{
            $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        
        return response()->json($response_data);
    }

    private function generateToken($value)
    {
        $key = config('app.key');

        if (Str::startsWith($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }
        return hash_hmac('sha256', $value, $key);
    }

    /**
     * Handles Social Login Check
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function socialCheck(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
              'email'       => 'required|email|min:'.limit("email.min").'|max:'.limit("email.max"),
              'code'        => 'required|max:200',
              'provider'    => ['required', Rule::in(config("site.social_providers"))],
              'device_type'   => 'required|exists:device_types,code,mobile_type,1,active,1,deleted_at,NULL'
            ]);
        if(!$validator->fails()){
            $user = User::whereEmail($request->email)->first();
            $social_token = SocialToken::whereCode($request->code)->whereProvider($request->provider)->whereActive(1)->first();
            if($user){
            	if($social_token && $social_token->user_id != $user->id){
            		$response_data = ["success" => 0, "register" => 0, "message" => __("validation.not_exists", ["Attribute" => "Social Code"])];
            	}else{
            		if(!$social_token){
            			if($user->active == 0){
            				$user->active = 1;
            				$user->token = NULL;
            				$user->save();
            			}
            			$socialToken = new SocialToken();
            			$socialToken->user_id = $user->id;
            			$socialToken->code = $request->code;
            			$socialToken->provider = $request->provider;
            			$socialToken->active = 1;
            			$socialToken->created_by = $user->id;
            			$socialToken->updated_by = $user->id;
            			$socialToken->save();
            		}

	                $token = $user->createToken('Fashion')->accessToken;
                    event(new LoginEvent($user, $request->ip(), $request->device_type));
	                  $usercount = UserProfile::whereActive(1)->whereUserId($user->id)->count();
                    $response_data = ["success" => 1, "register" => 1, "token" => $token, "data" => $user, "personalUserCount" => $usercount];
            	}
            }else{
                $response_data = ["success" => 0, "register" => 0, "message" => __("validation.not_found", ["attr" => "User"])];
                /*if($social_token){
                    $response_data = ["success" => 0, "register" => 0, "message" => __("validation.not_exists", ["Attribute" => "Social Code"])];
                }else{
            	}*/
            }
            
        }else{
            $response_data = ["success" => 0, "register" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        
 
        return response()->json($response_data);
    }
 
    /**
     * Handles Login Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
              'email'       => 'required|email',
              'password'   => 'required',
              'device_type'   => 'required|exists:device_types,code,mobile_type,1,active,1,deleted_at,NULL'
            ]);

        if(!$validator->fails()){
            $credentials = [
                'email'     => $request->email,
                'active'    => 1
            ];
            $user = User::whereEmail($credentials)->first();
            if ($user) {
                // if(empty($user->blocked_at)){
                    if (Hash::check($request->password, $user->password)) {
                      // if($user->active == 1){
                          $token = $user->createToken('dems')->accessToken;
                          //event(new LoginEvent($user, $request->ip(), $request->device_type));
                           // $usercount = UserProfile::whereUserId($user->id)->count();
                          $response_data = ["success" => 1,"message" => __("validation.login_success",['attr'=>'User login']),  "token" => $token, "data" => $user];
                      // }else{
                        //     $otp = strtoupper(str_random(6));
                        //     $token = $this->generateToken($otp);
                        //     $user->notify(new UserEmailVerification($otp));
                      //  $response_data = ["success" => 2,  "message" => __("validation.not_verified", ["attr" => "Email address"])];
                      // }
                    } else {
                        $response_data = ["success" => 0,  "message" => __("site.invalid_login")];
                    }
                // }else{
                //     $response_data = ["success" => 0,  "message" => __("validation.account_blocked")];
                // }

            } else {
               $response_data = ["success" => 0,  "message" => __("site.invalid_login")];
            }

        }else{
            $response_data = ["success" => 0,  "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        return response()->json($response_data);
    }
 
    /**
     * Returns Authenticated User Details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        $user = auth()->user();
        $response_data = ["success" => 1, 'data' => auth()->user()];
        return response()->json($response_data);
    }


    /**
     * Returns Authenticated User Details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profileUpdate(Request $request)
    {
    	//Log::debug($request->hasFile("profile_pic"));
        $user = auth()->user();
        $validator = Validator::make($request->all(),
            [
              'name'   => 'required|min:'.limit("name.min").'|max:'.limit("name.max").'|valid_name',
              'email'       => 'nullable|email|min:'.limit("email.min").'|max:'.limit("email.max").'|unique:users,email,'.$user->id.',id',
              'phone' =>'nullable|numeric|digits:'.limit("phone.max").'|unique:users,phone,'.$user->id.',id',
              'profile_pic' => 'nullable|image|mimes:'.limit("profile_pic.format"),
              'gender'      => 'nullable|exists:genders,id,active,1,deleted_at,NULL',
            ], ["phone.not_exists" => "Phone number is already used by another account",
                "email.not_exists" => "Email address is already used by another account"]);
        if(!$validator->fails()){

        // 	$filePath = "images/profile";
            // if($request->hasFile('profile_pic')){
            //     $user->profile_pic = url("storage/".$request->file('profile_pic')->store($filePath));
            // }
            
            // $filePath = "images/profile/";
            // if($request->hasFile('profile_pic')){
            //     $file = $request->file('profile_pic')->store($filePath);
            //     $file = "storage/".$file;
            //     $user->profile_pic = url($file);
            // }
            if ($request->hasfile('profile_pic')) {
                $file = $request->file('profile_pic');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('public/images/profile', $filename);
                $user->profile_pic = url('/public/images/profile/'.$filename);
            }

            $user->name   = $request->name;
            $user->email       = $request->email;
            if(!empty($request->phone))
            {
              $user->phone       = $request->phone;
            }
            if(!empty($request->gender))
            {
              $user->gender       = $request->gender;
            }
            $user->updated_by  = $user->id;

            if($user->save()){
                $response_data = ["success" => 1, "message" => __("validation.update_success", ["attr" => "Profile"])];
            }else{
                $response_data = ["success" => 0, "message" => __("site.server_error")];
            }
            
        }else{
            $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        return response()->json($response_data);
    }

    /**
     * Returns Authenticated User Details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), 
            [
                "current_password"  => "required",
                'password'          => 'required|min:'.limit("password.min").'|max:'.limit("password.max").'|password|different:current_password',
            ]);

        if(!$validator->fails()){
            $user = auth()->user();
            if (Hash::check($request->current_password, $user->password)) {
                $user->password = bcrypt($request->password);
                $user->updated_by = $user->id;
                if($user->save()){
                    $email=[
                        'user_id'   => $user->id,
                        'message'   =>'Your DEMS app account password has been Successfully changed.'
                    ];
                //event(new SendMail($email));
                    // $user->notify(new UserUpdatePassword($user));
                    $response_data = ["success" => 1, "message" => __("validation.update_success", ["attr" => "Password"])];
                }else{
                    $response_data = ["success" => 0, "message" => __("site.server_error")];
                }
            }else{

                $response_data = ["success" => 0,  "message" => __("site.invalid_password")];
            }
        }else{
            $response_data = ["success" => 0,  "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }

        return response()->json($response_data);
    }


    /**
     * Returns Authenticated User Details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fcmUpdate(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(),
            [
              'fcm_code'    => 'required|min:'.limit("fcm_code.min").'|max:'.limit("fcm_code.max"),
              'device_type' => 'required|exists:device_types,id,active,1,deleted_at,NULL',
            ]);

        if(!$validator->fails()){

            $defaultValue = ['user_id' => $user->id, 'code' => $request->fcm_code, 'device_type' => $request->device_type];
            $otherValues = ['created_by' => $user->id, 'updated_by' => $user->id];
            $deviceToken = DeviceToken::updateOrCreate($defaultValue, $otherValues);

            if($deviceToken){
                $response_data = ["success" => 1, "message" => __("validation.update_success", ["attr" => "FCM Code"])];
            }else{
                $response_data = ["success" => 0, "message" => __("site.server_error")];
            }
            
        }else{
            $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        return response()->json($response_data);
    }

        /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), 
            [
                'password'  => 'required|min:'.limit("password.min").'|max:'.limit("password.max").'|password',
            ]);

        if(!$validator->fails()){
            $user = auth()->user();

            $user->password = bcrypt($request->password);
            $user->updated_by = $user->id;
            if($user->save()){
                PasswordReset::whereEmail($user->email)->delete();
               /* $email=[
                        'user_id'   => $user->id,
                        'message'   =>'Your fashion app account password has been Successfully changed.'
                    ];*/
            		//event(new SendMail($email));
                $user->notify(new UserUpdatePassword($user));
                $response_data = ["success" => 1, "message" => __("validation.update_success", ["attr" => "Password"])];
            }else{
                $response_data = ["success" => 0, "message" => __("site.server_error")];
            }

        }else{
            $response_data = ["success" => 0,  "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }

        return response()->json($response_data);
    }


        /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message
     */
    public function logout(Request $request)
    {
        
        $user = auth()->user();
        $user->token()->revoke();

        $response_data = ["success" => 1, "message" => __("site.logout_success")];

        return response()->json($response_data);
    }

    public function userBlock(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
              'block_user' => 'required|numeric',
              'status'  => 'required|in:0,1',
            ]);

        if (!$validator->fails()) 
            {
                $user = auth()->user();
                if($request->block_user != $user->id )
                {
                    if($request->status !=1)
                    {
                        $checking = UserBlock::whereUserId($user->id)->whereBlockUser($request->block_user)->first();
                        if($checking)
                        {
                        	
                            $unblock = UserBlock::whereUserId($user->id)->whereBlockUser($request->block_user);
                            $data   = ['status' => 0];
                            $unblock->update($data);
                            $delete = Follower::whereFollowerId($user->id)->whereFollowedId($request->block_user)->delete();
                            $response_data = ["success" => 1, "message" => __("validation.block_success", ["attr" => "Block"])];
                        }
                        else
                        {
                            $block              = new UserBlock();
                            $block->user_id     = $user->id;
                            $block->block_user  = $request->block_user;
                            $block->status      = $request->status;
                            $block->created_by  = $user->id;
                            $block->updated_by  = $user->id;
                            $block->save(); 
                            $delete = Follower::whereFollowerId($user->id)->whereFollowedId($request->block_user)->delete();
                            $response_data = ["success" => 1, "message" => __("validation.block_success", ["attr" => "User blocked"])];
                        }  
                    }
                    else
                    {
                        $checking = UserBlock::whereUserId($user->id)->whereBlockUser($request->block_user)->first();
                        if($checking)
                        {
                            $unblock = UserBlock::whereUserId($user->id)->whereBlockUser($request->block_user);
                            $data   = ['status' => 1];
                            $unblock->update($data);
                        }
                        $update = Follower::whereFollowerId($user->id)->whereFollowedId($request->block_user);
                        if($update)
                        {
                            $data   = ['deleted_at' => NULL];
                            $update->update($data);
                            $response_data = ["success" => 1, "message" => __("validation.block_success", ["attr" => "User unblocked"])]; 
                        }
                    }
                }else
                {
                    $response_data = ["success" => 1, "message" => 'Same user account cannot be blocked'];
                }
                
            }
            else
            {
                $response_data = ["success" => 0,  "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
            }

          return response()->json($response_data);
    }

    public function blockList(Request $request)
    {
        /*$data= UserBlock::with(["blockUser"])->whereUserId(Auth::id())->get();*/
        $query = UserBlock::select(["block_user"])->whereStatus(0)->whereUserId(Auth::id())->get();
        $user = $query->pluck("block_user")->toArray();
        $limit = config("site.pagination.block");
        $data=User::select("id", "name", "gender","profile_pic")->whereIn('id',$user)->paginate($limit);
        $response_data = ["success" => 1, 'data' => $data];
        return response()->json($response_data);
    }

    public function updateLocation(Request $request)
    {
        $validator = Validator::make($request->all(),
                    [
                      "lat"           => "required|numeric",
                      "long"          => "required|numeric",
                    ]);

        if (!$validator->fails()){
            $user = auth()->user();
            $user->lat = $request->lat;
            $user->lng = $request->long;
            if($user->save()){
                $response_data = ["success" => 1, "message" => __("validation.update_success", ["attr" => "Location"])];
            }else{
                $response_data = ["success" => 0, "message" => __("site.server_error")];
            }
        }else{
            $response_data = ["success" => 0,  "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        return response()->json($response_data);
    }


    public function personalUser(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(),
            [
              'name'    => 'required|min:'.limit("name.min").'|max:'.limit("name.max"),
              'age'    => 'required|min:'.limit("age.min").'|max:'.limit("age.max").'|numeric',
              'gender'      => 'required|exists:genders,id,active,1,deleted_at,NULL',
              'height'    => 'required|min:'.limit("height.min").'|max:'.limit("height.max").'|numeric|between:0,99.99',
              'weight'    => 'required|min:'.limit("weight.min").'|max:'.limit("weight.max").'|numeric|between:0,999.99',
              'hair_color'    => 'required|in:0,1',
              'contact'    => 'required|in:0,1',
                'image' => 'required|image|mimes:'.limit("profile_pic.format").'|min:'.limit("profile_pic.min").'|max:'.limit("profile_pic.max").',|dimensions:min_width='.limit("profile_pic.min_width").',min_height='.limit("profile_pic.min_height"),
             
            ]);

        if(!$validator->fails()){
            $usercount = UserProfile::whereActive(1)->whereUserId($user->id)->whereUserType(1)->count();
            
            if($user->active == 1 && $usercount<=2)
            {
                $filePath = "images/profile/";
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
                $response_data = ["success" => 1, "message" => __("validation.create_success")];
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

    public function personalUserEdit(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(),
            [
              'data' => 'required|exists:user_profiles,id,deleted_at,NULL',
            ]);

        if(!$validator->fails()){
            $userProfile = UserProfile::find($request->data);
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

    public function personalUserDelete(Request $request)
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
                $response_data = ["success" => 1, "message" => __("validation.delete_success",['attr'=>'Personal User'])];
            }else{
                $response_data = ["success" => 0, "message" => __("site.server_error")];
            }
            
        }else{
            $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()->toArray()];
        }
        return response()->json($response_data);
    }


    public function personalUserUpdate(Request $request)
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
             
            ]);

        if(!$validator->fails()){
            $usercount = UserProfile::whereActive(1)->whereUserId($user->id)->whereUserType(1)->count();
            
            if($user->active == 1 && $usercount<=2)
            {
                $filePath = "images/profile/";
                if($request->hasFile('image')){
                    $file = $request->file('image')->store($filePath);
                    $file = "storage/".$file;
                }else{
                    $file =  "storage/".$filePath.uniqid().".png";
                    Avatar::create(strtoupper($request->name))->save($file, $quality = 90);
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

    public function simpleEmail()
    {
        $msg = "First line of text\nSecond line of text";

        // use wordwrap() if lines are longer than 70 characters
        $msg = wordwrap($msg,70);

      // send email
       $result = mail("dinesh84.techzar@gmail.com","My subject",$msg);

       if($result){
        $response_data = ["success" => 1, "message" => "Success"];
       }else
       {
         $response_data = ["success" => 0, "message" => "Fail"];
       }
       return response()->json($response_data);
    }
}
