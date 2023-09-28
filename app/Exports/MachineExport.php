<?php

namespace App\Exports;

use App\BreakdownModule;
use App\ProductType;
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

class MachineExport implements FromView, ShouldAutoSize, WithTitle, WithCustomStartCell, WithEvents 
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
        return 'Machine Downtime List';
    }

    public function registerEvents(): array
    {
        return [
           
            AfterSheet::class    => function(AfterSheet $event) {

                // page formatting (orientation and size)
                $event->sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

                $event->sheet->getPageSetup()->setFitToWidth(1);
                
                $totalAllBorder = 'A1:O'.$this->totalCount;

                $event->sheet->getStyle($totalAllBorder)->applyFromArray([
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
                    ],
                ]);     
                
                $event->sheet->getStyle('A1:O2')->applyFromArray([ 
                        'font' => [
                            'name'      =>  'Times Roman',
                            'size'      =>  12,
                            'bold'      =>  true
                        ],
                    ]
                );

                $event->sheet->getStyle('A2:O2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('d8d8d8');

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

        $total1 = 0;$total2 = 0;$total3 = 0;$total4 = 0;$total5 = 0;$total6 = 0; $total7 = 0;$total8 = 0;$total9 = 0; 
        $total10 = 0;$total11 = 0;$total12 = 0;$totalsum = 0;

        $product = ProductType::whereLocation($line)->get();
    
        foreach($product as $keys => $products)
        {
            $sum = 0;
            $product_form = ProductType::whereId($products->id)->with('product')->whereLocation($line)->first();
            $report[$keys]['name'] = isset($product_form->product) ?$product_form->product->name : "";
            $report[$keys]['station'] = $product_form->station;
                foreach($month as $key => $value)
                {
                    $bk = BreakdownModule::select('product_type',DB::raw('sum(downtime_minute) as total'))->whereProductType($products->id)->whereRaw('MONTH(date) = ?',[$key])->whereRaw('YEAR(date) = ?',[$year])->groupBy('product_type')->first();
                    if($bk)
                    {
                        $report[$keys][$value] = $bk->total;
                        $sum +=  $bk->total;
                       
                            $totalsum += $sum;
                            switch ($key) {
                                case "1":
                                    $total1 += $bk->total;
                                    break;
                                case "2":
                                    $total2 += $bk->total;
                                    break;
                                case "3":
                                    $total3 += $bk->total;
                                    break;
                                case "4":
                                    $total4 += $bk->total;
                                    break;
                                case "5":
                                    $total5 += $bk->total;
                                    break;
                                case "6":
                                    $total6 += $bk->total;
                                    break;
                                case "7":
                                    $total7 += $bk->total;
                                    break;
                                case "8":
                                    $total8 += $bk->total;
                                    break;
                                case "9":
                                    $total9 += $bk->total;
                                    break;
                                case "10":
                                    $total10 += $bk->total;
                                    break;
                                case "11":
                                    $total11 += $bk->total;
                                    break;
                                case "12":
                                    $total12 += $bk->total;
                                    break;
                            }
                    }else
                    {
                        $report[$keys][$value] ="";
                    }
                }
                $report[$keys]['sum'] = $sum;
        }
            $total['jan'] = $total1;  $total['feb'] = $total2;   $total['mar'] = $total3;$total['apr'] = $total4; $total['may'] = $total5;
            $total['jun'] = $total6;  $total['jul'] = $total7;   $total['aug'] = $total8;$total['sep'] = $total9; $total['oct'] = $total10;
            $total['nov'] = $total11; $total['dec'] = $total12;  $total['sum'] = $totalsum;

            $response_data['data'] =  $report;
            $response_data['total'] = $total;
            //dd(count($report));
            $this->totalCount = count($report)+3;
            //dd(count($report)+2);
            //dd($total);
        return view('admin.exports.machine', ['reports' => $report, 'totals' => $total]);
    }
}
