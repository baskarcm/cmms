<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Validator;
use App\BreakdownProblemDetail;
use App\ProductionUptime;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;

class BreakdownProblemDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response_data["title"] = __("title.private.breakdown_pm_details");
        return view("admin.report.bdown-problem-report")->with($response_data);
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
        $new_request = $request->all();
        $validator = Validator::make($new_request,
            [
                'name'      => 'required|string:production_uptimes,name',
                'jan'       => 'nullable|string',
                'feb'       => 'nullable|string',
                'march'     => 'nullable|string',  
                'april'     => 'nullable|string',
                'may'       => 'nullable|string',
                'april'     => 'nullable|string',
                'may'       => 'nullable|string',
                'june'      => 'nullable|string',
                'july'      => 'nullable|string',
                'aug'       => 'nullable|string',
                'sep'       => 'nullable|string',
                'oct'       => 'nullable|string',
                'nov'       => 'nullable|string',
                'dec'       => 'nullable|string',
            ]);

        if(!$validator->fails()){
       
            $name           = $request->name;
            $jan            = $request->jan;
            $feb            = $request->feb;
            $march          = $request->march;
            $april          = $request->april;            
            $may            = $request->may;            
            $july           = $request->july;            
            $aug            = $request->aug;            
            $sep            = $request->sep;            
            $oct            = $request->oct;            
            $nov            = $request->nov;            
            $dec            = $request->dec;             
            $active         = 1;
            $created_by     = Auth::id();
            $updated_by     = Auth::id();

            for($count = 0; $count < count($name); $count++)
            {
                $data = array(
                    'name'       => $name[$count],
                    'jan'        => $jan[$count],
                    'feb'        => $jan[$count],
                    'march'      => $jan[$count],
                    'april'      => $jan[$count],
                    'may'        => $jan[$count],
                    'june'       => $jan[$count],
                    'july'       => $jan[$count],
                    'aug'        => $jan[$count],
                    'sep'        => $jan[$count],
                    'nov'        => $jan[$count],
                    'dec'        => $jan[$count]
                );
                $insert_data[] = $data; 
            }
            $production = ProductionUptime::insert($insert_data);
            if($production->save()){
                $response_data = ["success" => 1,"message" => __("validation.create_success",['attr'=>"Production Uptime"])];
                
            }else{
                $response_data = ["success" => 0, "message" => __("site.server_error")];
            }
            
        }else{
            $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()];
        }
    
        return response()->json($response_data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BreakdownProblemDetail  $breakdownProblemDetail
     * @return \Illuminate\Http\Response
     */
    public function show(BreakdownProblemDetail $breakdownProblemDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BreakdownProblemDetail  $breakdownProblemDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(BreakdownProblemDetail $breakdownProblemDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BreakdownProblemDetail  $breakdownProblemDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BreakdownProblemDetail $breakdownProblemDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BreakdownProblemDetail  $breakdownProblemDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(BreakdownProblemDetail $breakdownProblemDetail)
    {
        //
    }
}
