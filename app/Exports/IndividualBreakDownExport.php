<?php

namespace App\Exports;

use App\BreakdownModule;
use App\BreakdownDetail;
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

class IndividualBreakDownExport implements FromView, ShouldAutoSize, WithTitle, WithEvents
{
	protected $breakdownId,$footerCount,$dataRow,$startRow=5;
    public function __construct($request)
    {
        $this->breakdownId = $request->individualId;
    }

    public function title(): string
    {
        return 'Individual BreakDown Daily';
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function registerEvents(): array
    {
        //$this->dataRow = "A".$this->footerCount.":O".$this->footerCount;
        return [
           
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->getStyle('A1:S8')->applyFromArray([
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
                    'D1:P1',
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
                    'Q1:S1',
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
                    'A5:S5',
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
                    'A2:S2',
                    [
                        'font' => [
                            'name'      =>  'Times Roman',
                            'size'      =>  12,
                            'bold'      =>  true
                        ],
                    ]
                );
                $event->sheet->styleCells(
                    'A3:S3',
                    [
                        'font' => [
                            'name'      =>  'Times Roman',
                            'size'      =>  12,
                            'bold'      =>  true,
                            'color'		=> ['rgb'=>'000000']
                        ],
                        'alignment' => [
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        
                    ]
                );
                $event->sheet->styleCells(
                    'D2:G2',
                    [
                        'font' => [
                            'name'      =>  'Times Roman',
                            'size'      =>  12,
                            'bold'      =>  true,
                            'color'		=> ['rgb'=>'000000']
                        ],
                    ]
                );
                
                $event->sheet->getRowDimension('3')->setRowHeight(30);
                $event->sheet->getRowDimension('4')->setRowHeight(100);
                //$event->sheet->getRowDimension('7')->setRowHeight(30);
                $event->sheet->getStyle('D2:G2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('d8d8d8');
                $event->sheet->getStyle('A5:S5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('d8d8d8');
       			$event->sheet->getStyle('A3:S3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('a5a5a5');
                $event->sheet->getHeaderFooter()->setOddFooter('&LDM-P18-F05');
                
            },
        ];
       
    }
    public function view(): View
    {
        $data = BreakdownModule::with(['equipment.product','schedule.manager','users'])->find($this->breakdownId);
        $details = BreakdownDetail::whereBreakdownId($this->breakdownId)->get();
        return view('admin.exports.dailyindividual_breakdown', ['schedule' => $data,'details'=>$details,'title' => 'Daily Individual BreakDown Report']);
    }

    public function getList()
    {
        $query = BreakdownModule::with(['equipment.product','schedule.manager','users'])->find($this->breakdownId);
        return $query->get(); 
    }
}
