<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <form action="{{ route('medicine.store') }}" method="post" class="form">
            @csrf
            <div class="row g-3 mb-3">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="code" class="form-label required">Kode Obat</label>
                        <input class="form-control @error('code') is-invalid @enderror" type="text" id="code" name="code" required value="{{ old('code') }}">
                        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label required">Nama Obat</label>
                        <input class="form-control @error('name') is-invalid @enderror" type="text" id="name" name="name" required value="{{ old('name') }}">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="stock" class="form-label required">Stok</label>
                        <input class="form-control @error('stock') is-invalid @enderror" type="number" id="stock" name="stock" required value="{{ old('stock') }}">
                        @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label required">Harga</label>
                        <input class="form-control @error('price') is-invalid @enderror" type="number" id="price" name="price" required value="{{ old('price') }}">
                        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
            <div class="text-end">
                <a href="{{ route('medicine.index') }}" class="btn btn-warning me-1">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</x-app>