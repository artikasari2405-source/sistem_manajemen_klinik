<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-lg p-3 mb-4">
                <h5 class="card-title border-bottom pb-2">Data Pasien</h5>
                <dl class="row mb-0">
                    <dt class="col-sm-4">No. Antrian</dt><dd class="col-sm-8"><span class="badge bg-primary fs-6">{{ $visit->queue_number }}</span></dd>
                    <dt class="col-sm-4">No. RM</dt><dd class="col-sm-8">{{ $visit->patient->rm_number }}</dd>
                    <dt class="col-sm-4">Nama</dt><dd class="col-sm-8"><strong>{{ $visit->patient->name }}</strong></dd>
                    <dt class="col-sm-4">L/P</dt><dd class="col-sm-8">{{ $visit->patient->gender }}</dd>
                    <dt class="col-sm-4">Usia</dt><dd class="col-sm-8">{{ \Carbon\Carbon::parse($visit->patient->birth_date)->age }} thn</dd>
                </dl>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-lg p-3">
                <h5 class="card-title border-bottom pb-2">Form Rekam Medis</h5>
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
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

                <form action="{{ route('medical-record.store', $visit) }}" method="post" class="form">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="symptoms" class="form-label required">Keluhan (Symptoms)</label>
                        <textarea class="form-control" id="symptoms" name="symptoms" rows="3" required>{{ old('symptoms') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="diagnosis" class="form-label required">Diagnosa</label>
                        <textarea class="form-control" id="diagnosis" name="diagnosis" rows="3" required>{{ old('diagnosis') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan Tambahan</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                    </div>

                    <hr class="my-4">
                    <h6 class="fw-bold">Tindakan Medis</h6>
                    <div id="treatments-container"></div>
                    <button type="button" class="btn btn-sm btn-outline-primary mb-3" id="btn-add-treatment"><i class="bx bx-plus"></i> Tambah Tindakan</button>

                    <hr class="my-4">
                    <h6 class="fw-bold">Resep Obat</h6>
                    <div id="medicines-container"></div>
                    <button type="button" class="btn btn-sm btn-outline-primary mb-3" id="btn-add-medicine"><i class="bx bx-plus"></i> Tambah Obat</button>

                    <div class="text-end mt-4">
                        <a href="{{ route('visit.index') }}" class="btn btn-warning me-1">Batal</a>
                        <button type="submit" class="btn btn-success">Selesai & Simpan Rekam Medis</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Template Row Obat -->
    <template id="medicine-row-template">
        <div class="row g-2 mb-2 align-items-center medicine-row">
            <div class="col-md-5">
                <select name="medicines[]" class="form-select" required>
                    <option value="">Pilih Obat</option>
                    @foreach($medicines as $med)
                        <option value="{{ $med->id }}">{{ $med->name }} (Stok: {{ $med->stock }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" name="quantities[]" class="form-control" placeholder="Qty" min="1" required>
            </div>
            <div class="col-md-4">
                <input type="text" name="instructions[]" class="form-control" placeholder="Aturan Pakai (ex: 3x1)" required>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm btn-remove-row"><i class="bx bx-x"></i></button>
            </div>
        </div>
    </template>

    <!-- Template Row Tindakan -->
    <template id="treatment-row-template">
        <div class="row g-2 mb-2 align-items-center treatment-row">
            <div class="col-md-11">
                <select name="treatments[]" class="form-select" required>
                    <option value="">Pilih Tindakan</option>
                    @foreach($treatments as $trt)
                        <option value="{{ $trt->id }}">{{ $trt->name }} (Rp {{ number_format($trt->price,0,',','.') }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm btn-remove-row"><i class="bx bx-x"></i></button>
            </div>
        </div>
    </template>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#btn-add-medicine').click(function() {
                    var template = $('#medicine-row-template').html();
                    $('#medicines-container').append(template);
                });

                $('#btn-add-treatment').click(function() {
                    var template = $('#treatment-row-template').html();
                    $('#treatments-container').append(template);
                });

                $(document).on('click', '.btn-remove-row', function() {
                    $(this).closest('.row').remove();
                });
            });
        </script>
    @endpush
</x-app>
