<?php

namespace App\Exports;

use App\InventoryStock;
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

class InventoryStockExport implements FromView, ShouldAutoSize, WithTitle, WithEvents 
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $totalCount;
    public function title(): string
    {
        return 'Inventory Stock List';
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {

        return [
            

            AfterSheet::class    => function(AfterSheet $event) {

                $totalAllBorder = 'A1:D'.$this->totalCount;

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
                    'C1:D1',
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
                    'A4:D200',
                    [                        
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                    ]
                );       

                $event->sheet->styleCells(
                    'A2:D2',
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
                    'A3:D3',
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
                    'A4:D4',
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
                
                $event->sheet->getStyle('A4:D4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('d8d8d8');

                $event->sheet->getRowDimension('2')->setRowHeight(20);
                $event->sheet->getRowDimension('3')->setRowHeight(20);
                $event->sheet->getRowDimension('4')->setRowHeight(30);
            },
        ];
    }

    public function view(): View
    {
        $data  = InventoryStock::all();
        $this->totalCount = count($data)+4;
        return view('admin.exports.inventory_stock', ['inventory_stock' => $data]);
    }
}
