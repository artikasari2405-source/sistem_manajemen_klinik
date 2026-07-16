<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    public function index()
    {
        return view('patient.index', [
            'title' => 'Data Pasien',
            'patients' => Patient::latest()->get(),
        ]);
    }

    public function create()
    {
        return view('patient.create', [
            'title' => 'Tambah Pasien',
        ]);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'rm_number' => 'required|unique:patients,rm_number',
            'name' => 'required',
            'gender' => 'required|in:L,P',
            'birth_date' => 'required|date',
            'phone' => 'nullable',
            'address' => 'nullable',
        ]);

        Patient::create($validate);
        return to_route('patient.index')->withSuccess('Data pasien berhasil ditambahkan');
    }

    public function show(Patient $patient)
    {
        return view('patient.show', [
            'title' => 'Detail Pasien',
            'patient' => $patient,
        ]);
    }

    public function edit(Patient $patient)
    {
        return view('patient.edit', [
            'title' => 'Edit Pasien',
            'patient' => $patient,
        ]);
    }

    public function update(Request $request, Patient $patient)
    {
        $validate = $request->validate([
            'rm_number' => 'required|unique:patients,rm_number,' . $patient->id,
            'name' => 'required',
            'gender' => 'required|in:L,P',
            'birth_date' => 'required|date',
            'phone' => 'nullable',
            'address' => 'nullable',
        ]);

        $patient->update($validate);
        return to_route('patient.index')->withSuccess('Data pasien berhasil diubah');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return to_route('patient.index')->withSuccess('Data pasien berhasil dihapus');
    }
}
