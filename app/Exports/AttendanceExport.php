<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AttendanceExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle, WithEvents
{
    protected $reportData;
    protected $month;
    protected $year;
    protected $monthName;

    public function __construct($reportData, $month, $year)
    {
        $this->reportData = $reportData;
        $this->month = $month;
        $this->year = $year;
        
        $months = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
            '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];
        $this->monthName = $months[$month] ?? '';
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = collect();
        
        foreach($this->reportData as $index => $item) {
            $employee = $item['employee'];
            
            $data->push([
                $index + 1,
                $employee->nik,
                $employee->nip,
                $employee->user->name,
                $employee->position->title,
                $employee->department->name,
                (int) $item['total_days'],
                (int) $item['hadir'],
                (int) $item['terlambat'],
                (int) $item['izin'],
                (int) $item['sakit'],
                (int) $item['alpha'],
                (int) $item['cuti'],
            ]);
        }
        
        return $data;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'NIK',
            'NIP',
            'Nama Karyawan',
            'Jabatan',
            'Departemen',
            'Total Hari',
            'Hadir',
            'Terlambat',
            'Izin',
            'Sakit',
            'Alpha',
            'Cuti',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style untuk header
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 15,  // NIK
            'C' => 15,  // NIP
            'D' => 25,  // Nama
            'E' => 20,  // Jabatan
            'F' => 20,  // Departemen
            'G' => 12,  // Total Hari
            'H' => 10,  // Hadir
            'I' => 10,  // Terlambat
            'J' => 10,  // Izin
            'K' => 10,  // Sakit
            'L' => 10,  // Alpha
            'M' => 10,  // Cuti
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return "Absensi {$this->monthName} {$this->year}";
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();

                // Add borders to all cells
                $sheet->getStyle("A1:{$lastColumn}{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Center align untuk kolom angka
                $sheet->getStyle("A2:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("G2:M{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Add total row
                $totalRow = $lastRow + 1;
                $sheet->setCellValue("F{$totalRow}", 'TOTAL');
                $sheet->setCellValue("G{$totalRow}", "=SUM(G2:G{$lastRow})");
                $sheet->setCellValue("H{$totalRow}", "=SUM(H2:H{$lastRow})");
                $sheet->setCellValue("I{$totalRow}", "=SUM(I2:I{$lastRow})");
                $sheet->setCellValue("J{$totalRow}", "=SUM(J2:J{$lastRow})");
                $sheet->setCellValue("K{$totalRow}", "=SUM(K2:K{$lastRow})");
                $sheet->setCellValue("L{$totalRow}", "=SUM(L2:L{$lastRow})");
                $sheet->setCellValue("M{$totalRow}", "=SUM(M2:M{$lastRow})");

                // Style total row
                $sheet->getStyle("F{$totalRow}:M{$totalRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E7E6E6'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // Freeze first row
                $sheet->freezePane('A2');

                // Auto-height untuk baris
                $sheet->getRowDimension(1)->setRowHeight(25);
            },
        ];
    }
}