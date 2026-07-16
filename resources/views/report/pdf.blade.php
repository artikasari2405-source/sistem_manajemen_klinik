<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pendapatan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; margin: 0; padding: 20px; }
        h2 { text-align: center; margin-bottom: 4px; font-size: 16px; }
        p.sub { text-align: center; margin-top: 0; color: #555; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th { background-color: #1e3a8a; color: #fff; padding: 6px 8px; text-align: left; }
        td { padding: 5px 8px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background-color: #f3f4f6; }
        .total-row td { font-weight: bold; border-top: 2px solid #1e3a8a; background: #eff6ff; }
        .text-end { text-align: right; }
        .footer { margin-top: 24px; font-size: 10px; color: #777; text-align: center; }
    </style>
</head>
<body>
    <h2>LAPORAN PENDAPATAN KLINIK</h2>
    <p class="sub">Periode: {{ $startDate->format('d M Y') }} &ndash; {{ $endDate->format('d M Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Pasien</th>
                <th>Dokter</th>
                <th class="text-end">Total Tagihan (Rp)</th>
                <th>Metode Bayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $i => $trx)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y') }}</td>
                    <td>{{ $trx->medicalRecord->visit->patient->name }}</td>
                    <td>{{ $trx->medicalRecord->visit->doctor->name }}</td>
                    <td class="text-end">{{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                    <td>{{ $trx->payment_method }}</td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center">Tidak ada data pada rentang ini.</td></tr>
            @endforelse
            <tr class="total-row">
                <td colspan="4" class="text-end">TOTAL PENDAPATAN</td>
                <td class="text-end">{{ number_format($filteredTotal, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d M Y H:i') }} &mdash; Sistem Manajemen Klinik
    </div>
</body>
</html>
