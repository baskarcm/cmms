<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::namespace("Api")->group(function () {

	Route::post('register', "UserController@register");
	Route::post('social-register', "UserController@socialRegister");
	Route::post('social-login', "UserController@socialCheck");
	Route::post('user/send-email-verify', "UserController@sendConfirmationEmailOtp");
	Route::post('user/verify-email', "UserController@checkVerificationToken");

	Route::post('login', "UserController@login");

	Route::post('reset-password', "ForgotPasswordController@sendResetLinkEmail");
	Route::post('check-reset-token', "ForgotPasswordController@checkResetToken");

	Route::get('check-email', "UserController@simpleEmail");
	Route::middleware('auth:api')->group(function () {

	    Route::post('update-password', "UserController@updatePassword");

		Route::get('profile', "UserController@profile");
		Route::post('updateprofile', "UserController@profileUpdate");
		Route::post('change-password', "UserController@changePassword");
		Route::post('update-fcm', "UserController@fcmUpdate");
		Route::post('logout', "UserController@logout");
		//Route::post('user-block', "UserController@userBlock");
		//Route::post('block-list', "UserController@blockList");
		Route::post('user/location', "UserController@updateLocation");
		Route::get('engineer/breakdown-list', "BreakdownController@engineerScheduleList");

		//PM Module
			Route::post('module/pm', "PmController@barcode");
			Route::post('module/get-pm', "PmController@getPm");
			Route::post('pm/registers', "PmController@registers");
			Route::post('pm/update', "PmController@update");
			Route::post('pm-engineer/registers', "PmController@commentRegs");
			Route::post('pm/get-list', "PmController@getList");
			Route::post('engineer/pm/get-list', "PmController@engineerList");
			Route::post('manager/pm/get-list', "PmController@managerList");	
			Route::post('spectator/pm/get-list', "PmController@spectatorList");
			Route::post('spectator/pm/test', "PmController@test");
			
			//User schedule
			Route::get('schedule/pm-list', "PmController@scheduleList");
			Route::get('pm-user/pending-list', "PmController@pendingList");
			Route::get('pm-user/complete-list', "PmController@completeList");

			//engineer schedule
			Route::get('pm-engineer/pm-list', "PmController@engineerScheduleList");
			Route::get('pm-engineer/pending-list', "PmController@engineerPendingList");
			Route::get('pm-engineer/complete-list', "PmController@engineerCompleteList");

			//Manager schedule
			Route::get('pm-manager/pm-list', "PmController@managerScheduleList");
			Route::get('pm-manager/pending-list', "PmController@managerPendingList");
            Route::get('pm-manager/complete-list', "PmController@managerCompleteList");
            
            //Sepctator schedule
			Route::get('pm-spectator/pm-list', "PmController@spectatorScheduleList");
			Route::get('pm-spectator/pending-list', "PmController@spectatorPendingList");
			Route::get('pm-spectator/complete-list', "PmController@spectatorCompleteList");

		//Breakdown
			//barcode
			Route::post('module/breakdown', "BreakdownController@barcode");
			//id based detail
			Route::post('module/get-breakdown', "BreakdownController@getBreakdown");
			Route::post('schedule/breakdown-registers', "BreakdownController@registers");

			//user schedule
			Route::get('schedule/breakdown-list', "BreakdownController@scheduleList");
			Route::get('schedule/pending-list', "BreakdownController@pendingList");
			Route::get('schedule/complete-list', "BreakdownController@completeList");

			Route::post('breakdown/get-list', "BreakdownController@getList");
			Route::post('engineer/breakdown/get-list', "BreakdownController@engineerGetList");
			Route::post('engineer/comment-registers', "BreakdownController@commentRegs");
			Route::post('schedule/breakdown-update', "BreakdownController@update");

			//engineer schedule
			Route::get('engineer/breakdown-list', "BreakdownController@engineerScheduleList");
			Route::get('engineer/pending-list', "BreakdownController@engineerPendingList");
			Route::get('engineer/complete-list', "BreakdownController@engineerCompleteList");

			//manager schedule
			Route::get('manager/breakdown-list', "BreakdownController@managerScheduleList");
			Route::get('manager/pending-list', "BreakdownController@managerPendingList");
            Route::get('manager/complete-list', "BreakdownController@managerCompleteList");
            
            //spectator schedule
			Route::get('spectator/breakdown-list', "BreakdownController@spectatorScheduleList");
			Route::get('spectator/pending-list', "BreakdownController@spectatorPendingList");
			Route::get('spectator/complete-list', "BreakdownController@spectatorCompleteList");

			//Notification
			Route::post('notify/read-status', "NotifyStatusController@readStatus");
			Route::get('notify/list', "NotifyStatusController@notifyList");
            Route::post('notify/status', "NotifyStatusController@notifyStatus");

			Route::post('device-token', "NotifyStatusController@deviceToken");

			//Dashboard
			Route::get('user/dashboard-count', "NotifyStatusController@userCount");
			Route::get('engineer/dashboard-count', "NotifyStatusController@engineerCount");
			Route::get('manager/dashboard-count', "NotifyStatusController@managerCount");
			Route::get('spectator/dashboard-count', "NotifyStatusController@spectatorCount");

	});
	// UNAUTHORIZED
    Route::get('unauthorized', function () {
        return response()->json(['error' => 'Unauthorized.', 'success' => 0], 401);
    })->name('unauthorized');

});
