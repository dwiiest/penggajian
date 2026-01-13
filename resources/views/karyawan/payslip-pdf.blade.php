<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Slip Gaji - {{ $payroll->month }} {{ $payroll->year }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 15px;
        }
        .company-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .document-title {
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
            color: #333;
        }
        .employee-info {
            margin-bottom: 20px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 5px;
            vertical-align: top;
        }
        .info-label {
            width: 30%;
            color: #666;
        }
        .salary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .salary-table th,
        .salary-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .salary-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .section-title {
            background-color: #e9ecef;
            font-weight: bold;
            padding: 8px !important;
        }
        .total-row {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }
        .earning {
            color: #28a745;
        }
        .deduction {
            color: #dc3545;
        }
        .footer {
            margin-top: 40px;
        }
        .signature-section {
            margin-top: 50px;
        }
        .signature-box {
            width: 45%;
            display: inline-block;
            text-align: center;
        }
        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #333;
            padding-top: 5px;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            color: rgba(0, 0, 0, 0.05);
            z-index: -1;
        }
        .note {
            font-size: 10px;
            color: #666;
            margin-top: 20px;
            padding: 10px;
            border: 1px dashed #ccc;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="watermark">CONFIDENTIAL</div>

    <!-- Header -->
    <div class="header">
        <div class="company-name">PT. NAMA PERUSAHAAN</div>
        <div>Jl. Alamat Perusahaan No. 123, Kota, Provinsi</div>
        <div>Telp: (021) 12345678 | Email: info@perusahaan.com</div>
        <div class="document-title">SLIP GAJI KARYAWAN</div>
        <div>Periode: {{ $payroll->month }} {{ $payroll->year }}</div>
    </div>

    <!-- Employee Information -->
    <div class="employee-info">
        <table class="info-table">
            <tr>
                <td class="info-label">Nama Karyawan</td>
                <td>: {{ $employee->user->name }}</td>
                <td class="info-label">NIP</td>
                <td>: {{ $employee->nip }}</td>
            </tr>
            <tr>
                <td class="info-label">NIK</td>
                <td>: {{ $employee->nik }}</td>
                <td class="info-label">Jabatan</td>
                <td>: {{ $employee->position->title }}</td>
            </tr>
            <tr>
                <td class="info-label">Departemen</td>
                <td>: {{ $employee->department->name }}</td>
                <td class="info-label">Status</td>
                <td>: Karyawan Tetap</td>
            </tr>
        </table>
    </div>

    <!-- Salary Details -->
    <table class="salary-table">
        <thead>
            <tr>
                <th colspan="2" class="text-center">RINCIAN PENGHASILAN DAN POTONGAN</th>
            </tr>
        </thead>
        <tbody>
            <!-- Gaji Pokok -->
            <tr>
                <td><strong>Gaji Pokok</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</strong></td>
            </tr>

            <!-- Tunjangan -->
            <tr>
                <td colspan="2" class="section-title earning">TUNJANGAN</td>
            </tr>
            <tr>
                <td style="padding-left: 20px;">Tunjangan Transport</td>
                <td class="text-right">Rp {{ number_format($employee->position->transport_allowance * $payroll->working_days, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="padding-left: 20px;">Tunjangan Makan</td>
                <td class="text-right">Rp {{ number_format($employee->position->meal_allowance * $payroll->working_days, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Total Tunjangan</strong></td>
                <td class="text-right earning"><strong>Rp {{ number_format($payroll->total_allowance, 0, ',', '.') }}</strong></td>
            </tr>

            <!-- Gross Salary -->
            <tr style="background-color: #e3f2fd;">
                <td><strong>Total Penghasilan Bruto</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($payroll->basic_salary + $payroll->total_allowance, 0, ',', '.') }}</strong></td>
            </tr>

            <!-- Potongan -->
            <tr>
                <td colspan="2" class="section-title deduction">POTONGAN</td>
            </tr>
            @if($payroll->total_deduction > 0)
            <tr>
                <td style="padding-left: 20px;">Potongan Lain-lain</td>
                <td class="text-right">Rp {{ number_format($payroll->total_deduction, 0, ',', '.') }}</td>
            </tr>
            @else
            <tr>
                <td style="padding-left: 20px;" class="text-center" colspan="2">Tidak ada potongan</td>
            </tr>
            @endif
            <tr>
                <td><strong>Total Potongan</strong></td>
                <td class="text-right deduction"><strong>Rp {{ number_format($payroll->total_deduction, 0, ',', '.') }}</strong></td>
            </tr>

            <!-- Net Salary -->
            <tr class="total-row">
                <td><strong>GAJI BERSIH (TAKE HOME PAY)</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <!-- Bank Transfer Info -->
    <div style="margin-top: 20px; padding: 10px; background-color: #f8f9fa; border-left: 4px solid #007bff;">
        <strong>Informasi Transfer:</strong><br>
        Bank: {{ $employee->bank_name ?? '-' }}<br>
        No. Rekening: {{ $employee->account_number ?? '-' }}<br>
        Atas Nama: {{ $employee->user->name }}
        @if($payroll->payment_date)
        <br>Tanggal Pembayaran: {{ \Carbon\Carbon::parse($payroll->payment_date)->locale('id')->isoFormat('dddd, D MMMM Y') }}
        @endif
    </div>

    <!-- Signature Section -->
    <div class="signature-section">
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%; text-align: center;">
                    <div>Mengetahui,</div>
                    <div style="margin-top: 5px;"><strong>HRD Manager</strong></div>
                    <div class="signature-line">
                        <strong>(_________________)</strong>
                    </div>
                </td>
                <td style="width: 50%; text-align: center;">
                    <div>Diterima oleh,</div>
                    <div style="margin-top: 5px;"><strong>Karyawan</strong></div>
                    <div class="signature-line">
                        <strong>{{ $employee->user->name }}</strong>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Note -->
    <div class="note">
        <strong>Catatan:</strong>
        <ul style="margin: 5px 0; padding-left: 20px;">
            <li>Slip gaji ini merupakan bukti sah pembayaran gaji karyawan</li>
            <li>Harap disimpan dengan baik untuk keperluan administrasi</li>
            <li>Dokumen ini dicetak otomatis oleh sistem E-Payroll</li>
            <li>Untuk pertanyaan, hubungi Departemen HRD</li>
        </ul>
        <div style="margin-top: 10px; text-align: right; font-style: italic;">
            Dicetak pada: {{ now()->locale('id')->isoFormat('dddd, D MMMM Y HH:mm') }} WIB
        </div>
    </div>
</body>
</html>

@php
function getWorkingDays($payroll) {
    // This is a helper function to get working days
    // You can implement actual logic based on attendance
    return 22; // Default working days in a month
}
@endphp