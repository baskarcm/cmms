<?php

namespace App\Exports;

use App\BreakdownModule;
use App\ProductType;
use App\ProductionUptime;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
// use Maatwebsite\Excel\Concerns\WithDrawings;
// use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class MonthDownTimeExport implements FromView, ShouldAutoSize, WithTitle, WithCustomStartCell, WithEvents 
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $getLine,$getYear,$totalCount;
    public function __construct($request)
    {
       $this->getLine = $request->getLine;
       $this->getYear = $request->getYear;
    } 

    public function title(): string
    {
        return 'Monthly Downtime Report';
    }

    public function registerEvents(): array
    {
        return [
           
            AfterSheet::class    => function(AfterSheet $event) {

                /*$totalAllBorder = 'A1:O'.$this->totalCount;*/
                $event->sheet->getPageSetup()->setFitToWidth(1);

                // page formatting (orientation and size)
                $event->sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

                $event->sheet->getStyle('A1:N9')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '030048'],
                        ],
                        'font' => [
                            'name'      =>  'Times Roman',
                            'size'      =>  12,
                            
                        ],
                    ],
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);     

                $event->sheet->getStyle('A1:N2')->applyFromArray([ 
                        'font' => [
                            'name'      =>  'Times Roman',
                            'size'      =>  12,
                            'bold'      =>  true
                        ],
                    ]
                );

                $event->sheet->getStyle('A2:N2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('d8d8d8');

                $event->sheet->getRowDimension('2')->setRowHeight(30);
            },
        ];
    }

    public function startCell(): string
    {
        return 'A10';
    }

    public function view(): View
    {
        $year = $this->getYear;
        $line = $this->getLine;

        $month =[
            '1' => 'Jan', '2' => 'Feb', '3' => 'Mar', '4' => 'Apr', '5' => 'May', '6' => 'Jun',
            '7' => 'July', '8' => 'Aug', '9' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec',
        ];

        $total1 = 0;  $total2 = 0;  $total3 = 0;  $total4 = 0; $total5 = 0; $total6 = 0; $total7 = 0; $total8 = 0; $total9 = 0; 
        $total10 = 0; $total11 = 0; $total12 = 0; $totalsum = 0;

        $count1 = 0; $count2 = 0; $count3 = 0; $count4 = 0;  $count5 = 0;  $count6 = 0;
        $count7 = 0; $count8 = 0; $count9 = 0; $count10 = 0; $count11 = 0; $count12 = 0; $count_sum =0; 

        $product = ProductType::whereLocation($line)->get();

        $uptime= ProductionUptime::where('year',$year)->whereLine($line)->whereType(1)->first();
        if($uptime)
        {
            $mtbf_jan = $uptime->jan; $mtbf_feb = $uptime->feb; $mtbf_mar = $uptime->mar;
            $mtbf_apr = $uptime->apr; $mtbf_may = $uptime->may; $mtbf_jun = $uptime->jun; 
            $mtbf_jul = $uptime->jul; $mtbf_aug = $uptime->aug; $mtbf_sep = $uptime->sep;
            $mtbf_oct = $uptime->oct; $mtbf_nov = $uptime->nov; $mtbf_dec = $uptime->dec;
        }else{

            $mtbf_jan = 0;$mtbf_feb = 0;$mtbf_mar = 0;$mtbf_apr = 0;$mtbf_may = 0;$mtbf_jun = 0;
            $mtbf_jul = 0;$mtbf_aug = 0;$mtbf_sep = 0;$mtbf_oct = 0;$mtbf_nov = 0;$mtbf_dec = 0;
        }

        $targets= ProductionUptime::where('year',$year)->whereLine($line)->whereType(7)->first();
        if($targets)
        {
            $target_jan = $targets->jan; $target_feb = $targets->feb; $target_mar = $targets->mar;
            $target_apr = $targets->apr; $target_may = $targets->may; $target_jun = $targets->jun; 
            $target_jul = $targets->jul; $target_aug = $targets->aug; $target_sep = $targets->sep;
            $target_oct = $targets->oct; $target_nov = $targets->nov; $target_dec = $targets->dec;

        }else{
            $target_jan = 0;$target_feb = 0;$target_mar = 0;$target_apr = 0;$target_may = 0;$target_jun = 0;
            $target_jul = 0;$target_aug = 0;$target_sep = 0;$target_oct = 0;$target_nov = 0;$target_dec = 0;
        }

        foreach($product as $keys => $products)
        {
            $sum = 0;
            $product_form = ProductType::whereId($products->id)->with('product')->whereLocation($line)->first();
                foreach($month as $key => $value)
                {
                    $bk = BreakdownModule::select('product_type',DB::raw('sum(downtime_minute) as total'))->whereProductType($products->id)->whereRaw('MONTH(date) = ?',[$key])->whereRaw('YEAR(date) = ?',[$year])->groupBy('product_type')->first();
                    if($bk)
                    {
                        //$report[$keys][$value] = $bk->total;
                        $sum +=  $bk->total;
                       
                            $totalsum += $sum;
                            switch ($key) {
                                case "1":
                                    $count1 += 1;
                                    $count_sum += 1;
                                    $total1 += $bk->total;
                                    break;
                                case "2":
                                    $count2 += 1;
                                    $count_sum += 1;
                                    $total2 += $bk->total;
                                    break;
                                case "3":
                                    $count3 += 1;
                                    $count_sum += 1;
                                    $total3 += $bk->total;
                                    break;
                                case "4":
                                    $count4 += 1;
                                    $count_sum += 1;
                                    $total4 += $bk->total;
                                    break;
                                case "5":
                                    $count5 += 1;
                                    $count_sum += 1;
                                    $total5 += $bk->total;
                                    break;
                                case "6":
                                    $count6 += 1;
                                    $count_sum += 1;
                                    $total6 += $bk->total;
                                    break;
                                case "7":
                                    $count7 += 1;
                                    $count_sum += 1;
                                    $total7 += $bk->total;
                                    break;
                                case "8":
                                    $count8 += 1;
                                    $count_sum += 1;
                                    $total8 += $bk->total;
                                    break;
                                case "9":
                                    $count9 += 1;
                                    $count_sum += 1;
                                    $total9 += $bk->total;
                                    break;
                                case "10":
                                    $count10 += 1;
                                    $count_sum += 1;
                                    $total10 += $bk->total;
                                    break;
                                case "11":
                                    $count11 += 1;
                                    $count_sum += 1;
                                    $total11 += $bk->total;
                                    break;
                                case "12":
                                    $count12 += 1;
                                    $count_sum += 1;
                                    $total12 += $bk->total;
                                    break;
                            }
                    }else
                    {
                       // $report[$keys][$value] ="";
                    }
                }
               
        }
            /*--------------------------MTBF - Mean Time Before Failure (days)-------------------*/

            if(($mtbf_jan > 0 ) && ($total1 > 0))
            {
                $value1 = $mtbf_jan/1010/$total1+1;
                $mtbf['jan'] = round($value1);
            }else{
                $mtbf['jan'] = 0;
            }

            if(($mtbf_feb > 0) && ($total2 > 0))
            {
                $value2 = $mtbf_feb/1010/$total2+1;
                $mtbf['feb'] = round($value2);
            }else{
                $mtbf['feb'] = 0;
            }

            if(($mtbf_mar > 0 ) && ($total3 > 0))
            {
                $value3 = $mtbf_mar/1010/$total3+1;
                $mtbf['mar'] = round($value3);
            }else{
                $mtbf['mar'] = 0;
            }

            if(($mtbf_apr > 0) && ($total4 > 0))
            {
                $value4 = $mtbf_apr/1010/$total4+1;
                $mtbf['apr'] = round($value4);
            }else{
                $mtbf['apr'] = 0;
            }

            if(($mtbf_may > 0) && ($total5 > 0))
            {
                $value5 = $mtbf_may/1010/$total5+1;
                $mtbf['may'] = round($value5);
            }else{
                $mtbf['may'] = 0;
            }

            if(($mtbf_jun > 0) && ($total6 > 0))
            {
                $value6 = $mtbf_jun/1010/$total6+1;
                $mtbf['jun'] = round($value6);
            }else{
                $mtbf['jun'] = 0;
            }

            if(($mtbf_jul > 0) && ($total7 > 0))
            {
                $value7 = $mtbf_jul/1010/$total7+1;
                $mtbf['jul'] = round($value7);
            }else{
                $mtbf['jul'] = 0;
            }

            if(($mtbf_aug > 0) && ($total8 > 0))
            {
                $value8 = $mtbf_aug/1010/$total8+1;
                $mtbf['aug'] = round($value8);
            }else{
                $mtbf['aug'] = 0;
            }

            if(($mtbf_sep > 0) && ($total9 > 0))
            {
                $value9 = $mtbf_sep/1010/$total9+1;
                $mtbf['sep'] = round($value9);
            }else{
                $mtbf['sep'] = 0;
            }

            if(($mtbf_oct > 0) && ($total10 > 0))
            {
                $value10 = $mtbf_oct/1010/$total10+1;
                $mtbf['oct'] = round($value10);
            }else{
                $mtbf['oct'] = 0;
            }

            if(($mtbf_nov > 0) && ($total11 > 0))
            {
                $value11 = $mtbf_nov/1010/$total11+1;
                $mtbf['nov'] = round($value11);
            }else{
                $mtbf['nov'] = 0;
            }

            if(($mtbf_dec > 0) && ($total12 > 0))
            {
                $value12 = $mtbf_dec/1010/$total12+1;
                $mtbf['dec'] = round($value12);
            }else{
                $mtbf['dec'] = 0;
            }

             /*---------------------MTTR - Mean Time To Repair (mins) ------------*/

            if(($count1 > 0 ) && ($total1 > 0))
            {
                $val1 = $total1/$count1;
                $mttr['jan'] = round($val1);
            }else{
                $mttr['jan'] = 0;
            }

            if(($count2 > 0) && ($total2 > 0))
            {
                $val2 = $total2/$count2;
                $mttr['feb'] = round($val2);
            }else{
                $mttr['feb'] = 0;
            }

            if(($count3 > 0 ) && ($total3 > 0))
            {
                $val3 = $total3/$count3;
                $mttr['mar'] = round($val3);
            }else{
                $mttr['mar'] = 0;
            }

            if(($count4 > 0) && ($total4 > 0))
            {
                $val4 = $total4/$count4;
                $mttr['apr'] = round($val4);
            }else{
                $mttr['apr'] = 0;
            }

            if(($count5 > 0) && ($total5 > 0))
            {
                $val5 = $total5/$count5;
                $mttr['may'] = round($val5);
            }else{
                $mttr['may'] = 0;
            }

            if(($count6 > 0) && ($total6 > 0))
            {
                $val6 = $total6/$count6;
                $mttr['jun'] = round($val6);
            }else{
                $mttr['jun'] = 0;
            }

            if(($count7 > 0) && ($total7 > 0))
            {
                $val7 = $total7/$count7;
                $mttr['jul'] = round($val7);
            }else{
                $mttr['jul'] = 0;
            }

            if(($count8 > 0) && ($total8 > 0))
            {
                $val8 = $total8/$count8;
                $mttr['aug'] = round($val8);
            }else{
                $mttr['aug'] = 0;
            }

            if(($count9 > 0) && ($total9 > 0))
            {
                $val9 = $total9/$count9;
                $mttr['sep'] = round($val9);
            }else{
                $mttr['sep'] = 0;
            }

            if(($count10 > 0) && ($total10 > 0))
            {
                $val10 = $total10/$count10;
                $mtbf['oct'] = round($val10);
            }else{
                $mttr['oct'] = 0;
            }

            if(($count11 > 0) && ($total11 > 0))
            {
                $val11 = $total11/$count11;
                $mttr['nov'] = round($val11);
            }else{
                $mttr['nov'] = 0;
            }

            if(($count12 > 0) && ($total12 > 0))
            {
                $val12 = $total12/$count12;
                $mttr['dec'] = round($val12);
            }else{
                $mttr['dec'] = 0;
            }

            /*---------------------Machine Uptime in % ------------*/

            if(($mtbf_jan > 0 ) && ($total1 > 0))
            {
                $per1 = 100-$total1/$mtbf_jan*100;
                $per['jan'] = round($per1);
            }else{
                $per['jan'] = 0;
            }

            if(($mtbf_feb > 0) && ($total2 > 0))
            {
                $per2 = 100-$total2/$mtbf_feb*100;
                $per['feb'] = round($per2);
            }else{
                $per['feb'] = 0;
            }

            if(($mtbf_mar > 0 ) && ($total3 > 0))
            {
                $per3 = 100-$total3/$mtbf_mar*100;
                $per['mar'] = round($per3);
            }else{
                $per['mar'] = 0;
            }

            if(($mtbf_apr > 0) && ($total4 > 0))
            {
                $per4 = 100-$total4/$mtbf_apr*100;
                $per['apr'] = round($per4);
            }else{
                $per['apr'] = 0;
            }

            if(($mtbf_may > 0) && ($total5 > 0))
            {
                $per5 = 100-$total5/$mtbf_may*100;
                $per['may'] = round($per5);
            }else{
                $per['may'] = 0;
            }

            if(($mtbf_jun > 0) && ($total6 > 0))
            {
                $per6 = 100-$total6/$mtbf_jun*100;
                $per['jun'] = round($per6);
            }else{
                $per['jun'] = 0;
            }

            if(($mtbf_jul > 0) && ($total7 > 0))
            {
                $per7 = 100-$total7/$mtbf_jul*100;
                $per['jul'] = round($per7);
            }else{
                $per['jul'] = 0;
            }

            if(($mtbf_aug > 0) && ($total8 > 0))
            {
                $per8 = 100-$total8/$mtbf_aug*100;
                $per['aug'] = round($per8);
            }else{
                $per['aug'] = 0;
            }

            if(($mtbf_sep > 0) && ($total9 > 0))
            {
                $per9 = 100-$total9/$mtbf_sep*100;
                $per['sep'] = round($per9);
            }else{
                $per['sep'] = 0;
            }

            if(($mtbf_oct > 0) && ($total10 > 0))
            {
                $per10 = 100-$total10/$mtbf_oct*100;
                $per['oct'] = round($per10);
            }else{
                $per['oct'] = 0;
            }

            if(($mtbf_nov > 0) && ($total11 > 0))
            {
                $per11 = 100-$total11/$mtbf_nov*100;
                $per['nov'] = round($value11);
            }else{
                $per['nov'] = 0;
            }

            if(($mtbf_dec > 0) && ($total12 > 0))
            {
                $per12 = 100-$total12/$mtbf_dec*100;
                $per['dec'] = round($per12);
            }else{
                $per['dec'] = 0;
            }

            $total['jan'] = $total1; $total['feb'] = $total2;  $total['mar'] = $total3;  $total['apr'] = $total4;
            $total['may'] = $total5; $total['jun'] = $total6;  $total['jul'] = $total7;  $total['aug'] = $total8;
            $total['sep'] = $total9; $total['oct'] = $total10; $total['nov'] = $total11; $total['dec'] = $total12;
            $total['sum'] = $totalsum;

            $production['jan'] = $mtbf_jan;  $production['feb'] = $mtbf_feb;  $production['mar'] = $mtbf_mar;
            $production['apr'] = $mtbf_apr;  $production['may'] = $mtbf_may;  $production['jun'] = $mtbf_jun;
            $production['jul'] = $mtbf_jul;  $production['aug'] = $mtbf_aug;  $production['sep'] = $mtbf_sep;
            $production['oct'] = $mtbf_oct;  $production['nov'] = $mtbf_nov;  $production['dec'] = $mtbf_dec;
            $production['sum'] = 0;

            $target['jan'] = $target_jan;  $target['feb'] = $target_feb;  $target['mar'] = $target_mar;
            $target['apr'] = $target_apr;  $target['may'] = $target_may;  $target['jun'] = $target_jun;
            $target['jul'] = $target_jul;  $target['aug'] = $target_aug;  $target['sep'] = $target_sep;
            $target['oct'] = $target_oct;  $target['nov'] = $target_nov;  $target['dec'] = $target_dec;
            $target['sum'] = 0;

            $count['jan_count'] = $count1;  $count['feb_count'] = $count2;  $count['mar_count'] = $count3;
            $count['apr_count'] = $count4;  $count['may_count'] = $count5;  $count['jun_count'] = $count6;
            $count['jul_count'] = $count7;  $count['aug_count'] = $count8;  $count['sep_count'] = $count9;
            $count['oct_count'] = $count10; $count['nov_count'] = $count11; $count['dec_count'] = $count12;
            $count['sum']       = 0;
            
            $response_data['production']    =  $production;
            $response_data['target']        =  $target;
            $response_data['per']           =  $per;
            $response_data['mtbf']          =  $mtbf;
            $response_data['mttr']          =  $mttr;
            $response_data['count']         = $count;
            $response_data['total']         = $total;
            //dd(count($report));
            // $this->totalCount = count($production);
            //dd(count($report)+2);
            //dd($production, $per, $mtbf, $mttr, $count, $total);
        return view('admin.exports.monthdt-report', ['production' => $production, 'per' => $per, 'mtbf' => $mtbf, 'mttr' => $mttr, 'count' => $count, 'total' => $total, 'target' => $target, 'selectedYear' => $this->getYear]);
    }

}
