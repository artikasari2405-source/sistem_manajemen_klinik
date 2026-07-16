<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="card shadow-lg p-3">
        @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Superadmin')
        <div class="mb-3">
            <a class="btn btn-primary" href="{{ route('visit.create') }}" role="button">Tambah Antrian</a>
        </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100" id="data-table">
                <thead>
                    <tr>
                        <th scope="col">No. Antrian</th>
                        <th scope="col">Pasien</th>
                        <th scope="col">Dokter</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($visits as $visit)
                        <tr>
                            <td>
                                <span class="badge bg-primary fs-5">{{ $visit->queue_number }}</span>
                            </td>
                            <td>
                                <strong>{{ $visit->patient->name }}</strong><br>
                                <small class="text-muted">{{ $visit->patient->rm_number }}</small>
                            </td>
                            <td>{{ $visit->doctor->name }}</td>
                            <td>
                                @if($visit->status == 'Waiting')
                                    <span class="badge bg-warning text-dark">Waiting</span>
                                @elseif($visit->status == 'Examining')
                                    <span class="badge bg-info text-dark">Examining</span>
                                @elseif($visit->status == 'Done')
                                    <span class="badge bg-success">Done</span>
                                @else
                                    <span class="badge bg-danger">Cancelled</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('visit.status', $visit) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    <select name="status" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                                        <option value="Waiting" @selected($visit->status == 'Waiting')>Waiting</option>
                                        <option value="Examining" @selected($visit->status == 'Examining')>Examining</option>
                                        <option value="Done" @selected($visit->status == 'Done')>Done</option>
                                        <option value="Cancelled" @selected($visit->status == 'Cancelled')>Cancelled</option>
                                    </select>
                                </form>
                                @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Superadmin')
                                <button type="button" class="btn btn-danger btn-sm btn-delete ms-2" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal" data-route="{{ route('visit.destroy', $visit) }}">
                                    <i class='bx bx-trash'></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            $('#data-table').on('click', '.btn-delete', function() {
                $('#form-delete').attr('action', $(this).data('route'))
            })
        </script>
    @endpush
</x-app>
