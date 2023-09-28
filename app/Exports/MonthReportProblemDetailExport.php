<?php

namespace App\Exports;

use App\BreakdownModule;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Support\Facades\DB;

class MonthReportProblemDetailExport implements FromView, ShouldAutoSize, WithTitle, WithEvents 
{

	protected $line,$month,$totalRow;
	public function __construct($request)
	{
		$this->line = $request->exportsLineStatus;
       	$this->month = $request->exportsMonth;
	}

	public function title(): string
    {
        return 'Month Report Problem Details';
    }

    /**
     * @return array
    */
    public function registerEvents(): array
    {
        return [
           
            AfterSheet::class    => function(AfterSheet $event) {
                $totalBorder = 'A1:J'.$this->totalRow;
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
                    'D1:G1',
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
                    'H1:J1',
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
                    'A2:J2',
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
    	$dateMonthArray = explode('-', $this->month);
        $monthdata  = $dateMonthArray[0];
        $yeardata   = $dateMonthArray[1];
        $linedata   = $this->line;
        $query = BreakdownModule::with(['equipment','equipment.product','problemdetail'])
        		->select('product_type',DB::raw('sum(downtime_minute) as total'))
        		->whereRaw('MONTH(date) = ?',[$monthdata])
        		->whereRaw('YEAR(date) = ?',[$yeardata])
        		->whereHas('equipment', function ($query) use($linedata) {
            		$query->where('location', $linedata);
        		})->groupBy('product_type')->get();
        $data  = $query;
        //dd($data);
        $this->totalRow  = count($query)+4;
        return view('admin.exports.monthreport-problemdetails', ['schedule' => $data,'month'=>$this->month]);
    }
}
