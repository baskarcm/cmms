<?php

namespace App\Exports;

use App\PmModule;
use App\PmDetail;
use App\ProductJudge;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Facades\DB;

class IndividualReportPMExport implements WithEvents, ShouldAutoSize, FromView, WithTitle
{
    protected $pmId,$footerCount=10,$totalRow;
    public function __construct($request)
    {
       $this->pmId = $request->individualId;
    } 

    public function title(): string
    {
        return 'PM Individual Report List';
    }


   /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $totalBorder = 'A1:H'.$this->totalRow;
                $event->sheet->getStyle($totalBorder)->applyFromArray([
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
                ]);
                $event->sheet->styleCells(
                    'C1:H1',
                    [
                        'font' => [
                            'name'      =>  'Times Roman',
                            'size'      =>  12,
                            'bold'      =>  true
                        ],
                        'alignment' => [
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]
                );
                $event->sheet->styleCells(
                    'A2:H4',
                    [
                        'font' => [
                            'name'      =>  'Times Roman',
                            'size'      =>  12,
                            'bold'      =>  true
                        ],
                        'alignment' => [
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]
                );
                $event->sheet->styleCells(
                    'A2:H4',
                    [
                        'font' => [
                            'name'      =>  'Times Roman',
                            'size'      =>  12,
                            'bold'      =>  true
                        ],
                        'alignment' => [
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]
                );
                
                $event->sheet->getRowDimension('2')->setRowHeight(20);
                $event->sheet->getRowDimension('3')->setRowHeight(30);
                $event->sheet->getRowDimension('4')->setRowHeight(30);
                $event->sheet->getHeaderFooter()->setOddFooter('&LDM-P18-F09');
                //$event->sheet->getRowDimension($this->footerCount)->setRowHeight(30);
                $event->sheet->getStyle('A2:H2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('d8d8d8');
                $event->sheet->getStyle('A4:H4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('a5a5a5');
                
            },
        ];
    }

	public function view(): View
    {
    	DB::enableQueryLog(); // Enable query log
        $query = PmModule::with(['equipment.product','schedule','users'])->find($this->pmId);
        if($query)
        {
            $data = $query;
            $detail      = PmDetail::with(['point','items','judge'])->wherePmId($query->id)->whereActive(1)->get();
            $judge       = ProductJudge::whereProductId($query->equipment->product->id)->get();
        }
        $this->totalRow = $detail->count()+8;
        return view('admin.exports.report_pm_individual', ['schedule' => $data,'detail'=>$detail,'judge'=>$judge,'title' => 'Monthly  Inspection Check Sheet For']);
    }
}
