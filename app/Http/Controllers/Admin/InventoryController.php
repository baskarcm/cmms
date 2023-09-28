<?php

namespace App\Http\Controllers\Admin;
use Auth;
use App\User;
use App\Inventory;
use App\InventoryStock;
use App\ProductType;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Exports\InventoryExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InventoryStockExport;
use App\Imports\InventoryStockImport;
class InventoryController extends Controller
{

	public function index()
    {
        $response_data["title"] = __("title.private.inventory_list");
        return view("admin.inventory.list")->with($response_data);
    }

    public function getList(Request $request)
    {
        $query = Inventory::with(['equipment.product','users:id,name']);
        if($request->has("status") && $request->status != ""){
            $query->whereActive($request->status);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', "{$request->get('start_date')}");
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', "{$request->get('end_date')}");
        }
        return Datatables::of($query->get())->make(true);
    }


    public function updateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
                    "value" => "required|in:0,1",
                    "pk" => "required|exists:inventories,id",
                ]);
        if(!$validator->fails()){
            $data = Inventory::find($request->pk);
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


    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
              'data' => 'required|exists:inventories,id,deleted_at,NULL',
            ]);
            
        if (!$validator->fails()) 
        { 
            $data = Inventory::find($request->data); 
            if($data->delete()){
                    $response_data =  ['success' => 1, 'message' => __('validation.delete_success',['attr'=>'Inventory'])]; 
            }else{
                $response_data =  ['success' => 0, 'message' => __('validation.refresh_page')];
            }

        }else
        {
            $response_data =  ['success' => 0, 'message' => __('validation.refresh_page')];
        }

        return response()->json($response_data);
    }

    public function dataExports(Request $request)
    {
        $current = strtotime(now());
        $file = "Inventory-".$current.".xlsx";
        Excel::store(new InventoryExport($request), $file);
        $downloadUrl =url('/')."/storage/".$file;
        $response_data = ["success" => 1,"message" => __("validation.create_success",['attr'=>'Inventory Export']),"data"=> $downloadUrl];
        return response()->json($response_data);
    }

    public function stockImports(Request $request)
    {
        $new_request = $request->all();
        $validator = Validator::make($new_request,
        [
          'importFile'      => 'required|mimes:csv,txt',
        ]);

        if(!$validator->fails()){
            if($request->hasFile('importFile')){
                $StockImport = new InventoryStockImport;
                try{
                    $count = InventoryStock::whereActive(1)->get();
                    //dd($count);
                    if($count->count() > 0){
                        InventoryStock::truncate();
                    }
                    Excel::import($StockImport, $request->file('importFile'));
                    $response_data = ["success" => 1,"message" => __("validation.create_success",['attr'=>'Import'])];
                }catch (\Exception $e ){
                    $response_data = ["success" => 0, "message" => "The given data was invalid", "errors" => $e->errors()];
                }
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

    public function stockExports(Request $request)
    {
        $current = strtotime(now());
        $file = "Inventory Stock-".$current.".xlsx";
        Excel::store(new InventoryStockExport($request), $file);
        $downloadUrl =url('/')."/storage/".$file;
        $response_data = ["success" => 1,"message" => __("validation.create_success",['attr'=>'Inventory Stock Export']),"data"=> $downloadUrl];
        return response()->json($response_data);
    }
}