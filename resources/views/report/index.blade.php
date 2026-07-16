<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>

    {{-- Widget Summary --}}
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card shadow-sm text-center p-3 border-0" style="background:linear-gradient(135deg,#1e3a8a,#2563eb);color:#fff;">
                <div style="font-size:2.5rem;"><i class="bx bx-money"></i></div>
                <h6 class="mb-1">Pendapatan Hari Ini</h6>
                <h4 class="fw-bold mb-0">Rp {{ number_format($todayIncome,0,',','.') }}</h4>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card shadow-sm text-center p-3 border-0" style="background:linear-gradient(135deg,#065f46,#059669);color:#fff;">
                <div style="font-size:2.5rem;"><i class="bx bx-calendar-check"></i></div>
                <h6 class="mb-1">Pendapatan Bulan Ini</h6>
                <h4 class="fw-bold mb-0">Rp {{ number_format($monthIncome,0,',','.') }}</h4>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card shadow-sm text-center p-3 border-0" style="background:linear-gradient(135deg,#7c3aed,#a78bfa);color:#fff;">
                <div style="font-size:2.5rem;"><i class="bx bx-user-check"></i></div>
                <h6 class="mb-1">Kunjungan Hari Ini</h6>
                <h4 class="fw-bold mb-0">{{ $todayVisits }} pasien</h4>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card shadow-sm text-center p-3 border-0" style="background:linear-gradient(135deg,#b45309,#f59e0b);color:#fff;">
                <div style="font-size:2.5rem;"><i class="bx bx-trending-up"></i></div>
                <h6 class="mb-1">Kunjungan Bulan Ini</h6>
                <h4 class="fw-bold mb-0">{{ $monthVisits }} pasien</h4>
            </div>
        </div>
    </div>

    {{-- Grafik Pendapatan Harian --}}
    <div class="card shadow-lg p-3 mb-4">
        <h5 class="card-title border-bottom pb-2">Grafik Pendapatan Harian</h5>
        <canvas id="incomeChart" height="80"></canvas>
    </div>

    {{-- Filter & Tabel --}}
    <div class="card shadow-lg p-3">
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-3 gap-2">
            <h5 class="card-title mb-0">Detail Transaksi</h5>
            <form method="GET" action="{{ route('report.index') }}" class="d-flex gap-2 align-items-center flex-wrap">
                <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $startDate->toDateString() }}" style="width:150px">
                <span class="fw-bold">s/d</span>
                <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $endDate->toDateString() }}" style="width:150px">
                <button type="submit" class="btn btn-primary btn-sm"><i class="bx bx-filter-alt"></i> Filter</button>
                <a href="{{ route('report.export-pdf', request()->query()) }}" class="btn btn-danger btn-sm"><i class="bx bxs-file-pdf"></i> Export PDF</a>
            </form>
        </div>

        <div class="alert alert-info py-2 px-3 mb-3">
            Total Pendapatan ({{ $startDate->format('d M Y') }} &ndash; {{ $endDate->format('d M Y') }}):
            <strong>Rp {{ number_format($filteredTotal,0,',','.') }}</strong>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100" id="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Pasien</th>
                        <th>Dokter</th>
                        <th>Total Tagihan</th>
                        <th>Metode</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $i => $trx)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y H:i') }}</td>
                            <td>{{ $trx->medicalRecord->visit->patient->name }}</td>
                            <td>{{ $trx->medicalRecord->visit->doctor->name }}</td>
                            <td class="text-end">Rp {{ number_format($trx->total_amount,0,',','.') }}</td>
                            <td><span class="badge bg-info text-dark">{{ $trx->payment_method }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted">Tidak ada data pada rentang tanggal ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script>
        const ctx = document.getElementById('incomeChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: {!! json_encode($chartData) !!},
                    backgroundColor: 'rgba(37, 99, 235, 0.6)',
                    borderColor: 'rgba(37, 99, 235, 1)',
                    borderWidth: 1,
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(val) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(val);
                            }
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app>
