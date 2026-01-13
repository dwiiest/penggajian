<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Slip Gaji - {{ $payroll->employee->user->name }}</title>
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
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }
        .info-label {
            display: table-cell;
            width: 150px;
            font-weight: bold;
        }
        .info-value {
            display: table-cell;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th {
            background-color: #f3f4f6;
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
        }
        table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-row {
            background-color: #f9fafb;
            font-weight: bold;
        }
        .grand-total {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }
        .period-box {
            background-color: #f3f4f6;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }
        .period-box h2 {
            margin: 0;
            color: #333;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>SLIP GAJI KARYAWAN</h1>
        <p>PT. Digital Printivo</p>
        <p>Jl. Antariksa Perusahaan No. 123, Kota Cimahi, Jawa Barat</p>
    </div>

    <div class="period-box">
        <h2>Periode: {{ $payroll->month }} {{ $payroll->year }}</h2>
    </div>

    <div class="info-section">
        <h3 style="margin-bottom: 15px; color: #333;">Informasi Karyawan</h3>
        <div class="info-row">
            <div class="info-label">Nama</div>
            <div class="info-value">: {{ $payroll->employee->user->name }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">NIK</div>
            <div class="info-value">: {{ $payroll->employee->nik }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">NIP</div>
            <div class="info-value">: {{ $payroll->employee->nip }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Jabatan</div>
            <div class="info-value">: {{ $payroll->employee->position->title }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Departemen</div>
            <div class="info-value">: {{ $payroll->employee->department->name }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Keterangan</th>
                <th class="text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>PENGHASILAN</strong></td>
                <td></td>
            </tr>
            <tr>
                <td style="padding-left: 20px;">Gaji Pokok</td>
                <td class="text-right">Rp {{ number_format($breakdown['basic_salary'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>TUNJANGAN</strong></td>
                <td></td>
            </tr>
            <tr>
                <td style="padding-left: 20px;">Tunjangan Transport</td>
                <td class="text-right">Rp {{ number_format($breakdown['transport_allowance'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="padding-left: 20px;">Tunjangan Makan</td>
                <td class="text-right">Rp {{ number_format($breakdown['meal_allowance'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="padding-left: 20px;">Lembur ({{ $overtimes->count() }} kali, {{ $overtimes->sum('total_hours') }} jam)</td>
                <td class="text-right">Rp {{ number_format($breakdown['overtime_pay'], 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td>Total Penghasilan</td>
                <td class="text-right">Rp {{ number_format($breakdown['basic_salary'] + $breakdown['total_allowance'], 0, ',', '.') }}</td>
            </tr>
            @if($breakdown['total_deduction'] > 0)
            <tr>
                <td><strong>POTONGAN</strong></td>
                <td></td>
            </tr>
            <tr class="total-row">
                <td>Total Potongan</td>
                <td class="text-right">Rp {{ number_format($breakdown['total_deduction'], 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr class="grand-total">
                <td><strong>GAJI BERSIH (TAKE HOME PAY)</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($breakdown['net_salary'], 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    @if($overtimes->count() > 0)
    <h3 style="margin-top: 30px; color: #333;">Detail Lembur</h3>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jam Mulai</th>
                <th>Jam Selesai</th>
                <th class="text-center">Durasi (Jam)</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($overtimes as $overtime)
            <tr>
                <td>{{ $overtime->date->format('d/m/Y') }}</td>
                <td>{{ $overtime->start_time }}</td>
                <td>{{ $overtime->end_time }}</td>
                <td class="text-center">{{ $overtime->total_hours }}</td>
                <td class="text-right">Rp {{ number_format($overtime->total_pay, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="4" class="text-right"><strong>Total Lembur</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($overtimes->sum('total_pay'), 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>
    @endif

    <div style="margin-top: 20px; padding: 10px; background-color: #f8f9fa; border-left: 4px solid #007bff;">
        <strong>Informasi Transfer:</strong><br>
        Bank: {{ $payroll->employee->bank_name ?? '-' }}<br>
        No. Rekening: {{ $payroll->employee->account_number ?? '-' }}<br>
        Atas Nama: {{ $payroll->employee->user->name }}
        @if($payroll->payment_date)
        <br>Tanggal Pembayaran: {{ \Carbon\Carbon::parse($payroll->payment_date)->locale('id')->isoFormat('dddd, D MMMM Y') }}
        @endif
    </div>
</body>
</html>