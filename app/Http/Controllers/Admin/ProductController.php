<?php

namespace App\Http\Controllers\Admin;

use App\Product;
use Auth;
use Validator;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Encryption\DecryptException;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $response_data["title"] = __("title.private.product");
        return view("admin.product.list")->with($response_data);
    }
    public function getList(Request $request)
    {
        $query = new Product();
        if($request->has("status") && $request->status != ""){
            $query->whereActive($request->status);
        }
        return Datatables::of($query->get())->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $new_request = $request->all();
        //Log::debug($new_request);
        $validator = Validator::make($new_request,
            [
                'name'          => 'required|min:'.limit("inspection.min").'|max:'.limit("inspection.max").'|not_exists:products,name,deleted_at,NULL',
                'image.*'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            ]);

        if(!$validator->fails()){
       
            $product = new Product();
            $filePath = "images/equipment/";
            $product->name           = $request->name;
            if(!empty($request->image ))
            {
                $file = [];
                foreach( $request->image as $image){
                    // $file1 = $image->store($filePath);
                    // $file[] = url("storage/".$file1);
                      $file1 = $image;
                      $extension = $file1->getClientOriginalExtension();
                      $filename = time() . '.' . $extension;
                      $file1->move('public/storage', $filename);
                      $file[] = url('/public/storage/'.$filename);
                }
                $product->image   = json_encode($file);
            }
            $product->active         = 1;
            $product->created_by     = Auth::id();
            $product->updated_by     = Auth::id();
            if($product->save()){
                $response_data = ["success" => 1,"message" => __("validation.create_success",['attr'=>'Product'])];
            }else{
                $response_data = ["success" => 0, "message" => __("site.server_error")];
            }
            
        }else{
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        //
        $validator = Validator::make($request->all(),
            [
              'data' => 'required|exists:products,id,deleted_at,NULL',
            ]);
            
        if (!$validator->fails()) 
        { 
            $typeId = $request->data;
            $product = Product::where('id', $typeId)->first();
            if($product)
            {
                $response_data = ["success" => 1, "data" => $product];
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
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //

        $typeId = $request->data;
        $new_request = $request->all();
        $validator = Validator::make($new_request,
            [
                'data' => 'required|exists:products,id,deleted_at,NULL',
                'name' => 'required|min:'.limit("inspection.min").'|max:'.limit("inspection.max").'|unique:products,name,'.$typeId.',id,deleted_at,NULL',
                'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            ]);

        if(!$validator->fails()){
            
           $product = Product::find($typeId);
           $filePath = "images/equipment/";
           $product->name           = $request->name;
           if(!empty($request->image ))
            {
                $file = [];
                foreach( $request->image as $image){
                    // $file1 = $image->store($filePath);
                    // $file[] = url("storage/".$file1);
                    $file1 = $image;
                      $extension = $file1->getClientOriginalExtension();
                      $filename = time() . '.' . $extension;
                      $file1->move('public/storage', $filename);
                      $file[] = url('/public/storage/'.$filename);
                }
                $product->image   = json_encode($file);
            }
           $product->updated_by     = Auth::id();

            if($product->save()){
                $response_data = ["success" => 1,"message" => __("validation.update_success",['attr'=>'Product'])];
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
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
   {
        $validator = Validator::make($request->all(),
            [
              'data' => 'required|exists:products,id,deleted_at,NULL',
            ]);
            
        if (!$validator->fails()) 
        { 
            $product = Product::find($request->data); 
            if($product->delete()){
                    $response_data =  ['success' => 1, 'message' => __('validation.delete_success',['attr'=>'Product'])]; 
            }else{
                $response_data =  ['success' => 0, 'message' => __('validation.refresh_page')];
            }

        }else
        {
            $response_data =  ['success' => 0, 'message' => __('validation.refresh_page')];
        }

        return response()->json($response_data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function qrCode()
    {
       return QRCode::text('QR Code Generator for Laravel!')->png();    
    }


    public function image(Request $request)
    {
        //
        $validator = Validator::make($request->all(),
            [
              'data' => 'required|exists:products,id,deleted_at,NULL',
            ]);
            
        if (!$validator->fails()) 
        { 
            $typeId = $request->data;
            $product = Product::where('id', $typeId)->first();
            if($product)
            {
                $response_data = ["success" => 1, "data" => $product];
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
    
}
