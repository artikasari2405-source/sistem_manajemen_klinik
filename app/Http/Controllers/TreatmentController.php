<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    public function index()
    {
        return view('treatment.index', [
            'title' => 'Data Tindakan',
            'treatments' => Treatment::latest()->get(),
        ]);
    }

    public function create()
    {
        return view('treatment.create', [
            'title' => 'Tambah Tindakan',
        ]);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required',
            'price' => 'required|integer|min:0',
        ]);

        Treatment::create($validate);
        return to_route('treatment.index')->withSuccess('Data tindakan berhasil ditambahkan');
    }

    public function show(Treatment $treatment)
    {
        return view('treatment.show', [
            'title' => 'Detail Tindakan',
            'treatment' => $treatment,
        ]);
    }

    public function edit(Treatment $treatment)
    {
        return view('treatment.edit', [
            'title' => 'Edit Tindakan',
            'treatment' => $treatment,
        ]);
    }

    public function update(Request $request, Treatment $treatment)
    {
        $validate = $request->validate([
            'name' => 'required',
            'price' => 'required|integer|min:0',
        ]);

        $treatment->update($validate);
        return to_route('treatment.index')->withSuccess('Data tindakan berhasil diubah');
    }

    public function destroy(Treatment $treatment)
    {
        $treatment->delete();
        return to_route('treatment.index')->withSuccess('Data tindakan berhasil dihapus');
    }
}
