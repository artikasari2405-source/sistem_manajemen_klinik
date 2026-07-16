<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="row">
        <div class="col-md-7">
            <div class="card shadow-lg p-3 mb-4">
                <h5 class="card-title border-bottom pb-2">Rincian Tagihan</h5>
                
                <table class="table table-borderless table-sm mb-0">
                    <tr><th width="30%">Nama Pasien</th><td>: {{ $medicalRecord->visit->patient->name }}</td></tr>
                    <tr><th>No. RM</th><td>: {{ $medicalRecord->visit->patient->rm_number }}</td></tr>
                </table>

                <hr>
                
                <h6 class="fw-bold">Tindakan Medis</h6>
                <table class="table table-sm">
                    @forelse($medicalRecord->recordTreatments as $rt)
                        <tr>
                            <td>{{ $rt->treatment->name }}</td>
                            <td class="text-end">Rp {{ number_format($rt->subtotal,0,',','.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="text-muted text-center">- Tidak ada tindakan -</td></tr>
                    @endforelse
                    <tr>
                        <th class="text-end">Subtotal Tindakan</th>
                        <th class="text-end">Rp {{ number_format($totalTreatment,0,',','.') }}</th>
                    </tr>
                </table>

                <h6 class="fw-bold mt-4">Resep Obat</h6>
                <table class="table table-sm">
                    @forelse($medicalRecord->prescriptions as $p)
                        <tr>
                            <td>{{ $p->medicine->name }} ({{ $p->quantity }}x)</td>
                            <td class="text-end">Rp {{ number_format($p->subtotal,0,',','.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="text-muted text-center">- Tidak ada obat -</td></tr>
                    @endforelse
                    <tr>
                        <th class="text-end">Subtotal Obat</th>
                        <th class="text-end">Rp {{ number_format($totalMedicine,0,',','.') }}</th>
                    </tr>
                </table>

                <table class="table mt-4">
                    <tr>
                        <th class="text-end fs-6">Biaya Layanan/Admin</th>
                        <th class="text-end fs-6">Rp {{ number_format($adminFee,0,',','.') }}</th>
                    </tr>
                    <tr class="table-primary">
                        <th class="text-end fs-5">TOTAL TAGIHAN</th>
                        <th class="text-end fs-5 text-danger">Rp <span id="text-total">{{ number_format($totalAmount,0,',','.') }}</span></th>
                    </tr>
                </table>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card shadow-lg p-3 bg-light">
                <h5 class="card-title border-bottom pb-2">Proses Pembayaran</h5>
                
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('transaction.store', $medicalRecord) }}" method="post">
                    @csrf
                    <input type="hidden" name="total_amount" id="val-total" value="{{ $totalAmount }}">
                    
                    <div class="mb-3">
                        <label for="payment_method" class="form-label fw-bold">Metode Pembayaran</label>
                        <select name="payment_method" id="payment_method" class="form-select" required>
                            <option value="Cash">Tunai (Cash)</option>
                            <option value="Debit">Debit Card</option>
                            <option value="QRIS">QRIS / E-Wallet</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="payment_amount" class="form-label fw-bold">Nominal Pembayaran (Rp)</label>
                        <input type="number" name="payment_amount" id="payment_amount" class="form-control form-control-lg text-end fw-bold" required min="0" value="{{ old('payment_amount', $totalAmount) }}">
                    </div>

                    <div class="mb-4 text-end">
                        <span class="text-muted">Kembalian:</span>
                        <h3 class="text-success fw-bold mb-0">Rp <span id="text-change">0</span></h3>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg"><i class="bx bx-check-circle"></i> Selesaikan Transaksi</button>
                        <a href="{{ route('transaction.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            function calculateChange() {
                let total = parseInt($('#val-total').val()) || 0;
                let pay = parseInt($('#payment_amount').val()) || 0;
                let change = pay - total;
                if(change < 0) change = 0;
                $('#text-change').text(new Intl.NumberFormat('id-ID').format(change));
            }

            $('#payment_amount').on('input', calculateChange);
            calculateChange();
        });
    </script>
    @endpush
</x-app>
