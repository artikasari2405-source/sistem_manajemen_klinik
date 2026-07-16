<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <form action="{{ route('patient.store') }}" method="post" class="form">
            @csrf
            <div class="row g-3 mb-3">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="rm_number" class="form-label required">No. RM</label>
                        <input class="form-control @error('rm_number') is-invalid @enderror" type="text" id="rm_number" name="rm_number" required value="{{ old('rm_number') }}">
                        @error('rm_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label required">Nama</label>
                        <input class="form-control @error('name') is-invalid @enderror" type="text" id="name" name="name" required value="{{ old('name') }}">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="gender" class="form-label required">Jenis Kelamin</label>
                        <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
<option value="">Pilih Jenis Kelamin</option>
                            <option value="L" @selected(old('gender') == 'L')>Laki-laki</option>
                            <option value="P" @selected(old('gender') == 'P')>Perempuan</option>
                        </select>
                        @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="birth_date" class="form-label required">Tanggal Lahir</label>
                        <input class="form-control @error('birth_date') is-invalid @enderror" type="date" id="birth_date" name="birth_date" required value="{{ old('birth_date') }}">
                        @error('birth_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label required">Telepon</label>
                        <input class="form-control @error('phone') is-invalid @enderror" type="text" id="phone" name="phone" required value="{{ old('phone') }}">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address">{{ old('address') }}</textarea>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
            <div class="text-end">
                <a href="{{ route('patient.index') }}" class="btn btn-warning me-1">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</x-app>