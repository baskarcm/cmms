<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PmDetail extends Model
{

    public function point()
    {
        return $this->belongsTo("App\InspectionPoint", "inspec_point", "id");
    }

    public function items()
    {
        return $this->belongsTo("App\InspectionIteam", "inspec_item", "id");
    }

    public function judge()
    {
        return $this->belongsTo("App\ProductJudge", "product_judge", "id");
    }

}
