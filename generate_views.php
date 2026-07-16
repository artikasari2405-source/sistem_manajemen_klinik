<?php

$entities = [
    'patient' => [
        'title' => 'Pasien',
        'var' => 'patient',
        'vars' => 'patients',
        'fields' => [
            ['name' => 'rm_number', 'label' => 'No. RM', 'type' => 'text'],
            ['name' => 'name', 'label' => 'Nama', 'type' => 'text'],
            ['name' => 'gender', 'label' => 'Jenis Kelamin', 'type' => 'select', 'options' => ['L' => 'Laki-laki', 'P' => 'Perempuan']],
            ['name' => 'birth_date', 'label' => 'Tanggal Lahir', 'type' => 'date'],
            ['name' => 'phone', 'label' => 'Telepon', 'type' => 'text'],
            ['name' => 'address', 'label' => 'Alamat', 'type' => 'textarea'],
        ]
    ],
    'medicine' => [
        'title' => 'Obat',
        'var' => 'medicine',
        'vars' => 'medicines',
        'fields' => [
            ['name' => 'code', 'label' => 'Kode Obat', 'type' => 'text'],
            ['name' => 'name', 'label' => 'Nama Obat', 'type' => 'text'],
            ['name' => 'stock', 'label' => 'Stok', 'type' => 'number'],
            ['name' => 'price', 'label' => 'Harga', 'type' => 'number'],
        ]
    ],
    'treatment' => [
        'title' => 'Tindakan',
        'var' => 'treatment',
        'vars' => 'treatments',
        'fields' => [
            ['name' => 'name', 'label' => 'Nama Tindakan', 'type' => 'text'],
            ['name' => 'price', 'label' => 'Tarif', 'type' => 'number'],
        ]
    ],
];

foreach ($entities as $e) {
    $dir = __DIR__ . "/resources/views/{$e['var']}";
    if (!is_dir($dir)) mkdir($dir, 0755, true);

    // INDEX
    $ths = "";
    $tds = "";
    foreach ($e['fields'] as $f) {
        $ths .= "<th scope=\"col\">{$f['label']}</th>\n                        ";
        $tds .= "<td>{{ \$item->{$f['name']} }}</td>\n                            ";
    }

    $indexTemplate = <<<HTML
<x-app>
    <x-slot:title>{{ \$title }}</x-slot:title>

    <div class="card shadow-lg p-3">
        <div class="mb-3">
            <a class="btn btn-primary" href="{{ route('{$e['var']}.create') }}" role="button">Tambah</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100" id="data-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        $ths<th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (\${$e['vars']} as \$item)
                        <tr>
                            <td>{{ \$loop->iteration }}</td>
                            $tds<td>
                                <button type="button" class="btn btn-info btn-sm btn-detail"
                                    data-route="{{ route('{$e['var']}.show', \$item) }}">
                                    <i class='bx bx-show'></i>
                                </button>
                                <a href="{{ route('{$e['var']}.edit', \$item) }}" class="btn btn-warning btn-sm">
                                    <i class='bx bx-edit-alt'></i>
                                </a>
                                <button type="button" class="btn btn-danger btn-sm btn-delete" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal" data-route="{{ route('{$e['var']}.destroy', \$item) }}">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('modals')
        <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Detail {$e['title']}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modal-detail">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endpush

    @push('scripts')
        <script>
            $('#data-table').on('click', '.btn-delete', function() {
                $('#form-delete').attr('action', \$(this).data('route'))
            })
            $('#data-table').on('click', '.btn-detail', function() {
                Swal.fire({
                    title: 'Memuat...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $('#modal-detail').load(\$(this).data('route'), function(response, status, xhr) {
                    if (status == "success") {
                        setTimeout(() => {
                            Swal.close();
                            $('#detailModal').modal('show');
                        }, 1000);
                    } else {
                        Swal.fire({title: "Error", text: "Gagal memuat data", icon: "error"});
                    }
                });
            })
        </script>
    @endpush
</x-app>
HTML;

    file_put_contents("$dir/index.blade.php", $indexTemplate);

    // CREATE
    $createInputs = "";
    foreach ($e['fields'] as $f) {
        if ($f['type'] == 'textarea') {
            $createInputs .= <<<HTML
                    <div class="mb-3">
                        <label for="{$f['name']}" class="form-label">{$f['label']}</label>
                        <textarea class="form-control @error('{$f['name']}') is-invalid @enderror" id="{$f['name']}" name="{$f['name']}">{{ old('{$f['name']}') }}</textarea>
                        @error('{$f['name']}')<div class="invalid-feedback">{{ \$message }}</div>@enderror
                    </div>

HTML;
        } elseif ($f['type'] == 'select') {
            $options = "<option value=\"\">Pilih {$f['label']}</option>\n";
            foreach($f['options'] as $v => $l) {
                $options .= "                            <option value=\"$v\" @selected(old('{$f['name']}') == '$v')>$l</option>\n";
            }
            $createInputs .= <<<HTML
                    <div class="mb-3">
                        <label for="{$f['name']}" class="form-label required">{$f['label']}</label>
                        <select class="form-select @error('{$f['name']}') is-invalid @enderror" id="{$f['name']}" name="{$f['name']}" required>
$options                        </select>
                        @error('{$f['name']}')<div class="invalid-feedback">{{ \$message }}</div>@enderror
                    </div>

HTML;
        } else {
            $createInputs .= <<<HTML
                    <div class="mb-3">
                        <label for="{$f['name']}" class="form-label required">{$f['label']}</label>
                        <input class="form-control @error('{$f['name']}') is-invalid @enderror" type="{$f['type']}" id="{$f['name']}" name="{$f['name']}" required value="{{ old('{$f['name']}') }}">
                        @error('{$f['name']}')<div class="invalid-feedback">{{ \$message }}</div>@enderror
                    </div>

HTML;
        }
    }

    $createTemplate = <<<HTML
<x-app>
    <x-slot:title>{{ \$title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <form action="{{ route('{$e['var']}.store') }}" method="post" class="form">
            @csrf
            <div class="row g-3 mb-3">
                <div class="col-md-12">
$createInputs                </div>
            </div>
            <div class="text-end">
                <a href="{{ route('{$e['var']}.index') }}" class="btn btn-warning me-1">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</x-app>
HTML;

    file_put_contents("$dir/create.blade.php", $createTemplate);

    // EDIT
    $editInputs = "";
    foreach ($e['fields'] as $f) {
        if ($f['type'] == 'textarea') {
            $editInputs .= <<<HTML
                    <div class="mb-3">
                        <label for="{$f['name']}" class="form-label">{$f['label']}</label>
                        <textarea class="form-control @error('{$f['name']}') is-invalid @enderror" id="{$f['name']}" name="{$f['name']}">{{ old('{$f['name']}', \${$e['var']}->{$f['name']}) }}</textarea>
                        @error('{$f['name']}')<div class="invalid-feedback">{{ \$message }}</div>@enderror
                    </div>

HTML;
        } elseif ($f['type'] == 'select') {
            $options = "<option value=\"\">Pilih {$f['label']}</option>\n";
            foreach($f['options'] as $v => $l) {
                $options .= "                            <option value=\"$v\" @selected(old('{$f['name']}', \${$e['var']}->{$f['name']}) == '$v')>$l</option>\n";
            }
            $editInputs .= <<<HTML
                    <div class="mb-3">
                        <label for="{$f['name']}" class="form-label required">{$f['label']}</label>
                        <select class="form-select @error('{$f['name']}') is-invalid @enderror" id="{$f['name']}" name="{$f['name']}" required>
$options                        </select>
                        @error('{$f['name']}')<div class="invalid-feedback">{{ \$message }}</div>@enderror
                    </div>

HTML;
        } else {
            $editInputs .= <<<HTML
                    <div class="mb-3">
                        <label for="{$f['name']}" class="form-label required">{$f['label']}</label>
                        <input class="form-control @error('{$f['name']}') is-invalid @enderror" type="{$f['type']}" id="{$f['name']}" name="{$f['name']}" required value="{{ old('{$f['name']}', \${$e['var']}->{$f['name']}) }}">
                        @error('{$f['name']}')<div class="invalid-feedback">{{ \$message }}</div>@enderror
                    </div>

HTML;
        }
    }

    $editTemplate = <<<HTML
<x-app>
    <x-slot:title>{{ \$title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <form action="{{ route('{$e['var']}.update', \${$e['var']}) }}" method="post" class="form">
            @csrf
            @method('put')
            <div class="row g-3 mb-3">
                <div class="col-md-12">
$editInputs                </div>
            </div>
            <div class="text-end">
                <a href="{{ route('{$e['var']}.index') }}" class="btn btn-warning me-1">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</x-app>
HTML;

    file_put_contents("$dir/edit.blade.php", $editTemplate);

    // SHOW
    $showDetails = "";
    foreach ($e['fields'] as $f) {
        $showDetails .= <<<HTML
            <dt class="col-sm-3">{$f['label']}</dt>
            <dd class="col-sm-9">{{ \${$e['var']}->{$f['name']} }}</dd>

HTML;
    }

    $showTemplate = <<<HTML
<dl class="row">
$showDetails</dl>
HTML;

    file_put_contents("$dir/show.blade.php", $showTemplate);
}

echo "Views generated successfully.\n";

