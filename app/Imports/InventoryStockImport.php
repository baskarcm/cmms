<?php

namespace App\Imports;

use App\InventoryStock;
use Auth;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InventoryStockImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */


    use Importable;

    public function collection(collection $rows)
    {
        Validator::make($rows->toArray(),
        [
            '*.name' => 'required|string',
            '*.code' => 'required',
            '*.stock' => 'required|numeric',
        ])->validate();

        $user = auth()->user();
        foreach ($rows as $value) {           
            InventoryStock::create([
                'name'          =>  $value['name'],
                'code'          =>  $value['code'],
                'stock'         =>  $value['stock'],
                'active'        =>  1,
                'created_by'    =>  $user->id,
                'updated_by'    =>  $user->id,
            ]);
        }
    }
}
