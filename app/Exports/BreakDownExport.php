<?php

namespace App\Exports;

use App\BreakdownModule;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Facades\DB;

class BreakDownExport implements WithEvents, ShouldAutoSize, FromView, WithTitle
{
    protected $status,$module,$start_date,$end_date,$manager,$totalRow;
    public function __construct($request)
    {
       $this->status = $request->exportsStatus;
       $this->start_date = $request->exportsStartDate;
       $this->end_date = $request->exportsEndDate;
    } 
    public function title(): string
    {
        return 'BreakDown Daily Report List';
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
                    'C1:K1',
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

	public function view(): View
    {
        $query = BreakdownModule::with(['equipment.product','schedule.manager','users'])->select();
        if(!empty($this->status)){
            $query->where('active',$this->status);
        }
        elseif(!empty($this->status) && $this->status==0)
        {
        	$query->where('active',0);
        }
        if(!empty($this->start_date)){
            $query->whereDate('created_at', '>=', $this->start_date);
        }
        if(!empty($this->end_date)){
            $query->whereDate('created_at', '<=', $this->end_date);
        }
        $data  = $query->get();
        $this->totalRow = $data->count()+2;
        return view('admin.exports.report_breakdown', ['schedule' => $data,'title'=>'BreakDown Daily Report List']);
    }
}
