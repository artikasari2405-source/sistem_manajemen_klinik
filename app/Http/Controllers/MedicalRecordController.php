<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\MedicalRecord;
use App\Models\Medicine;
use App\Models\Treatment;
use App\Models\Prescription;
use App\Models\RecordTreatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicalRecordController extends Controller
{
    public function create(Visit $visit)
    {
        // Pastikan visit belum ada rekam medis dan status bukan Done
        if ($visit->status == 'Done' || $visit->medicalRecord) {
            return redirect()->route('visit.index')->with('error', 'Rekam medis untuk antrian ini sudah selesai.');
        }

        // Otomatis ubah status ke Examining saat dokter membuka form ini (opsional)
        if ($visit->status == 'Waiting') {
            $visit->update(['status' => 'Examining']);
        }

        return view('medical_record.create', [
            'title' => 'Input Rekam Medis',
            'visit' => $visit,
            'medicines' => Medicine::where('stock', '>', 0)->orderBy('name')->get(),
            'treatments' => Treatment::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request, Visit $visit)
    {
        $request->validate([
            'symptoms' => 'required',
            'diagnosis' => 'required',
            'medicines' => 'nullable|array',
            'medicines.*' => 'exists:medicines,id',
            'quantities' => 'nullable|array',
            'quantities.*' => 'numeric|min:1',
            'instructions' => 'nullable|array',
            'treatments' => 'nullable|array',
            'treatments.*' => 'exists:treatments,id',
        ]);

        DB::beginTransaction();
        try {
            // Buat rekam medis
            $mr = MedicalRecord::create([
                'visit_id' => $visit->id,
                'symptoms' => $request->symptoms,
                'diagnosis' => $request->diagnosis,
                'notes' => $request->notes,
            ]);

            // Proses obat (Prescription)
            if ($request->has('medicines') && count($request->medicines) > 0) {
                foreach ($request->medicines as $key => $med_id) {
                    if (!$med_id) continue;
                    $medicine = Medicine::find($med_id);
                    $qty = $request->quantities[$key] ?? 1;
                    
                    if ($medicine->stock < $qty) {
                        throw new \Exception("Stok {$medicine->name} tidak mencukupi. (Sisa: {$medicine->stock})");
                    }

                    Prescription::create([
                        'medical_record_id' => $mr->id,
                        'medicine_id' => $med_id,
                        'quantity' => $qty,
                        'instructions' => $request->instructions[$key] ?? '',
                        'subtotal' => $medicine->price * $qty,
                    ]);

                    // Kurangi stok obat
                    $medicine->decrement('stock', $qty);
                }
            }

            // Proses tindakan (RecordTreatment)
            if ($request->has('treatments') && count($request->treatments) > 0) {
                foreach ($request->treatments as $treat_id) {
                    if (!$treat_id) continue;
                    $treatment = Treatment::find($treat_id);

                    RecordTreatment::create([
                        'medical_record_id' => $mr->id,
                        'treatment_id' => $treat_id,
                        'subtotal' => $treatment->price,
                    ]);
                }
            }

            // Selesaikan visit
            $visit->update(['status' => 'Done']);

            DB::commit();
            return redirect()->route('visit.index')->with('success', 'Rekam Medis berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan rekam medis: ' . $e->getMessage());
        }
    }
}
