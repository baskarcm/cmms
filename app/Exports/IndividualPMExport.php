<?php

namespace App\Exports;

use App\PmModule;
use App\PmDetail;
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

class IndividualPMExport implements FromView, ShouldAutoSize, WithTitle, WithEvents
{
	protected $pmId,$footerCount,$dataRow,$startRow=5,$totalRow;
    public function __construct($request)
    {
        $this->pmId = $request->individualId;
    }

    public function title(): string
    {
        return 'Daily Individual PM Report';
    }

     /**
     * @return array
     */
    public function registerEvents(): array
    {
        $this->dataRow = "A7:G7";
        return [
           
            AfterSheet::class    => function(AfterSheet $event) {
                $totalBorder = 'A1:N8';
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
                    'C1:J1',
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
                    'K1:N1',
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
                    'A5:N5',
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
                    'A3:N3',
                    [
                        'font' => [
                            'name'      =>  'Times Roman',
                            'size'      =>  12,
                            'bold'      =>  true
                        ],
                    ]
                );
                $event->sheet->styleCells(
                    'A3:N3',
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
                    'C2:E2',
                    [
                        'font' => [
                            'name'      =>  'Times Roman',
                            'size'      =>  12,
                            'bold'      =>  true,
                            'color'		=> ['rgb'=>'000000']
                        ],
                    ]
                );
                
                $event->sheet->getRowDimension('2')->setRowHeight(20);
                $event->sheet->getRowDimension('3')->setRowHeight(30);
                $event->sheet->getRowDimension('4')->setRowHeight(100);
                $event->sheet->getStyle('C2:E2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('d8d8d8');
                $event->sheet->getStyle('A5:N5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('d8d8d8');
       			$event->sheet->getStyle('A3:N3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('a5a5a5');
                $event->sheet->getHeaderFooter()->setOddFooter('&LDM-P18-F05');
                $event->sheet->getStyle('D4:E4')->getAlignment()->setWrapText(true);
            },
        ];
       
    }
    public function view(): View
    {
        $data = PmModule::with(['equipment.product','schedule.manager','users'])->find($this->pmId);
        $details = PmDetail::wherePmId($this->pmId)->get();
        //dd($details);
        return view('admin.exports.dailyindividual_pm', ['schedule' => $data,'details'=>$details,'title' => 'Daily Individual PM Report']);
    }

}
