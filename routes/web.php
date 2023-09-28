<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return view('auth.login');
});
Route::prefix("private")->namespace("Admin")->group(function(){
	Auth::routes();
});

Route::prefix("private")->middleware("auth")->name("private.")->namespace("Admin")->group(function(){

	Route::get('dashboard', "DashboardController@index")->name('dashboard');

	Route::get('logout', "AdminUserController@logout")->name('logout');

	//Admin User related Routes
	Route::get('adminuser/list', "AdminUserController@index")->name("adminusers");
	Route::post('adminuser/list', "AdminUserController@getList")->name("adminuser.list");
	Route::post('adminuser/create', "AdminUserController@create")->name("adminuser.create");
	Route::post('adminuser/edit', "AdminUserController@edit")->name("adminuser.edit");
	Route::post('adminuser/update', "AdminUserController@update")->name("adminuser.update");
	Route::post('adminuser/status', "AdminUserController@updateStatus")->name("adminuser.status");
	Route::post('adminuser/destroy', "AdminUserController@destroy")->name("adminuser.destroy");
	Route::get('adminuser/profile/{key}', "AdminUserController@viewProfile")->name("adminuser.profile");
	Route::get('my-profile', "AdminUserController@myProfile")->name("myprofile");
	Route::post('password-update', "AdminUserController@passwordUpdate")->name("passwordUpdate");
	Route::post('profile-update', "AdminUserController@profileUpdate")->name("profileUpdate");

	//Admin User Type Routes
	Route::get('admin-type', "AdminTypeController@index")->name("admintypes");
	Route::post('admin-type/list', "AdminTypeController@getUserTypeList")->name("admintype.list");
	Route::post('admin-type/create', "AdminTypeController@create")->name("admintype.create");
	Route::post('admin-type/edit', "AdminTypeController@edit")->name("admintype.edit");
	Route::post('admin-type/update', "AdminTypeController@update")->name("admintype.update");
	Route::post('admin-type/status', "AdminTypeController@updateStatus")->name("admintype.status");
	Route::post('admin-type/destroy', "AdminTypeController@destroy")->name("admintype.destroy");

	//Chart
	Route::post('dashboard-chart', "DashboardController@barChart")->name('dashboard.chart');
	Route::post('pm-chart', "DashboardController@pmChart")->name('piechart.pm');
	Route::post('bk-chart', "DashboardController@bkChart")->name('piechart.bk');
	Route::post('line-chart', "DashboardController@lineChart")->name('linechart');
	Route::post('performance-chart', "DashboardController@performance")->name('performance.chart');
	// User Type
	Route::get('user-type', "UserTypeController@index")->name("usertype")->middleware("ican:usertype.view");
	Route::post('user-type/list', "UserTypeController@getList")->name("usertype.list");
	Route::post('user-type/create', "UserTypeController@create")->name("usertype.create");
	Route::post('user-type/edit', "UserTypeController@edit")->name("usertype.edit")->middleware("ican:usertype.edit");
	Route::post('user-type/update', "UserTypeController@update")->name("usertype.update");
	Route::post('user-type/status', "UserTypeController@updateStatus")->name("usertype.status");
	Route::post('user-type/destroy', "UserTypeController@destroy")->name("usertype.destroy")->middleware("ican:usertype.delete");
	

	//Permission list
	Route::get('get-permission', "PermissionController@getPermissionList")->name("permission.list");
	Route::post('permission/update', "PermissionController@update")->name("permission.update");

	/*// Attribute Category Routes
	Route::get('attribute-category', "AttributeCategoryController@index")->name("attributecategories")->middleware("ican:attributecategory.view");
	Route::post('attribute-category/list', "AttributeCategoryController@getList")->name("attributecategory.list")->middleware("ican:attributecategory.view");
	Route::post('attribute-category/create', "AttributeCategoryController@create")->name("attributecategory.create")->middleware("ican:attributecategory.create");
	Route::post('attribute-category/edit', "AttributeCategoryController@edit")->name("attributecategory.edit")->middleware("ican:attributecategory.edit");
	Route::post('attribute-category/update', "AttributeCategoryController@update")->name("attributecategory.update")->middleware("ican:attributecategory.edit");
	Route::post('attribute-category/status', "AttributeCategoryController@updateStatus")->name("attributecategory.status")->middleware("ican:attributecategory.edit");
	Route::post('attribute-category/destroy', "AttributeCategoryController@destroy")->name("attributecategory.destroy")->middleware("ican:attributecategory.delete");*/


	//User Routes
	Route::get('users',"UserController@index")->name("users");
	Route::post('user/list',"UserController@getList")->name("user.list");
	Route::post('user/create', "UserController@create")->name("user.create");
	Route::post('user/count', "UserController@userCount")->name("user.count");
	Route::get('user/profile/{key}', "UserController@viewProfile")->name("user.profile");
	Route::post('user/status', "UserController@updateStatus")->name("user.status");
	Route::post('user/block', "UserController@updateBlock")->name("user.block");
	Route::post('user/destroy',"UserController@destroy")->name("user.destroy");
	Route::post('user/edit', "UserController@edit")->name("user.edit");
	Route::post('user/update', "UserController@update")->name("user.update");

	// inspection Point
	Route::get('inspection-point', "InspectionPointController@index")->name("insPoint");
	Route::post('inspection-point/list', "InspectionPointController@getList")->name("insPoint.list");
	Route::post('inspection-point/create', "InspectionPointController@create")->name("insPoint.create");
	Route::post('inspection-point/edit', "InspectionPointController@edit")->name("insPoint.edit");
	Route::post('inspection-point/update', "InspectionPointController@update")->name("insPoint.update");
	Route::post('inspection-point/status', "InspectionPointController@updateStatus")->name("insPoint.status");
	Route::post('inspection-point/destroy', "InspectionPointController@destroy")->name("insPoint.destroy");

	// inspection items
	Route::get('inspection-items', "InspectionIteamController@index")->name("insIteam");
	Route::post('inspection-items/list', "InspectionIteamController@getList")->name("insIteam.list");
	Route::post('inspection-items/create', "InspectionIteamController@create")->name("insIteam.create");
	Route::post('inspection-items/edit', "InspectionIteamController@edit")->name("insIteam.edit");
	Route::post('inspection-items/update', "InspectionIteamController@update")->name("insIteam.update");
	Route::post('inspection-items/status', "InspectionIteamController@updateStatus")->name("insIteam.status");
	Route::post('inspection-items/destroy', "InspectionIteamController@destroy")->name("insIteam.destroy");
	Route::post('inspection-items/point', "InspectionIteamController@getPoint")->name("insIteam.getPoint");

	//Product Form 
	Route::get('product-form', "ProductFormController@index")->name("form");
	Route::post('product-form/list', "ProductFormController@getList")->name("form.list");
	Route::post('product-form/create', "ProductFormController@create")->name("form.create");
	Route::post('product-form/edit', "ProductFormController@edit")->name("form.edit");
	Route::post('product-form/editPoint', "ProductFormController@edit")->name("form.editPoint");
	Route::post('product-form/update', "ProductFormController@update")->name("form.update");
	Route::post('product-form/status', "ProductFormController@updateStatus")->name("form.status");
	Route::post('product-form/destroy', "ProductFormController@destroy")->name("form.destroy");
	Route::post('product-form/point', "ProductFormController@getPoint")->name("form.getPoint");
	Route::post('product-form/items', "ProductFormController@getItems")->name("form.getItems");
	Route::get('product-form/reg-form', "ProductFormController@form")->name("form.reg");
	Route::get('product-form/edit-form/{key}', "ProductFormController@formEdit")->name("formEdit");
	Route::get('product-form/view/{key}', "ProductFormController@view")->name("formView");

	//product routes
	Route::get('product',"ProductController@index")->name("product");
	Route::post('product/list', "ProductController@getList")->name("product.list");
	Route::post('product/create', "ProductController@create")->name("product.create");
	Route::post('product/edit', "ProductController@edit")->name("product.edit");
	Route::post('product/update', "ProductController@update")->name("product.update");
	Route::post('product/status', "ProductController@updateStatus")->name("product.status");
	Route::post('product/destroy', "ProductController@destroy")->name("product.destroy");
	Route::post('product/image', "ProductController@image")->name("product.image");

	Route::get('qr-code',"ProductController@qrCode")->name("qrCode");

		// product judge
	Route::get('product-judge', "ProductJudgeController@index")->name("judge");
	Route::post('product-judge/list', "ProductJudgeController@getList")->name("judge.list");
	Route::post('product-judge/create', "ProductJudgeController@create")->name("judge.create");
	Route::post('product-judge/edit', "ProductJudgeController@edit")->name("judge.edit");
	Route::post('product-judge/update', "ProductJudgeController@update")->name("judge.update");
	Route::post('product-judge/status', "ProductJudgeController@updateStatus")->name("judge.status");
	Route::post('product-judge/destroy', "ProductJudgeController@destroy")->name("judge.destroy");

	//Schedule
	Route::get('schedule',"ScheduleController@index")->name("schedule");
	Route::post('schedule/list',"ScheduleController@getList")->name("schedule.list");
	Route::post('schedule/create', "ScheduleController@create")->name("schedule.create");
	Route::post('schedule/count', "ScheduleController@userCount")->name("schedule.count");
	Route::post('schedule/status', "ScheduleController@updateStatus")->name("schedule.status");
	Route::post('schedule/block', "ScheduleController@updateBlock")->name("schedule.block");
	Route::post('schedule/destroy',"ScheduleController@destroy")->name("schedule.destroy");
	Route::post('schedule/edit', "ScheduleController@edit")->name("schedule.edit");
	Route::post('schedule/update', "ScheduleController@update")->name("schedule.update");
	Route::post('schedule/product-type', "ScheduleController@productType")->name("schedule.productType");

	// Inventory
	Route::get('inventory/list', "InventoryController@index")->name("inventory");
	Route::post('inventory/list', "InventoryController@getList")->name("inventory.list");
	Route::post('inventory/status', "InventoryController@updateStatus")->name("inventory.status");
	Route::post('inventory/destroy', "InventoryController@destroy")->name("inventory.destroy");


	// Breakdown
	Route::get('breakdown',"BreakdownController@index")->name("breakdown");
	Route::post('breakdown/list',"BreakdownController@getList")->name("breakdown.list");
	Route::get('breakdown/view/{key}', "BreakdownController@view")->name("breakdown.view");
	Route::post('breakdown/status', "BreakdownController@updateStatus")->name("breakdown.status");
	Route::post('breakdown/destroy',"BreakdownController@destroy")->name("breakdown.destroy");

	Route::get('pm',"PmController@index")->name("pm");
	Route::post('pm/list',"PmController@getList")->name("pm.list");
	Route::get('pm/view/{key}', "PmController@view")->name("pm.view");
	Route::post('pm/status', "PmController@updateStatus")->name("pm.status");
	Route::post('pm/destroy',"PmController@destroy")->name("pm.destroy");

	//Report
	Route::get('month-view',"ReportController@monthView")->name("month.view");
	Route::post('month-list',"ReportController@monthList")->name("report.month");
	Route::post('month-year-list',"ReportController@monthYearList")->name("year.list");
	Route::post('problem-detail',"ReportController@problemDetail")->name("breakdown-problem.report");
	Route::post('problem-store',"ReportController@store")->name("problem.store");
	Route::post('year-report-store',"ReportController@yearReportStore")->name("year.report");
	Route::post('production-chart',"ReportController@production")->name("production.chart");
	Route::post('downtime-chart',"ReportController@downtime")->name("downtime.chart");

	//Breakdown Problem
	Route::get('production-uptime',"BreakdownProblemDetailController@index")->name("production-uptime.view");
	Route::post('production-uptime/list',"BreakdownProblemDetailController@store")->name("production-uptime.list");

	/*//planvsactual
	Route::get('actual',"BreakdownController@index")->name("actual");
	Route::post('actual/list',"BreakdownController@getList")->name("actual.list");*/

	//Inventory Import
	Route::post('inventory/stock-imports', "InventoryController@stockImports")->name("inventory.stock-imports");

	//Inventory Stock Download
	Route::post('inventory/stock-exports', "InventoryController@stockExports")->name("inventory.stock-exports");

	//planvsactual
	Route::get('actual/report',"ScheduleController@actual")->name("actual.report");
	Route::post('actual/list',"ScheduleController@getActualList")->name("actual.list");
	Route::post('actual/plan-exports', "ScheduleController@PlanDataExports")->name("actual.plan-exports");

	//Machine Export
	Route::post('machine/data-exports', "ReportController@dataMachineExports")->name("machine.data-exports");

	//Month DT Export
	Route::post('monthdt/month-exports', "ReportController@dataMonthdtExports")->name("monthdt.data-exports");

	//PM
	Route::get('report/pm',"ReportController@pmView")->name("pm.report");
	Route::post('pm-report/list',"ReportController@pmList")->name("pmreport.list");

	//Breakdown
	Route::get('report/breakdown',"ReportController@breakdownView")->name("breakdown.report");
	Route::post('breakdown-report/list',"ReportController@breakdownList")->name("bkReport.list");



	
	//Exports Data
	Route::post('schedule/data-exports', "ScheduleController@dataExports")->name("schedule.data-exports");
	Route::post('inventory/data-exports', "InventoryController@dataExports")->name("inventory.data-exports");

	Route::post('report/databreakdown-exports', "ReportController@dataBreakDownExports")->name("report.databreakdown-exports");
	Route::post('report/datapm-exports', "ReportController@dataPMExports")->name("report.datapm-exports");
	Route::post('report/individualpm-exports', "ReportController@individualPMExports")->name("report.individualpm-exports");
	Route::post('report/individualbreakdown-exports', "ReportController@individualBreakDownExports")->name("report.individualbreakdown-exports");
	Route::post('breakdown/data-exports',"BreakdownController@dataExports")->name("breakdown.data-exports");
	Route::post('breakdown/individualdata-exports',"BreakdownController@individualDataExports")->name("breakdown.individualdata-exports");
	Route::post('pm/data-exports',"PmController@dataExports")->name("pm.data-exports");
	Route::post('pm/individualdata-exports',"PmController@individualDataExports")->name("pm.individualdata-exports");
	Route::post('month-report',"ReportController@monthReportProblemDetailsExport")->name("month-report.problem-details-exports");
	
	Route::get('/foo', function () {
    Artisan::call('storage:link');
});

});

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/mail', 'HomeController@mail')->name('home');
