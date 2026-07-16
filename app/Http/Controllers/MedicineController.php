<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index()
    {
        return view('medicine.index', [
            'title' => 'Data Obat',
            'medicines' => Medicine::latest()->get(),
        ]);
    }

    public function create()
    {
        return view('medicine.create', [
            'title' => 'Tambah Obat',
        ]);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'code' => 'required|unique:medicines,code',
            'name' => 'required',
            'stock' => 'required|integer|min:0',
            'price' => 'required|integer|min:0',
        ]);

        Medicine::create($validate);
        return to_route('medicine.index')->withSuccess('Data obat berhasil ditambahkan');
    }

    public function show(Medicine $medicine)
    {
        return view('medicine.show', [
            'title' => 'Detail Obat',
            'medicine' => $medicine,
        ]);
    }

    public function edit(Medicine $medicine)
    {
        return view('medicine.edit', [
            'title' => 'Edit Obat',
            'medicine' => $medicine,
        ]);
    }

    public function update(Request $request, Medicine $medicine)
    {
        $validate = $request->validate([
            'code' => 'required|unique:medicines,code,' . $medicine->id,
            'name' => 'required',
            'stock' => 'required|integer|min:0',
            'price' => 'required|integer|min:0',
        ]);

        $medicine->update($validate);
        return to_route('medicine.index')->withSuccess('Data obat berhasil diubah');
    }

    public function destroy(Medicine $medicine)
    {
        $medicine->delete();
        return to_route('medicine.index')->withSuccess('Data obat berhasil dihapus');
    }
}
