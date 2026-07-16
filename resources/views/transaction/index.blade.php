<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="card shadow-lg p-3 mb-4">
        <h5 class="card-title pb-2">Belum Dibayar (Unpaid)</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100">
                <thead>
                    <tr>
                        <th scope="col">No. Antrian</th>
                        <th scope="col">Pasien</th>
                        <th scope="col">Tanggal Selesai</th>
                        <th scope="col">Dokter</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($unpaid as $mr)
                        <tr>
                            <td><span class="badge bg-primary fs-6">{{ $mr->visit->queue_number }}</span></td>
                            <td><strong>{{ $mr->visit->patient->name }}</strong><br><small>{{ $mr->visit->patient->rm_number }}</small></td>
                            <td>{{ \Carbon\Carbon::parse($mr->created_at)->format('d M Y H:i') }}</td>
                            <td>{{ $mr->visit->doctor->name }}</td>
                            <td>
                                <a href="{{ route('transaction.create', $mr) }}" class="btn btn-success btn-sm"><i class='bx bx-wallet'></i> Bayar</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">Tidak ada tagihan yang belum dibayar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card shadow-lg p-3">
        <h5 class="card-title pb-2">Riwayat Pembayaran (Paid)</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100" id="data-table">
                <thead>
                    <tr>
                        <th scope="col">Waktu Transaksi</th>
                        <th scope="col">Pasien</th>
                        <th scope="col">Total</th>
                        <th scope="col">Metode</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($paid as $trx)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y H:i') }}</td>
                            <td>{{ $trx->medicalRecord->visit->patient->name }}</td>
                            <td>Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                            <td><span class="badge bg-info text-dark">{{ $trx->payment_method }}</span></td>
                            <td>
                                <a href="{{ route('transaction.print', $trx) }}" target="_blank" class="btn btn-primary btn-sm"><i class='bx bx-printer'></i> Cetak</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app>
