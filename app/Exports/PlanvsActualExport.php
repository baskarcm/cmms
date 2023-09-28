<?php

namespace App\Exports;

use App\Schedule;
use Maatwebsite\Excel\Concerns\FromCollection;
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

class PlanvsActualExport implements FromView, ShouldAutoSize, WithTitle, WithCustomStartCell, WithEvents 
{
    /**
    * @return \Illuminate\Support\Collection
    */
    /*public function collection()
    {
        return Schedule::all();
    }*/

    protected $status,$getMonth,$end_date,$daysCount,$totalCount,$lastColum;
    public function __construct($request)
    {
       $this->status = $request->exportsStatus;
       $this->getMonth = $request->getMonth;
    }   

    public function title(): string
    {
        return 'Plan vs Actual List';
    }

    public function setPrintFitToWidth()
    {
        $this->sheet->getPageSetup()->setFitToWidth(1);    
        $this->sheet->getPageSetup()->setFitToHeight(0);    
    }
   /**
     * @return array
     */
    public function registerEvents(): array
    {

        return [
           
            AfterSheet::class    => function(AfterSheet $event) {

                // page formatting (orientation and size)
                $event->sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                /*$event->sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);*/

                $event->sheet->getHeaderFooter()->setOddFooter('&LDM-P18-F11');
                
                /*$event->sheet->getPageSetup()->setFitToWidth(1);
                $event->sheet->getPageSetup()->setFitToHeight(0);*/

                $totalAllBorder = 'A1:'.$this->lastColum.$this->totalCount;
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
                    ]
                ]);

                $event->sheet->styleCells(
                    'C1:Q1',
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
                    'A3:G200',
                    [                        
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                    ]
                );       

                $event->sheet->styleCells(
                    'R1:RF1',
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
                    'A2:BO2',
                    [                        
                        'font' => [
                            'name'      =>  'Times Roman',
                            'size'      =>  12,
                            'bold'      =>  true,
                            'color' => ['argb' => '000000'],
                        ],
                        'alignment' => [
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]
                );

                $event->sheet->styleCells(
                    'F2:BO2',
                    [                     
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ]
                    ],
                );

                $event->sheet->styleCells(
                    'F3:BO3',
                    [                     
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ]
                    ],
                );

                // $event->sheet->setCellValue('F3', 'P');
                // $event->sheet->setCellValue('G3', 'A');

                $number =1;
                if($this->daysCount == 28)
                {
                    $lastChar = 'BJ';
                    
                    $event->sheet->styleCells(
                        'A2:BI3',
                        [
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '030048'],
                                ],                                
                            ],
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'color' => ['argb' => 'd8d8d8']
                            ]
                        ],
                    );   
                                        
                }elseif($this->daysCount == 29){

                    $lastChar = 'BL';  
                    
                    $event->sheet->styleCells(
                        'A2:BK3',
                        [
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '030048'],
                                ],
                            ],
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'color' => ['argb' => 'd8d8d8']
                            ]
                        ],
                    );             

                }elseif($this->daysCount == 30){
                    
                    $lastChar = 'BN';
                   
                    $event->sheet->styleCells(
                        'F2:BM2',
                        [
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '030048'],
                                ],
                            ],
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'color' => ['argb' => 'd8d8d8']
                            ]
                        ],
                    );                     

                }elseif($this->daysCount == 31){
                    
                    $lastChar = 'BP';
                    
                    $event->sheet->styleCells(
                        'F2:BO2',
                        [
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '030048'],
                                ],
                            ],
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'color' => ['argb' => 'd8d8d8']
                            ]
                        ],
                    );                     
                }
                for ($i = 'F'; $i !== $lastChar; $i++){
                    if($number%2==0)
                    {
                        $event->sheet->setCellValue($i.'3', 'A');
                        $databorder = $i.'3';
                        $event->sheet->styleCells(
                            $databorder,
                            [
                                'borders' => [
                                    'allBorders' => [
                                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                        'color' => ['argb' => '030048'],
                                    ],
                                ],
                                'fill' => [
                                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                    'color' => ['argb' => 'd8d8d8']
                                ]
                            ],
                        );                        
                    }
                    else
                    {
                        $event->sheet->setCellValue($i.'3', 'P');
                        $databorder = $i.'3';
                        $event->sheet->styleCells(
                            $databorder,
                            [
                                'borders' => [
                                    'allBorders' => [
                                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                        'color' => ['argb' => '030048'],
                                    ],
                                ],
                                'fill' => [
                                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                    'color' => ['argb' => 'd8d8d8']
                                ]
                            ],
                        );                        
                    }
                    $number++;
                }


                $event->sheet->getStyle('A2:E2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('d8d8d8');                

                $event->sheet->getRowDimension('2')->setRowHeight(30);

                $event->sheet->mergeCells('A2:A3');
                $event->sheet->mergeCells('B2:B3');
                $event->sheet->mergeCells('B2:B3');
                $event->sheet->mergeCells('C2:C3');
                $event->sheet->mergeCells('D2:D3');
                $event->sheet->mergeCells('E2:E3');
                
            },
        ];
    }


    public function startCell(): string
    {
        return 'A10';
    }

	public function view(): View
    {
        $dateMonthArray = explode('-', $this->getMonth);
        $month = $dateMonthArray[0];
        $year = $dateMonthArray[1];
        $this->daysCount=cal_days_in_month(CAL_GREGORIAN,$month,$year);
        
        $query = Schedule::with(['equipment.product','users:id,name','actualScheduledate'])->whereRaw('MONTH(schedule_date) = ?',[$month])->whereRaw('YEAR(schedule_date) = ?',[$year])->select();
        if(!empty($this->status)){
            $query->whereActive($this->status);
        }
        $data  = $query->get();
        $this->totalCount = $data->count()+3;
        if($this->daysCount == 28)
        {
            $this->lastColum = 'BI';
        } elseif ($this->daysCount == 29) {
           $this->lastColum = 'BK';
        } elseif ($this->daysCount == 30) {
            $this->lastColum = 'BM';
        } elseif ($this->daysCount == 31) {
           $this->lastColum = 'BO';
        }


        return view('admin.exports.plan_vs_actual', ['planvsactual' => $data, 'daysCount' => $this->daysCount, 'getMonth' => $this->getMonth]);
    }
}
