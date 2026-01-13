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

class PayrollExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle, WithEvents
{
    protected $payrolls;
    protected $month;
    protected $year;

    public function __construct($payrolls, $month, $year)
    {
        $this->payrolls = $payrolls;
        $this->month = $month;
        $this->year = $year;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = collect();
        
        foreach($this->payrolls as $index => $payroll) {
            $data->push([
                $index + 1,
                $payroll->employee->nik,
                $payroll->employee->nip,
                $payroll->employee->user->name,
                $payroll->employee->position->title,
                $payroll->employee->department->name,
                (float) $payroll->basic_salary,
                (float) $payroll->total_allowance,
                (float) $payroll->total_deduction,
                (float) $payroll->net_salary,
                $payroll->status === 'paid' ? 'Dibayar' : 'Draft',
                $payroll->payment_date ? $payroll->payment_date->format('d/m/Y') : '-',
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
            'Gaji Pokok',
            'Total Tunjangan',
            'Total Potongan',
            'Gaji Bersih',
            'Status',
            'Tanggal Bayar',
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
            'G' => 18,  // Gaji Pokok
            'H' => 18,  // Tunjangan
            'I' => 18,  // Potongan
            'J' => 18,  // Gaji Bersih
            'K' => 12,  // Status
            'L' => 15,  // Tanggal Bayar
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return "Payroll {$this->month} {$this->year}";
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

                // Center align untuk kolom tertentu
                $sheet->getStyle("A2:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("K2:K{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("L2:L{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Format currency untuk kolom gaji
                $sheet->getStyle("G2:J{$lastRow}")->getNumberFormat()
                     ->setFormatCode('#,##0.00');

                // Add total row
                $totalRow = $lastRow + 1;
                $sheet->setCellValue("F{$totalRow}", 'TOTAL');
                $sheet->setCellValue("G{$totalRow}", "=SUM(G2:G{$lastRow})");
                $sheet->setCellValue("H{$totalRow}", "=SUM(H2:H{$lastRow})");
                $sheet->setCellValue("I{$totalRow}", "=SUM(I2:I{$lastRow})");
                $sheet->setCellValue("J{$totalRow}", "=SUM(J2:J{$lastRow})");

                // Style total row
                $sheet->getStyle("F{$totalRow}:J{$totalRow}")->applyFromArray([
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
                ]);

                // Format currency for total row
                $sheet->getStyle("G{$totalRow}:J{$totalRow}")->getNumberFormat()
                     ->setFormatCode('#,##0.00');

                // Freeze first row
                $sheet->freezePane('A2');
            },
        ];
    }
}