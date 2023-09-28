<?php

namespace App\Http\Controllers\Admin;

use App\ProductForm;
use Auth;
use App\Product;
use App\Schedule;
use App\ProductType;
use App\InspectionIteam;
use App\InspectionPoint;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Milon\Barcode\DNS2D;
use Milon\Barcode\DNS1D;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;

class ProductFormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response_data["title"] = __("title.private.check_list");
        $response_data["products"] = Product::whereActive(1)->get();
        return view("admin.product.form_list")->with($response_data);
    }

    public function form()
    {
        $response_data["title"] = __("title.private.check_list_form");
        $response_data["products"] = Product::whereActive(1)->get();
        return view("admin.product.form_reg")->with($response_data);
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

    public function getItems(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
              'data' => 'required|exists:inspection_points,id,deleted_at,NULL',
            ]);

        if (!$validator->fails())
        {
            $typeId = $request->data;
            $data = InspectionIteam::where('inspec_point', $typeId)->get();
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

    public function getList(Request $request)
    {
        $query = ProductType::with('equipment')->select();
        if($request->has("product") && $request->product != ""){
            $query->whereProductId($request->product);
        }
        if($request->has("line") && $request->line != ""){
            $query->whereLocation($request->line);
        }
        if($request->has("status") && $request->status != ""){
            $query->whereActive($request->status);
        }
        return Datatables::of($query->get())->make(true);
    }

    public function formEdit($key, Request $request)
    {
        try {
                $id = decrypt($key);
                $user = ProductType::find($id);
                if($user){
                            $response_data["title"] = __("title.private.edit_check_list_form");
                            $response_data["product_key"] = $id;
                            $response_data["products"] = Product::whereActive(1)->get();
                            return view("admin.product.form_edit")->with($response_data);
                        }
                        else
                        {
                        return redirect(route("private.form"));
                        }
            } catch (DecryptException $e) {
                return redirect(route("private.form"));
        }

    }

    public function view($key, Request $request)
    {
        try {
            $id = decrypt($key);
            $user = ProductType::find($id);
            if($user){
                        $response_data["title"]         = __("title.private.equipment_detail");
                        $response_data["product_key"]   = $id;
                        $response_data["product_type"]  = ProductType::with(['product'])->whereId($id)->whereActive(1)->first();
                        //$response_data["point"]          = ProductForm::with(['point','item'])->whereProductType($id)->whereLevel(1)->whereActive(1)->get();
                        $response_data["form"]          = ProductForm::with(['item.point'])->whereProductType($id)->whereLevel(2)->whereActive(1)->get();
                        //dd($response_data);
                        return view("admin.product.view")->with($response_data);
                    }
                    else
                    {
                    return redirect(route("private.form"));
                    }
        } catch (DecryptException $e) {
            return redirect(route("private.form"));
        }
    }

    public function create(Request $request)
    {
        $new_request = $request->all();
        $validator = Validator::make($new_request,
            [
                'product'          => 'required|exists:products,id,deleted_at,NULL',
                'code'             => 'required|min:'.limit("inspection.min").'|max:'.limit("inspection.max"),
                'line'             => 'required|min:1|max:'.limit("inspection.max"),
                'station'          => 'required|min:1|max:'.limit("inspection.max"),
                'position'         => 'required|min:1|max:'.limit("inspection.max"),
            ]);

        if(!$validator->fails()){
            $data = new ProductType();
            $data->product_id     = $request->product;
            $data->code           = $request->code;
            $data->location       = $request->line;
            $data->station        = $request->station;
            $barcode = mt_rand(10000000, 99999999);
            $data->doc_no = $barcode;
            $data->barcode = $barcode;
            $test = Storage::put("images/barcode/".$barcode.'.png',base64_decode(DNS1D::getBarcodePNG($barcode,"I25",6,50,array(1,1,1), true)));
            $file = Storage::url("images/barcode/".$barcode.'.png');
            $data->barcode_file = $file;
            $data->position = $request->position;
            $data->active         = 1;
            $data->created_by     = Auth::id();
            $data->updated_by     = Auth::id();
            if($data->save()){
                if($request->has("point") && $request->point != "")
                {
                    foreach($request->point as $points){
                    	$point = new ProductForm();
                        $point->product_type        = $data->id;
                        $point->inspec_point        = $points;
                        $point->level               = 1;
                        $point->active              = 1;
                        $point->created_by          = Auth::id();
                        $point->updated_by          = Auth::id();
                        $point->save();
                    }
                }
                if($request->has("items") && $request->items != "")
                {
                    foreach($request->items as $items){
                        $item = new ProductForm();
                        $item->product_type        = $data->id;
                        $item->inspec_iteam        = $items;
                        $item->level               = 2;
                        $item->active              = 1;
                        $item->created_by          = Auth::id();
                        $item->updated_by          = Auth::id();
                        $item->save();
                    }
                }

                $response_data = ["success" => 1,"message" => __("validation.create_success",['attr'=>'Product Form'])];
            }else{
                $response_data = ["success" => 0, "message" => __("site.server_error")];
            }

        }else{
            $response_data = ["success" => 0, "message" => __("validation.check_fields"), "errors" => $validator->errors()];
        }

        return response()->json($response_data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProductForm  $productForm
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
          'data' => 'required|exists:product_types,id,deleted_at,NULL',
        ]);

    if (!$validator->fails())
    {
        $typeId = $request->data;
        $data = ProductType::where('id', $typeId)->first();
        if($data)
        {
            $response_data['type'] = $data;
            $response_data['point'] = ProductForm::whereProductType($typeId)->whereLevel(1)->get();
            $response_data['items'] = ProductForm::whereProductType($typeId)->whereLevel(2)->get();
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
     * @param  \App\ProductForm  $productForm
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $new_request = $request->all();
        $validator = Validator::make($new_request,
            [
                'data'             => 'required|exists:product_types,id,deleted_at,NULL',
                'product'          => 'required|exists:products,id,deleted_at,NULL',
                'code'             => 'required|min:'.limit("inspection.min").'|max:'.limit("inspection.max"),
                'line'             => 'required|min:1|max:'.limit("inspection.max"),
                'station'          => 'required|min:1|max:'.limit("inspection.max"),
                'position'         => 'required|min:1|max:'.limit("inspection.max"),
            ]);

        if(!$validator->fails()){
            $data   = ProductType::find($request->data);
            $data2  = ProductForm::whereProductType($request->data);
            $data->product_id     = $request->product;
            $data->code           = $request->code;
            $data->location       = $request->line;
            $data->station        = $request->station;
            $data->position       = $request->position;
            $data->active         = 1;
            $data->created_by     = Auth::id();
            $data->updated_by     = Auth::id();
            if($data->save()){
                $data2->delete();
                if($request->has("point") && $request->point != "")
                {
                    foreach($request->point as $points){
                        $point = new ProductForm();
                        $point->product_type        = $data->id;
                        $point->inspec_point        = $points;
                        $point->level               = 1;
                        $point->active              = 1;
                        $point->created_by          = Auth::id();
                        $point->updated_by          = Auth::id();
                        $point->save();
                    }
                }
                if($request->has("items") && $request->items != "")
                {
                    foreach($request->items as $items){
                        $item = new ProductForm();
                        $item->product_type        = $data->id;
                        $item->inspec_iteam        = $items;
                        $item->level               = 2;
                        $item->active              = 1;
                        $item->created_by          = Auth::id();
                        $item->updated_by          = Auth::id();
                        $item->save();
                    }

                }

                $response_data = ["success" => 1,"message" => __("validation.update_success",['attr'=>'Product Form'])];
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
                    "pk" => "required|exists:product_types,id,deleted_at,NULL",
                ]);
        if(!$validator->fails()){
            $data = ProductType::find($request->pk);
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
     * Remove the specified resource from storage.
     *
     * @param  \App\ProductForm  $productForm
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
              'data' => 'required|exists:product_types,id,deleted_at,NULL',
            ]);

        if (!$validator->fails())
        {
            $schedule = Schedule::whereProductType($request->data)->first();
            if($schedule)
            {
                $response_data =  ['success' => 0, 'message' => "The product is scheduled for user"];
            }else
            {
                $data = ProductType::find($request->data);
                if($data->delete()){
                        $response_data =  ['success' => 1, 'message' => __('validation.delete_success',['attr'=>'product form'])];
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
}
