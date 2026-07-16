<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $transaction->medicalRecord->visit->patient->name }}</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; margin: 0; padding: 20px; font-size: 14px; }
        .invoice-container { max-width: 400px; margin: 0 auto; border: 1px dashed #ccc; padding: 20px; }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .text-bold { font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 10px; }
        th, td { padding: 4px 0; }
        .border-top { border-top: 1px dashed #000; }
        .border-bottom { border-bottom: 1px dashed #000; }
        @media print {
            body { padding: 0; }
            .invoice-container { border: none; padding: 0; max-width: 100%; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="invoice-container">
        <h3 class="text-center" style="margin:0;">KLINIK BERSAMA</h3>
        <p class="text-center" style="margin:5px 0;">Jl. Sehat Selalu No. 123<br>Telp: (021) 1234567</p>
        
        <div class="border-top" style="margin-top:10px; padding-top:10px;">
            <table>
                <tr><td>No. Transaksi</td><td class="text-end">TRX-{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}</td></tr>
                <tr><td>Tanggal</td><td class="text-end">{{ \Carbon\Carbon::parse($transaction->created_at)->format('d M Y H:i') }}</td></tr>
                <tr><td>Pasien</td><td class="text-end">{{ $transaction->medicalRecord->visit->patient->name }}</td></tr>
                <tr><td>Dokter</td><td class="text-end">{{ $transaction->medicalRecord->visit->doctor->name }}</td></tr>
            </table>
        </div>

        <div class="border-top" style="margin-top:10px; padding-top:10px;">
            <p class="text-bold" style="margin:5px 0;">Tindakan Medis</p>
            <table>
                @foreach($transaction->medicalRecord->recordTreatments as $rt)
                <tr>
                    <td>{{ $rt->treatment->name }}</td>
                    <td class="text-end">{{ number_format($rt->subtotal,0,',','.') }}</td>
                </tr>
                @endforeach
            </table>
            
            <p class="text-bold" style="margin:5px 0;">Resep Obat</p>
            <table>
                @foreach($transaction->medicalRecord->prescriptions as $p)
                <tr>
                    <td>{{ $p->medicine->name }} ({{ $p->quantity }}x)</td>
                    <td class="text-end">{{ number_format($p->subtotal,0,',','.') }}</td>
                </tr>
                @endforeach
            </table>
            
            <table>
                <tr>
                    <td>Biaya Layanan/Admin</td>
                    <td class="text-end">{{ number_format($adminFee,0,',','.') }}</td>
                </tr>
            </table>
        </div>

        <div class="border-top" style="margin-top:10px; padding-top:10px;">
            <table>
                <tr class="text-bold">
                    <td>TOTAL TAGIHAN</td>
                    <td class="text-end">{{ number_format($transaction->total_amount,0,',','.') }}</td>
                </tr>
                <tr>
                    <td>Bayar ({{ $transaction->payment_method }})</td>
                    <td class="text-end">{{ number_format($transaction->payment_amount,0,',','.') }}</td>
                </tr>
                <tr>
                    <td>Kembalian</td>
                    <td class="text-end">{{ number_format($transaction->payment_amount - $transaction->total_amount,0,',','.') }}</td>
                </tr>
            </table>
        </div>

        <div class="text-center" style="margin-top:20px;">
            <p>Terima kasih atas kunjungan Anda.<br>Semoga lekas sembuh!</p>
        </div>
    </div>
</body>
</html>
