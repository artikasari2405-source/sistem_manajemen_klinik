<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="card shadow-lg p-3">
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('visit.store') }}" method="post" class="form">
            @csrf
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="patient_id" class="form-label required">Pilih Pasien</label>
                    <select class="form-select select2-default @error('patient_id') is-invalid @enderror" id="patient_id" name="patient_id" required>
                        <option value="">-- Pilih Pasien --</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" @selected(old('patient_id') == $patient->id)>
                                {{ $patient->rm_number }} - {{ $patient->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="doctor_id" class="form-label required">Pilih Dokter</label>
                    <select class="form-select select2-default @error('doctor_id') is-invalid @enderror" id="doctor_id" name="doctor_id" required>
                        <option value="">-- Pilih Dokter --</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" @selected(old('doctor_id') == $doctor->id)>
                                {{ $doctor->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('doctor_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="text-end mt-4">
                <a href="{{ route('visit.index') }}" class="btn btn-warning me-1">Batal</a>
                <button type="submit" class="btn btn-primary">Daftar Antrian</button>
            </div>
        </form>
    </div>
</x-app>
