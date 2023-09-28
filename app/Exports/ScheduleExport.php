<?php

namespace App\Exports;

use App\Schedule;
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

class ScheduleExport implements FromView, ShouldAutoSize, WithTitle, WithCustomStartCell, WithEvents 
{
    protected $status,$module,$start_date,$end_date,$manager,$totalRow;
    public function __construct($request)
    {
       $this->status = $request->exportsStatus;
       $this->module = $request->exportsModule;
       $this->manager = $request->exportsManager;
       $this->start_date = $request->exportsStartDate;
       $this->end_date = $request->exportsEndDate;
    } 

    // public function drawings()
    // {
    //     $drawing = new Drawing();
    //     $drawing->setName('Logo');
    //     $drawing->setDescription('This is my logo');
    //     $drawing->setPath(public_path('/common/img/logo/logo-small.png'));
    //     $drawing->setHeight(70);
    //     return $drawing;
    // }

    

    public function title(): string
    {
        return 'Schedule List';
    }

   /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
           
            AfterSheet::class    => function(AfterSheet $event) {
                $totalBorder = 'A1:K'.$this->totalRow;
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
                    'D1:H1',
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
                    'I1:K1',
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
                    'A2:K2',
                    [
                        'font' => [
                            'name'      =>  'Times Roman',
                            'size'      =>  12,
                            'bold'      =>  true
                        ],
                    ]
                );
                
            },
        ];
    }

    public function startCell(): string
    {
        return 'A10';
    }


    public function view(): View
    {
        $query = Schedule::with(['equipment.product','users:id,name','manager:id,name','moduleType:id,name'])->select();
        if(!empty($this->status)){
            $query->where('active',(int)$this->status);
        }
        elseif(!empty($this->status) && $this->status==0)
        {
            $query->where('active',0);
        }
        if(!empty($this->module)){
            $query->whereModuleType((int)$this->module);
        }
        if(!empty($this->manager)){
            $query->whereEngineerId((int)$this->manager);
        }
        if(!empty($this->start_date)){
            $query->whereDate('created_at', '>=', $this->start_date);
        }
        if(!empty($this->end_date)){
            $query->whereDate('created_at', '<=', $this->end_date);
        }
        $data  = $query->get();
        $this->totalRow =$data->count()+2; 
        return view('admin.exports.schedule', ['schedule' => $data]);
    }

}
